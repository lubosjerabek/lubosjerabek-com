<?php
/**
 * Internal API endpoint: GET /api/posts.php
 *
 * Returns LinkedIn posts as JSON.
 * Caches results for 1 hour to avoid hammering the LinkedIn API.
 *
 * Query params:
 *   count  (int)  — number of posts to return, default 10, max 50
 *   fresh  (bool) — pass ?fresh=1 to bypass cache (owner use only)
 */

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

// Block direct iframe embedding
header('X-Frame-Options: DENY');

require_once dirname(__DIR__) . '/includes/LinkedInClient.php';

$count = min((int)($_GET['count'] ?? 10), 50);

try {
    $client = new LinkedInClient();

    if (!$client->hasToken()) {
        echo json_encode(['posts' => [], 'count' => 0]);
        exit;
    }

    $posts = $client->getPosts($count);

    echo json_encode([
        'posts' => $posts,
        'count' => count($posts),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error'   => 'api_error',
        'message' => $e->getMessage(),
    ]);
}
