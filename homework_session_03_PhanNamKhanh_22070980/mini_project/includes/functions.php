<?php
session_start();

define('USERS_FILE', __DIR__ . '/../data/users.json');

function getUsers()
{
    if (!file_exists(USERS_FILE)) {
        return [];
    }
    $json = file_get_contents(USERS_FILE);
    $users = json_decode($json, true);
    return is_array($users) ? $users : [];
}

function saveUsers($users)
{
    file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT));
}

function getUserByUsername($username)
{
    $users = getUsers();
    foreach ($users as $user) {
        if ($user['username'] === $username) {
            return $user;
        }
    }
    return null;
}

function registerUser($username, $password)
{
    if (empty(trim($username)) || empty($password)) {
        return ['success' => false, 'message' => 'Username and password cannot be empty.'];
    }
    if (getUserByUsername($username)) {
        return ['success' => false, 'message' => 'Username already exists.'];
    }

    $users = getUsers();
    $newUser = [
        'id' => uniqid(),
        'username' => trim($username),
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'avatar' => null
    ];
    $users[] = $newUser;
    saveUsers($users);

    return ['success' => true, 'message' => 'Registration successful! You can now log in.'];
}

function loginUser($username, $password)
{
    if (empty(trim($username)) || empty($password)) {
        return ['success' => false, 'message' => 'Username and password cannot be empty.'];
    }

    $user = getUserByUsername($username);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return ['success' => true, 'message' => 'Login successful!'];
    }

    return ['success' => false, 'message' => 'Invalid username or password.'];
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function getCurrentUser()
{
    if (!isLoggedIn())
        return null;
    $users = getUsers();
    foreach ($users as $user) {
        if ($user['id'] === $_SESSION['user_id']) {
            return $user;
        }
    }
    return null;
}

function updateAvatar($userId, $filePath)
{
    $users = getUsers();
    foreach ($users as &$user) {
        if ($user['id'] === $userId) {
            $user['avatar'] = $filePath;
            break;
        }
    }
    saveUsers($users);
}
?>