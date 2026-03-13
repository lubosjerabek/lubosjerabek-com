<?php
/**
 * Contact form handler — POST /api/contact.php
 * Returns JSON. Sends an email via PHP mail().
 */

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$body    = trim($_POST['message'] ?? '');

// Validate
if (!$name || !$email || !$body) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
    exit;
}

if (strlen($name) > 120 || strlen($email) > 254 || strlen($body) > 5000) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Input too long.']);
    exit;
}

// Build email
$to      = 'you@example.com'; // <-- change this to your email
$subject = 'New contact from ' . $name . ' — yoursite.com';

$mailBody = "Name:    {$name}\n"
          . "Email:   {$email}\n"
          . "Message:\n\n{$body}\n";

$headers  = "From: noreply@yoursite.com\r\n"
          . "Reply-To: {$email}\r\n"
          . "X-Mailer: PHP/" . PHP_VERSION;

$sent = mail($to, $subject, $mailBody, $headers);

if ($sent) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to send email. Please try contacting directly.']);
}
