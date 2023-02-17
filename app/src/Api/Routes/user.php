<?php

use Up\Api\Controllers\UserController;

if (!isset($app)) {
    return;
}

$app->post('/api/users', [UserController::class, 'post']);
$app->post('/api/users/login', [UserController::class, 'login']);
$app->get('/api/users/{userId}', [UserController::class, 'get']);
$app->get('/api/users', [UserController::class, 'getAll']);
$app->put('/api/users/{userId}', [UserController::class, 'put']);
$app->delete('/api/users/{userId}', [UserController::class, 'delete']);
