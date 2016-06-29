<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App;

$app->get('/url/{code}', function (Request $request, Response $response) {
    $code = $request->getAttribute('code');
    $response->getBody()->write("code: $code");

    return $response;
});

$app->run();