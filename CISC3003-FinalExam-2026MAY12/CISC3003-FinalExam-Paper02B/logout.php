<?php
declare(strict_types=1);

require_once __DIR__ . '/php/bootstrap.php';

unset($_SESSION['mail_debug']);
flash('info', 'Debug Cleared', 'Temporary Scenario B session data has been cleared.');
redirect('index.php');
