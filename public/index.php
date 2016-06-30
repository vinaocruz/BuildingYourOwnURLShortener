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

    try{
        $shortUrl = new \App\ShortUrl($this->db);
        $code = $shortUrl->urlToShortCode($args['url']);
        $response->getBody()->write("code:" . $code);
        return $response;
    } catch (\Exception $e) {

    }
});

//get long url
$app->get('/{code}', function (Request $request, Response $response) {
    $code = $request->getAttribute('code');

    try{
        $shortUrl = new \App\ShortUrl($this->db);
        $url = $shortUrl->shortCodeToUrl($code);
    } catch (\Exception $e) {
        echo $e->getTraceAsString();
        exit;
    }

    $response->getBody()->write("url: $url");

    return $response;
});

$app->run();