<?php

/**
 * LinkedInClient
 *
 * Handles LinkedIn OAuth 2.0 and UGC Posts API for fetching your own posts.
 *
 * Setup:
 *  1. Create a LinkedIn Developer App and add OAuth 2.0 credentials to .env
 *  2. Visit /auth/linkedin-callback.php?action=login to authenticate (site owner only)
 *  3. Token is stored in data/linkedin_token.json (gitignored)
 */
class LinkedInClient
{
    private string $clientId;
    private string $clientSecret;
    private string $redirectUri;
    private string $tokenFile;
    private string $cacheFile;
    private int    $cacheTtl = 3600; // 1 hour

    private const AUTH_URL  = 'https://www.linkedin.com/oauth/v2/authorization';
    private const TOKEN_URL = 'https://www.linkedin.com/oauth/v2/accessToken';
    private const API_BASE  = 'https://api.linkedin.com/v2';
    private const SCOPES    = ['openid', 'profile'];

    public function __construct()
    {
        $this->clientId     = $this->env('LINKEDIN_CLIENT_ID');
        $this->clientSecret = $this->env('LINKEDIN_CLIENT_SECRET');
        $this->redirectUri  = $this->env('LINKEDIN_REDIRECT_URI');
        $this->tokenFile    = dirname(__DIR__) . '/data/linkedin_token.json';
        $this->cacheFile    = dirname(__DIR__) . '/data/linkedin_posts_cache.json';
    }

    // -------------------------------------------------------
    // OAuth Flow
    // -------------------------------------------------------

    /** Redirect user to LinkedIn authorization page */
    public function getAuthorizationUrl(): string
    {
        $state = bin2hex(random_bytes(16));
        $_SESSION['linkedin_oauth_state'] = $state;

        return self::AUTH_URL . '?' . http_build_query([
            'response_type' => 'code',
            'client_id'     => $this->clientId,
            'redirect_uri'  => $this->redirectUri,
            'scope'         => implode(' ', self::SCOPES),
            'state'         => $state,
        ]);
    }

    /** Exchange authorization code for tokens and persist them */
    public function exchangeCodeForToken(string $code): array
    {
        $response = $this->httpPost(self::TOKEN_URL, [
            'grant_type'    => 'authorization_code',
            'code'          => $code,
            'redirect_uri'  => $this->redirectUri,
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
        ]);

        $token = $this->parseTokenResponse($response);
        $this->saveToken($token);

        return $token;
    }

    /** Use refresh token to get a fresh access token */
    public function refreshAccessToken(): bool
    {
        $token = $this->loadToken();
        if (empty($token['refresh_token'])) {
            return false;
        }

        $response = $this->httpPost(self::TOKEN_URL, [
            'grant_type'    => 'refresh_token',
            'refresh_token' => $token['refresh_token'],
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
        ]);

        $newToken = $this->parseTokenResponse($response, $token);
        $this->saveToken($newToken);

        return true;
    }

    // -------------------------------------------------------
    // API Methods
    // -------------------------------------------------------

    /** Fetch authenticated user's person URN */
    public function getPersonId(): ?string
    {
        $data = $this->apiGet('/userinfo');
        return $data['sub'] ?? null; // 'sub' contains the member URN identifier
    }

    /**
     * Fetch the user's own UGC posts.
     * Returns an array of simplified post objects.
     */
    public function getPosts(int $count = 10): array
    {
        // Serve from cache if fresh
        if ($this->isCacheFresh()) {
            return $this->loadCache();
        }

        $personId = $this->getPersonId();
        if (!$personId) {
            throw new RuntimeException('Could not retrieve LinkedIn person ID.');
        }

        $authorUrn = urlencode("urn:li:person:{$personId}");
        $data = $this->apiGet("/ugcPosts?q=authors&authors=List({$authorUrn})&count={$count}&sortBy=LAST_MODIFIED");

        $posts = $this->normalizePosts($data['elements'] ?? []);
        $this->saveCache($posts);

        return $posts;
    }

    // -------------------------------------------------------
    // Token Storage
    // -------------------------------------------------------

    public function saveToken(array $token): void
    {
        $dir = dirname($this->tokenFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0750, true);
        }
        file_put_contents($this->tokenFile, json_encode($token, JSON_PRETTY_PRINT));
        chmod($this->tokenFile, 0600);
    }

    public function loadToken(): array
    {
        if (!file_exists($this->tokenFile)) {
            return [];
        }
        $data = json_decode(file_get_contents($this->tokenFile), true);
        return is_array($data) ? $data : [];
    }

    public function isTokenValid(): bool
    {
        $token = $this->loadToken();
        return !empty($token['access_token'])
            && (!isset($token['expires_at']) || $token['expires_at'] > time() + 60);
    }

    public function hasToken(): bool
    {
        $token = $this->loadToken();
        return !empty($token['access_token']);
    }

    // -------------------------------------------------------
    // Internal Helpers
    // -------------------------------------------------------

    private function apiGet(string $path): array
    {
        // Auto-refresh token if expired
        if (!$this->isTokenValid()) {
            if (!$this->refreshAccessToken()) {
                throw new RuntimeException('Access token expired and refresh failed. Please re-authenticate.');
            }
        }

        $token = $this->loadToken();
        $url   = self::API_BASE . $path;

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $token['access_token'],
                'X-Restli-Protocol-Version: 2.0.0',
                'Content-Type: application/json',
            ],
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $body = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err  = curl_error($ch);
        curl_close($ch);

        if ($err) {
            throw new RuntimeException("cURL error: {$err}");
        }
        if ($code !== 200) {
            throw new RuntimeException("LinkedIn API error {$code}: {$body}");
        }

        $data = json_decode($body, true);
        if (!is_array($data)) {
            throw new RuntimeException('Invalid JSON response from LinkedIn API.');
        }

        return $data;
    }

    private function httpPost(string $url, array $params): string
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($params),
            CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $body = curl_exec($ch);
        $err  = curl_error($ch);
        curl_close($ch);

        if ($err) {
            throw new RuntimeException("cURL error: {$err}");
        }

        return $body;
    }

    private function parseTokenResponse(string $response, array $existing = []): array
    {
        $data = json_decode($response, true);
        if (!isset($data['access_token'])) {
            throw new RuntimeException('Token response missing access_token: ' . $response);
        }

        return array_merge($existing, [
            'access_token'        => $data['access_token'],
            'expires_in'          => $data['expires_in'] ?? 5183944,
            'expires_at'          => time() + ($data['expires_in'] ?? 5183944),
            'refresh_token'       => $data['refresh_token'] ?? ($existing['refresh_token'] ?? null),
            'refresh_token_expires_in' => $data['refresh_token_expires_in'] ?? null,
            'scope'               => $data['scope'] ?? null,
        ]);
    }

    /** Normalize raw UGC post elements into simpler display objects */
    private function normalizePosts(array $elements): array
    {
        $posts = [];
        foreach ($elements as $el) {
            $text = $el['specificContent']['com.linkedin.ugc.ShareContent']['shareCommentary']['text'] ?? '';
            if (empty(trim($text))) {
                continue;
            }

            $createdMs = $el['created']['time'] ?? null;
            $posts[] = [
                'id'        => $el['id'] ?? null,
                'text'      => $text,
                'date'      => $createdMs ? date('Y-m-d', (int)($createdMs / 1000)) : null,
                'date_iso'  => $createdMs ? date('c', (int)($createdMs / 1000)) : null,
                'likes'     => $el['totalSocialActivityCounts']['likeCount'] ?? null,
                'comments'  => $el['totalSocialActivityCounts']['commentCount'] ?? null,
            ];
        }
        return $posts;
    }

    private function isCacheFresh(): bool
    {
        if (!file_exists($this->cacheFile)) {
            return false;
        }
        $data = json_decode(file_get_contents($this->cacheFile), true);
        return isset($data['cached_at']) && (time() - $data['cached_at']) < $this->cacheTtl;
    }

    private function loadCache(): array
    {
        $data = json_decode(file_get_contents($this->cacheFile), true);
        return $data['posts'] ?? [];
    }

    private function saveCache(array $posts): void
    {
        file_put_contents($this->cacheFile, json_encode([
            'cached_at' => time(),
            'posts'     => $posts,
        ], JSON_PRETTY_PRINT));
    }

    private function env(string $key): string
    {
        // Try $_ENV / getenv first (works with Apache SetEnv or php-fpm)
        $val = $_ENV[$key] ?? getenv($key);
        if ($val !== false && $val !== '') {
            return $val;
        }

        // Fall back to parsing .env file
        $envFile = dirname(__DIR__) . '/.env';
        if (file_exists($envFile)) {
            foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                if (str_starts_with(trim($line), '#')) continue;
                [$k, $v] = array_pad(explode('=', $line, 2), 2, '');
                if (trim($k) === $key) {
                    return trim($v);
                }
            }
        }

        throw new RuntimeException("Missing environment variable: {$key}. Check your .env file.");
    }
}
