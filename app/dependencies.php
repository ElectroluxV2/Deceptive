<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Facebook\Facebook;
use Google\Auth\Credentials\UserRefreshCredentials;
use Google\Photos\Library\V1\PhotosLibraryClient;
use Medoo\Medoo;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Mpdf\Mpdf;
use phpseclib\Crypt\RSA;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get('settings');

            $loggerSettings = $settings['logger'];
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
    ], [
        Medoo::class => function (ContainerInterface $c) {
            $settings = $c->get('settings');

            $databaseSettings = $settings['database'];

            return new Medoo([
                'database_type' => 'mysql',
                'database_name' => $databaseSettings['name'],
                'server' => $databaseSettings['host'],
                'username' => $databaseSettings['user'],
                'password' => $databaseSettings['pass'],
                'logging' => $databaseSettings['logs'],
                'charset' => 'UTF-8',
            ]);
        },
    ], [
        PhotosLibraryClient::class => function (ContainerInterface $c) {
            $settings = $c->get('settings');
            $googleApi = $settings['googleApi'];
            $headers = apache_request_headers();
            $authCredentials = new UserRefreshCredentials($googleApi['scopes'],[
                'client_id' => $googleApi['clientId'],
                'client_secret' => $googleApi['clientSecret'],
                'refresh_token' => isset($headers['Authorization']) ? $headers['Authorization'] : ''
            ]);
            return new PhotosLibraryClient(['credentials' => $authCredentials]);
        }
    ], [
        Facebook::class => function (ContainerInterface $c) {
            $settings = $c->get('settings');
            $facebookApi = $settings['facebookApi'];
            return new Facebook([
                'app_id' => $facebookApi['appId'],
                'app_secret' => $facebookApi['appSecret'],
                'default_graph_version' => 'v6.0',
                'default_access_token' => $facebookApi['accessToken']
            ]);
        }
    ], [
        Mpdf::class => function (ContainerInterface $c) {
            $settings = $c->get('settings')['mpdf'];
            return new Mpdf([
                'tempDir' => $settings['tmpDir'],
                'fontDir' => $settings['fontDir'],
                'fontdata' => $settings['fonts'],
                'default_font' => $settings['defaultFont']
            ]);
        }
    ]);
};
