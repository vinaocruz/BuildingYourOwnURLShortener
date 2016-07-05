<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/../vendor/autoload.php';

$settings = require __DIR__ . '/../src/config.php';
$app = new \Slim\App(['settings' => $settings]);

require __DIR__ . '/../src/dependencies.php';

//generate url shortener
$app->post('/url', function (Request $request, Response $response) {
    $args = $request->getParsedBody();

    try {
        $shortUrl = new \App\ShortUrl($this->db);
        $code = $shortUrl->urlToShortCode($args['url']);

        return $response->withJson(['code' => $code]);
    } catch (\Exception $e) {
        return $response->withJson(['error' => $e->getMessage()], 500);
    }
});

//get long url
$app->get('/{code}', function (Request $request, Response $response) {
    $code = $request->getAttribute('code');

    try {
        $shortUrl = new \App\ShortUrl($this->db);
        $url = $shortUrl->shortCodeToUrl($code);

        return $response->withHeader('Location', $url);
    } catch (\Exception $e) {
        return $response->withJson(['error' => $e->getMessage()], 500);
    }
});

$app->run();
