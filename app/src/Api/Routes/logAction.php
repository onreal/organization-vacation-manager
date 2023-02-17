<?php

use Up\Api\Controllers\LogActionController;

if (!isset($app)) {
    return;
}

$app->get('/api/logAction/{userId}', [LogActionController::class, 'get']);
