<?php
// ============================================================
//  Portfolio Contact Form — PHPMailer Backend
//  Arquivo: mail/send.php
// ============================================================

// --- Permite chamada da origem do portfólio (CORS) ----------
$allowed = [
    'https://portfoliorodrigo.rodrigo-gotardo.workers.dev',
    'http://localhost:3030',
    'http://localhost:8080',
];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowed)) {
    header("Access-Control-Allow-Origin: $origin");
}
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

// Preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Só aceita POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// --- Lê o corpo JSON ----------------------------------------
$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);

$name    = trim($data['name']    ?? '');
$email   = trim($data['email']   ?? '');
$message = trim($data['message'] ?? '');

if (!$name || !$email || !$message) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
    exit;
}

// --- Carrega o PHPMailer ------------------------------------
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// ============================================================
//  CONFIGURAÇÃO SMTP — edite apenas estas linhas
// ============================================================
define('SMTP_HOST',     'smtp.gmail.com');
define('SMTP_PORT',     587);
define('SMTP_USER',     'dev.rodrigo.dev@gmail.com');   // seu Gmail
define('SMTP_PASS',     'GmLeGaHeRa8943Il');              // Senha de App do Google
define('MAIL_FROM',     'dev.rodrigo.dev@gmail.com');
define('MAIL_FROM_NAME','Rodrigo Gotardo | Portfolio');
define('MAIL_TO',       'dev.rodrigo.dev@gmail.com');
// ============================================================

try {
    $mail = new PHPMailer(true);

    // Servidor SMTP
    $mail->isSMTP();
    $mail->Host       = SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USER;
    $mail->Password   = SMTP_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = SMTP_PORT;
    $mail->CharSet    = 'UTF-8';

    // Remetente e destinatário
    $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
    $mail->addAddress(MAIL_TO, 'Rodrigo Gotardo');
    $mail->addReplyTo($email, $name);   // reply vai para quem enviou

    // Conteúdo
    $mail->isHTML(true);
    $mail->Subject = "Portfolio contact from $name";
    $mail->Body    = "
        <html><body style='font-family:Arial,sans-serif;color:#333'>
          <h2 style='color:#6c63ff'>New contact from your portfolio</h2>
          <table>
            <tr><td><strong>Name:</strong></td><td>" . htmlspecialchars($name) . "</td></tr>
            <tr><td><strong>Email:</strong></td><td><a href='mailto:$email'>$email</a></td></tr>
          </table>
          <h3>Message:</h3>
          <p style='background:#f4f4f4;padding:16px;border-radius:8px'>"
            . nl2br(htmlspecialchars($message)) . "
          </p>
          <hr>
          <small style='color:#999'>Sent via portfolio contact form</small>
        </body></html>
    ";
    $mail->AltBody = "Name: $name\nEmail: $email\n\nMessage:\n$message";

    $mail->send();

    echo json_encode(['success' => true, 'message' => 'Email sent successfully.']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to send email.',
        'debug'   => $mail->ErrorInfo   // remova em produção
    ]);
}
