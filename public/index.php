<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . '/../app/Views');
$twig = new Environment($loader);

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute
    ('GET', '/characters',
        [RickAndMorty\Controllers\Controllers::class, 'getCharactersByPage']);
    $r->addRoute
    ('GET', '/episodes',
        [RickAndMorty\Controllers\Controllers::class, 'getEpisodesByPage']);
    $r->addRoute
    ('GET', '/locations',
        [RickAndMorty\Controllers\Controllers::class, 'getLocationsByPage']);
    $r->addRoute
    ('GET', "/character",
        [RickAndMorty\Controllers\Controllers::class, 'searchCharacter']);
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        [$controllerName, $methodName] = $handler;

        $controller = new $controllerName;

        /** @var \RickAndMorty\View $response */

        $response = $controller->{$methodName}();

        echo $twig->render($response->getTemplate() . '.html.twig', $response->getCollection());

        break;
}
