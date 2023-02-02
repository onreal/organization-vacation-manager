<?php

use DI\Bridge\Slim\Bridge;
use DI\ContainerBuilder;
use Slim\Psr7\Request;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\Info;
use OpenApi\Annotations\PathItem;

include '../../app/api/vendor/autoload.php';

try {
    // Initialize Dotenv .env
    $dotenv = Dotenv\Dotenv::createImmutable('../../app/api/');
    $dotenv->load();
    // Set dependencies
    $containerBuilder = new ContainerBuilder;
    $containerBuilder->addDefinitions('../../app/api/src/Infrastructure/dependencies.php');
    $container = $containerBuilder->build();
    // Attach dependencies on Slim
    $app = Bridge::create($container);
    // Register Slim routes
    require '../../app/api/src/Api/Routes/user.php';
    require '../../app/api/src/Api/Routes/application.php';
    require '../../app/api/src/Api/Routes/logAction.php';
    // require '../app/src/Core/Domain/Entities/User.php';
    // Register Swagger route
    /**
     * @Get(
     *     path="/openapi",
     *     tags={"documentation"},
     *     summary="OpenAPI JSON File that describes the API",
     *     @OA\Response(response="200", description="OpenAPI Description File"),
     * )
     */
    $app->get('/openapi', function ($request, $response) {
        $swagger = \OpenApi\Generator::scan(['../../app/api/src/Api/Routes']);
        $response->getBody()->write(json_encode($swagger));
        return $response->withHeader('Content-Type', 'application/json');
    });
    // Add slim middleware for all requests to json
    $applicationJsonMiddleware = function (Request $request, Slim\Routing\RouteRunner $handler) {
        $request = $request->withHeader('Content-Type', 'application/json');
        return $handler->handle($request);
    };
    $app->add($applicationJsonMiddleware);
    // Run Slim app
    $app->run();
} catch (Exception $e) {
    die('Something wrong happened during app initialization. Message: '. $e->getMessage());
}
