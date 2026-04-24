<?php

require_once __DIR__ . '/../../mail/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * ContactController
 * Rota: index.php?page=contact&method=send  (POST, JSON)
 */
class ContactController
{
    public function send()
    {
        // --- Limpa o buffer do index.php para resposta JSON pura ----------------
        if (ob_get_level()) {
            ob_end_clean();
        }

        // --- Cabeçalhos ----------------------------------------------------------
        $allowed = [
            'https://portfoliorodrigo.rodrigo-gotardo.workers.dev',
            'http://localhost:3030',
            'http://localhost:8080',
        ];
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        if (in_array($origin, $allowed, true)) {
            header("Access-Control-Allow-Origin: $origin");
        }
        header('Access-Control-Allow-Methods: POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(204);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        // --- Lê e valida o JSON --------------------------------------------------
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
            echo json_encode(['success' => false, 'message' => 'Invalid e-mail address.']);
            exit;
        }

        // =========================================================
        //  CONFIGURAÇÃO SMTP  (altere apenas estas constantes)
        // =========================================================
        $smtpHost     = 'smtp.gmail.com';
        $smtpPort     = 587;
        $smtpUser     = 'dev.rodrigo.dev@gmail.com';
        $smtpPass     = 'GmLeGaHeRa8943Il';        // Senha de App do Google
        $mailFrom     = 'dev.rodrigo.dev@gmail.com';
        $mailFromName = 'Rodrigo Gotardo | Portfolio';
        $mailTo       = 'dev.rodrigo.dev@gmail.com';
        // =========================================================

        try {
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host       = $smtpHost;
            $mail->SMTPAuth   = true;
            $mail->Username   = $smtpUser;
            $mail->Password   = $smtpPass;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $smtpPort;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom($mailFrom, $mailFromName);
            $mail->addAddress($mailTo, 'Rodrigo Gotardo');
            $mail->addReplyTo($email, $name);

            $mail->isHTML(true);
            $mail->Subject = "Portfolio contact from {$name}";
            $mail->Body    = "
                <html><body style='font-family:Arial,sans-serif;color:#333'>
                  <h2 style='color:#6c63ff'>&#9993; New contact from your portfolio</h2>
                  <table cellpadding='6'>
                    <tr><td><strong>Name:</strong></td><td>" . htmlspecialchars($name) . "</td></tr>
                    <tr><td><strong>Email:</strong></td><td><a href='mailto:{$email}'>{$email}</a></td></tr>
                  </table>
                  <h3>Message:</h3>
                  <p style='background:#f4f4f4;padding:16px;border-radius:8px;line-height:1.6'>"
                    . nl2br(htmlspecialchars($message)) . "
                  </p>
                  <hr style='border:none;border-top:1px solid #ddd;margin-top:24px'>
                  <small style='color:#999'>Sent via portfolio contact form</small>
                </body></html>
            ";
            $mail->AltBody = "Name: {$name}\nEmail: {$email}\n\nMessage:\n{$message}";

            $mail->send();

            echo json_encode(['success' => true, 'message' => 'Email sent successfully.']);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to send email: ' . $mail->ErrorInfo,
            ]);
        }

        exit;
    }
}
