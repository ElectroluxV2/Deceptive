<?php
declare(strict_types=1);

use App\Application\Middleware\AuthMiddleware;
use App\Application\Middleware\SessionMiddleware;
use Medoo\Medoo;
use Slim\App;

return function (App $app) {
    $app->add(new SessionMiddleware());
    $app->add(new AuthMiddleware($app->getContainer()->get(Medoo::class)));
};
