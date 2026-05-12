<?php
declare(strict_types=1);

require_once __DIR__ . '/mailer.php';

function current_user(): ?array
{
    if (!isset($_SESSION['user_id']) || !is_int($_SESSION['user_id'])) {
        return null;
    }

    return find_user_by_id($_SESSION['user_id']);
}

function require_login(): array
{
    $user = current_user();

    if (!$user) {
        flash('error', 'Login Required', 'Please sign in to access the dashboard.');
        redirect(project_url('login.php'));
    }

    return $user;
}

function find_user_by_id(int $id): ?array
{
    $connection = get_db_connection();
    $statement = $connection->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');

    if (!$statement) {
        throw new RuntimeException('Unable to prepare the user lookup statement.');
    }

    $statement->bind_param('i', $id);
    $statement->execute();
    $result = $statement->get_result();
    $user = $result ? $result->fetch_assoc() : null;
    $statement->close();

    return $user ?: null;
}

function find_user_by_email(string $email): ?array
{
    $connection = get_db_connection();
    $statement = $connection->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');

    if (!$statement) {
        throw new RuntimeException('Unable to prepare the email lookup statement.');
    }

    $statement->bind_param('s', $email);
    $statement->execute();
    $result = $statement->get_result();
    $user = $result ? $result->fetch_assoc() : null;
    $statement->close();

    return $user ?: null;
}

function email_exists(string $email): bool
{
    return find_user_by_email($email) !== null;
}

function find_user_by_token_column(string $column, string $hash): ?array
{
    $allowedColumns = ['account_activation_hash', 'password_reset_hash'];

    if (!in_array($column, $allowedColumns, true)) {
        throw new InvalidArgumentException('Invalid token column requested.');
    }

    $connection = get_db_connection();
    $query = sprintf('SELECT * FROM users WHERE %s = ? LIMIT 1', $column);
    $statement = $connection->prepare($query);

    if (!$statement) {
        throw new RuntimeException('Unable to prepare the token lookup statement.');
    }

    $statement->bind_param('s', $hash);
    $statement->execute();
    $result = $statement->get_result();
    $user = $result ? $result->fetch_assoc() : null;
    $statement->close();

    return $user ?: null;
}

function send_activation_email(array $user, string $token): void
{
    $config = require __DIR__ . '/mail_config.php';
    $mailer = create_mailer($config);
    $activationUrl = build_url($config['base_url'], 'verify-account.php', ['token' => $token]);

    $mailer->setFrom($config['from_email'], $config['from_name']);
    $mailer->addAddress($user['email'], $user['name']);
    $mailer->Subject = 'Activate Your Scenario C Account';
    $mailer->isHTML(true);
    $mailer->Body = sprintf(
        '<h2>Hello %s,</h2><p>Thank you for registering.</p><p>Please activate your account by clicking the link below:</p><p><a href="%s">%s</a></p>',
        h($user['name']),
        h($activationUrl),
        h($activationUrl)
    );
    $mailer->AltBody = "Hello {$user['name']},\n\nPlease activate your account by visiting:\n{$activationUrl}";
    $mailer->send();
}

function send_password_reset_email(array $user, string $token): void
{
    $config = require __DIR__ . '/mail_config.php';
    $mailer = create_mailer($config);
    $resetUrl = build_url($config['base_url'], 'reset-password.php', ['token' => $token]);

    $mailer->setFrom($config['from_email'], $config['from_name']);
    $mailer->addAddress($user['email'], $user['name']);
    $mailer->Subject = 'Reset Your Scenario C Password';
    $mailer->isHTML(true);
    $mailer->Body = sprintf(
        '<h2>Hello %s,</h2><p>Click the secure link below to reset your password:</p><p><a href="%s">%s</a></p><p>This link expires in 1 hour.</p>',
        h($user['name']),
        h($resetUrl),
        h($resetUrl)
    );
    $mailer->AltBody = "Hello {$user['name']},\n\nReset your password using this link:\n{$resetUrl}\n\nThis link expires in 1 hour.";
    $mailer->send();
}

function build_url(string $baseUrl, string $path, array $query = []): string
{
    $url = rtrim($baseUrl, '/') . '/' . ltrim($path, '/');

    if ($query !== []) {
        $url .= '?' . http_build_query($query);
    }

    return $url;
}
