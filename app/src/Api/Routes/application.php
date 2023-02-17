<?php

use Up\Api\Controllers\ApplicationController;

if (!isset($app)) {
    return;
}
/**
 * @OA\Get(
 *     path="/api/users",
 *     @OA\Response(response="200", description="An example endpoint")
 * )
 */
$app->post('/api/applications', [ApplicationController::class, 'post']);
$app->get('/api/applications/{applicationId}', [ApplicationController::class, 'get']);
$app->get('/api/applications/user/{userId}', [ApplicationController::class, 'getByUserId']);
$app->get('/api/applications', [ApplicationController::class, 'getAll']);
$app->put('/api/applications/{applicationId}', [ApplicationController::class, 'put']);
$app->patch('/api/applications/{applicationId}', [ApplicationController::class, 'patch']);
$app->delete('/api/applications/{applicationId}', [ApplicationController::class, 'delete']);
