<?php

header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Headers:X-Request-With');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

use DI\Bridge\Slim\Bridge;
use DI\ContainerBuilder;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Request;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\Info;
use OpenApi\Annotations\PathItem;
use Slim\Routing\RouteContext;

include '../../app/vendor/autoload.php';

try {
    // Initialize Dotenv .env
    $dotenv = Dotenv\Dotenv::createImmutable('../../app/');
    $dotenv->load();
    // Set dependencies
    $containerBuilder = new ContainerBuilder;
    $containerBuilder->addDefinitions('../../app/src/Infrastructure/dependencies.php');
    $container = $containerBuilder->build();
    // Attach dependencies on Slim
    $app = Bridge::create($container);
    require '../../app/src/Api/Routes/user.php';
    require '../../app/src/Api/Routes/application.php';
    require '../../app/src/Api/Routes/logAction.php';
    // Add slim CORS
    $app->add(function ($request, $handler) {
        // Set content type
        $request = $request->withHeader('Content-Type', 'application/json');
        return $handler->handle($request);
    });
    // Run Slim app
    $app->run();
} catch (Exception|Throwable $e) {
    die('Something wrong happened during app initialization. Message: '. $e->getMessage());
}
