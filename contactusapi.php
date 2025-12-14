<?php
require_once __DIR__ . '/logger.php';
require_once __DIR__ . '/config.php';
header('Content-Type: text/html; charset=utf-8');

logger_log('contact', 'INFO', 'Contact request received', [
    'method' => $_SERVER['REQUEST_METHOD'] ?? 'CLI',
    'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
    'ua' => $_SERVER['HTTP_USER_AGENT'] ?? null
]);

if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($_POST["honeypot"])) {
    if (defined('RECAPTCHA_ENABLED') && RECAPTCHA_ENABLED) {
        $recaptcha_secret = "6Ldj71grAAAAAP4uoW0egdNTwH_11VRljjsvChHt";
        $recaptcha_response = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';
        if (empty($recaptcha_response)) {
            logger_log('contact', 'WARNING', 'Missing reCAPTCHA response');
            echo '<div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin: 20px 0;">
                <h2>reCAPTCHA Verification Failed</h2>
                <p>Please complete the reCAPTCHA verification.</p>
            </div>';
            exit;
        }
        $verify_response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$recaptcha_secret.'&response='.$recaptcha_response);
        $response_data = json_decode($verify_response);
        if (!$response_data || !$response_data->success) {
            logger_log('contact', 'WARNING', 'reCAPTCHA verification failed', [ 'resp' => $response_data ]);
            echo '<div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin: 20px 0;">
                <h2>reCAPTCHA Verification Failed</h2>
                <p>Please complete the reCAPTCHA verification.</p>
            </div>';
            exit;
        }
    }

    // Validate required fields
    $required_fields = ['name', 'email', 'subject', 'message'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            logger_log('contact', 'WARNING', 'Missing required field', [ 'field' => $field ]);
            echo '<div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin: 20px 0;">
                <h2>Missing Required Fields</h2>
                <p>Please fill in all required fields.</p>
            </div>';
            exit;
        }
    }

    $to = "ask@shrutipahadi.com";
    $name = htmlspecialchars($_POST["name"]);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars($_POST["subject"]);
    $message = htmlspecialchars($_POST["message"]);

    $email_subject = "New Message from Contact Form: ". $subject;
    $email_template = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Contact Form Submission</title>
        </head>
        <body>
            <div style="background-color: #f8f9fa; padding: 20px;">
                <div style="max-width: 600px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                    <h2>Contact Form Submission</h2>
                    <p><strong>Name:</strong> '.$name.'</p>
                    <p><strong>Email:</strong> '.$email.'</p>
                    <p><strong>Subject:</strong> '.$subject.'</p>
                    <p><strong>Message:</strong></p>
                    <p>'.$message.'</p>
                </div>
            </div>
        </body>
        </html>
        ';

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: " . $name . "<" . $email . ">";
    
    $sent = false;
    if (defined('MAIL_TEST_MODE') && MAIL_TEST_MODE === true) {
        $outboxDir = __DIR__ . '/outbox';
        if (!is_dir($outboxDir)) {
            @mkdir($outboxDir, 0777, true);
        }
        $eml = "To: {$to}\r\nSubject: {$email_subject}\r\n{$headers}\r\n\r\n{$email_template}";
        $file = $outboxDir . '/' . date('Ymd_His') . '_' . preg_replace('/[^a-z0-9]+/i','_', $name) . '_' . uniqid() . '.eml';
        $sent = (bool)@file_put_contents($file, $eml);
        logger_log('contact', $sent ? 'INFO' : 'ERROR', $sent ? 'Mail written to outbox' : 'Failed writing outbox', [ 'file' => $file ]);
    } else {
        if (stripos(PHP_OS, 'WIN') === 0) {
            if (defined('SMTP_HOST')) { ini_set('SMTP', SMTP_HOST); }
            if (defined('SMTP_PORT')) { ini_set('smtp_port', SMTP_PORT); }
            if (defined('MAIL_FROM_ADDRESS')) { ini_set('sendmail_from', MAIL_FROM_ADDRESS); }
        }
        $sent = @mail($to, $email_subject, $email_template, $headers);
    }

    if ($sent) {
        echo '<div style="background-color: #d4edda; color: #155724; padding: 10px; margin: 20px 0;">
            Hi '. $name. ', <br/>
            <h3>Thank you for contacting us!</h3>
            <p>We have received your message and will get back to you shortly.</p>
        </div>';
    } else {
        echo '<div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin: 20px 0;">
            Hi '. $name. ', <br/>
            <h2>Oops! Something went wrong.</h2>
            <p>Sorry, we were unable to send your message. Please try again later.</p>
        </div>';
    }
} else {
    logger_log('contact', 'WARNING', 'Invalid request to contact endpoint', [ 'method' => $_SERVER['REQUEST_METHOD'] ?? 'CLI' ]);
    echo '<div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin: 20px 0;">
        <h2>Invalid Request</h2>
        <p>Please submit the form properly.</p>
    </div>';
}
?>
