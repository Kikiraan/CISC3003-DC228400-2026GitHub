<?php
declare(strict_types=1);

require_once __DIR__ . '/php/bootstrap.php';

flash('info', 'Logout Page', 'Scenario A does not use an authenticated session, so you have been redirected to the form.');
redirect('index.php');
