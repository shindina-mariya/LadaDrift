<?php
/**
 * Хелпер авторизации пользователей (клиентов)
 * Сессии: user_id, user_email, user_name
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isUserLoggedIn(): bool
{
    return !empty($_SESSION['user_id']);
}

function getCurrentUserId(): ?int
{
    return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
}

function setUserSession(int $userId, string $email, string $name): void
{
    $_SESSION['user_id'] = $userId;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_name'] = $name;
}

function clearUserSession(): void
{
    unset($_SESSION['user_id'], $_SESSION['user_email'], $_SESSION['user_name']);
}

function getCurrentUserData(): ?array
{
    if (!isUserLoggedIn()) {
        return null;
    }

    return [
        'id' => $_SESSION['user_id'],
        'email' => $_SESSION['user_email'] ?? '',
        'name' => $_SESSION['user_name'] ?? '',
    ];
}
