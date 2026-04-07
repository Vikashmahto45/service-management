<?php
  /**
   * Mail Helper - Password Reset Email
   * 
   * Sends a password reset email using PHP's mail() function.
   * For production, consider using PHPMailer with SMTP for more reliable delivery.
   */

  function sendResetEmail($toEmail, $userName, $resetLink){
    $subject = SITENAME . ' - Password Reset Request';

    // HTML email body
    $body = '
    <html>
    <head>
      <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #343a40; color: #fff; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background-color: #f8f9fa; padding: 30px; border: 1px solid #dee2e6; }
        .button { display: inline-block; padding: 12px 30px; background-color: #28a745; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 20px 0; }
        .footer { padding: 15px; text-align: center; font-size: 12px; color: #6c757d; }
        .warning { background-color: #fff3cd; padding: 10px; border-radius: 5px; margin-top: 15px; font-size: 13px; }
      </style>
    </head>
    <body>
      <div class="container">
        <div class="header">
          <h2>' . SITENAME . '</h2>
        </div>
        <div class="content">
          <h3>Hello, ' . htmlspecialchars($userName) . '!</h3>
          <p>We received a request to reset your password. Click the button below to create a new password:</p>
          <p style="text-align: center;">
            <a href="' . $resetLink . '" class="button">Reset My Password</a>
          </p>
          <p>Or copy and paste this link into your browser:</p>
          <p style="word-break: break-all; font-size: 13px; color: #6c757d;">' . $resetLink . '</p>
          <div class="warning">
            <strong>⚠ Note:</strong> This link will expire in <strong>1 hour</strong>. If you didn\'t request a password reset, you can safely ignore this email.
          </div>
        </div>
        <div class="footer">
          <p>&copy; ' . date('Y') . ' ' . SITENAME . '. All rights reserved.</p>
        </div>
      </div>
    </body>
    </html>';

    // Email headers
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: " . SITENAME . " <noreply@sms.local>\r\n";
    $headers .= "Reply-To: noreply@sms.local\r\n";

    // Send email
    $sent = @mail($toEmail, $subject, $body, $headers);

    // Log the reset link for development (in case mail server is not configured)
    $logFile = dirname(APPROOT) . '/password_reset_log.txt';
    $logEntry = "[" . date('Y-m-d H:i:s') . "] Reset link for {$toEmail}: {$resetLink}\n";
    file_put_contents($logFile, $logEntry, FILE_APPEND);

    return $sent;
  }
