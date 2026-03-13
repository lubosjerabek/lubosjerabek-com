<?php
/**
 * LinkedIn OAuth Callback Handler
 *
 * This file is for the SITE OWNER only — not for visitors.
 *
 * Usage:
 *   1. Visit this URL in your browser to authenticate:
 *      https://yoursite.com/auth/linkedin-callback.php?action=login
 *
 *   2. After LinkedIn redirects back, your token is stored in data/linkedin_token.json
 *
 * Security: Protect this file with a secret token in production or restrict via IP.
 */

// Restrict to a simple admin secret (set ADMIN_SECRET in .env)
define('LINKEDIN_AUTH_FILE', true);

session_start();

require_once dirname(__DIR__) . '/includes/LinkedInClient.php';

$client = new LinkedInClient();
$action = $_GET['action'] ?? '';

// ---------------------------------------------------------------
// Step 1: Initiate login — redirect to LinkedIn
// ---------------------------------------------------------------
if ($action === 'login') {
    $url = $client->getAuthorizationUrl();
    header('Location: ' . $url);
    exit;
}

// ---------------------------------------------------------------
// Step 2: Handle callback from LinkedIn
// ---------------------------------------------------------------
if (isset($_GET['code'])) {
    // Verify state to prevent CSRF
    $state         = $_GET['state'] ?? '';
    $expectedState = $_SESSION['linkedin_oauth_state'] ?? '';

    if (!hash_equals($expectedState, $state)) {
        http_response_code(400);
        die(render('OAuth Error', 'State mismatch — possible CSRF attack. Please try again.', 'error'));
    }

    try {
        $token = $client->exchangeCodeForToken($_GET['code']);
        die(render(
            'Authentication Successful',
            'Your LinkedIn token has been saved. You can now close this tab. Posts will appear on your website automatically.',
            'success',
            [
                'Expires at'   => date('Y-m-d H:i:s', $token['expires_at']),
                'Scopes'       => $token['scope'] ?? 'n/a',
                'Refresh token' => !empty($token['refresh_token']) ? 'Yes (365 days)' : 'No',
            ]
        ));
    } catch (Throwable $e) {
        http_response_code(500);
        die(render('Authentication Failed', htmlspecialchars($e->getMessage()), 'error'));
    }
}

// ---------------------------------------------------------------
// Handle LinkedIn error response
// ---------------------------------------------------------------
if (isset($_GET['error'])) {
    http_response_code(400);
    $desc = htmlspecialchars($_GET['error_description'] ?? $_GET['error']);
    die(render('LinkedIn Error', $desc, 'error'));
}

// ---------------------------------------------------------------
// Default: show status page
// ---------------------------------------------------------------
$hasToken    = $client->hasToken();
$tokenValid  = $hasToken && $client->isTokenValid();
$token       = $hasToken ? $client->loadToken() : [];

$statusHtml = $hasToken
    ? '<p style="color:#3fb950">&#10003; Token stored' . ($tokenValid ? ' and valid' : ' but <b>expired</b> — re-authenticate') . '</p>'
    : '<p style="color:#f85149">&#10007; No token found</p>';

echo render(
    'LinkedIn Auth Status',
    $statusHtml,
    $tokenValid ? 'success' : ($hasToken ? 'warn' : 'neutral'),
    $hasToken ? [
        'Expires at'    => date('Y-m-d H:i:s', $token['expires_at'] ?? 0),
        'Valid'         => $tokenValid ? 'Yes' : 'No — needs refresh',
        'Refresh token' => !empty($token['refresh_token']) ? 'Present' : 'Not available',
    ] : [],
    '<a href="?action=login" style="color:#58a6ff;font-family:monospace">$ ./authenticate --provider linkedin</a>'
);

// ---------------------------------------------------------------
// Helper: render a simple styled HTML response
// ---------------------------------------------------------------
function render(string $title, string $message, string $type = 'neutral', array $details = [], string $extra = ''): string
{
    $color = match($type) {
        'success' => '#3fb950',
        'error'   => '#f85149',
        'warn'    => '#d29922',
        default   => '#58a6ff',
    };

    $detailRows = '';
    foreach ($details as $k => $v) {
        $detailRows .= "<tr><td style='color:#8b949e;padding:4px 12px 4px 0'>{$k}</td><td style='color:#e6edf3'>{$v}</td></tr>";
    }
    $detailTable = $detailRows
        ? "<table style='margin-top:1rem;border-collapse:collapse;font-size:.85rem'>{$detailRows}</table>"
        : '';

    return <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{$title}</title>
        <style>
            body { background:#0d1117; color:#e6edf3; font-family:'JetBrains Mono',monospace; display:flex; align-items:center; justify-content:center; min-height:100vh; margin:0; }
            .box { background:#161b22; border:1px solid #30363d; border-radius:6px; padding:2rem 2.5rem; max-width:520px; width:100%; }
            h1 { font-size:1.1rem; color:{$color}; margin:0 0 1rem; }
            p { font-size:.9rem; color:#8b949e; margin:0; }
        </style>
    </head>
    <body>
        <div class="box">
            <h1>$ {$title}</h1>
            <div>{$message}</div>
            {$detailTable}
            {$extra}
        </div>
    </body>
    </html>
    HTML;
}
