<?php
declare(strict_types=1);

require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

function build_mailer(array $config, bool $debugMode, array &$debugLog): PHPMailer
{
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = $config['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['username'];
    $mail->Password = $config['password'];
    $mail->Port = (int) $config['port'];
    $mail->SMTPSecure = $config['encryption'];
    $mail->CharSet = 'UTF-8';

    if ($debugMode) {
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = static function (string $message, int $level) use (&$debugLog): void {
            $debugLog[] = "SMTP[$level] $message";
        };
    }

    return $mail;
}
