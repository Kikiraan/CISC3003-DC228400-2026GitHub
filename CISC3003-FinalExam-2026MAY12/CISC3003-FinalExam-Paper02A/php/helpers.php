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

function old_value(array $old, string $key): string
{
    $value = $old[$key] ?? '';
    return is_string($value) ? $value : '';
}

function checked(array $old, string $key, string $expected): string
{
    return old_value($old, $key) === $expected ? 'checked' : '';
}

function checked_array(array $old, string $key, string $expected): string
{
    $values = $old[$key] ?? [];
    return is_array($values) && in_array($expected, $values, true) ? 'checked' : '';
}

function selected(array $old, string $key, string $expected): string
{
    return old_value($old, $key) === $expected ? 'selected' : '';
}

function scenario_a_programs(): array
{
    return [
        'BSc in Computer Science',
        'BSc in Data Science',
        'BBA in Digital Business',
        'Postgraduate Diploma in IT',
    ];
}

function scenario_a_study_modes(): array
{
    return ['On-campus', 'Online', 'Hybrid'];
}

function scenario_a_interests(): array
{
    return [
        'Academic Advising',
        'Career Coaching',
        'Internship Support',
        'Programming Workshop',
        'Scholarship Updates',
    ];
}
