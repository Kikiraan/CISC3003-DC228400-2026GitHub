<?php
declare(strict_types=1);

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function footer_text(): string
{
    return 'CISC3003 Web Programming: Wang Yufeng + DC228400 + 2026';
}

function project_url(string $path = ''): string
{
    $base = '/CISC3003-FinalExam-Paper02C';
    return $path === '' ? $base : $base . '/' . ltrim($path, '/');
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function flash(string $type, string $title, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'title' => $title,
        'message' => $message,
    ];
}

function get_flash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function store_form_state(string $key, array $data): void
{
    $_SESSION[$key] = $data;
}

function pull_form_state(string $key): array
{
    $data = $_SESSION[$key] ?? [];
    unset($_SESSION[$key]);

    return is_array($data) ? $data : [];
}

function old_value(array $old, string $key): string
{
    $value = $old[$key] ?? '';
    return is_string($value) ? $value : '';
}

function format_datetime(?string $value): string
{
    if (!$value) {
        return 'Not available';
    }

    $timestamp = strtotime($value);
    if ($timestamp === false) {
        return $value;
    }

    return date('Y-m-d H:i:s', $timestamp);
}

function mail_debug_log_path(): string
{
    return __DIR__ . '/mail_debug.log';
}

function log_mail_debug(string $context, string $message): void
{
    $line = sprintf("[%s] %s: %s%s", date('Y-m-d H:i:s'), $context, $message, PHP_EOL);
    file_put_contents(mail_debug_log_path(), $line, FILE_APPEND);
}
