<?php
declare(strict_types=1);

namespace App\Application\Middleware;

use App\Application\Actions\ActionPayload;
use App\Domain\DomainException\AuthException;
use App\Domain\User\User;
use Exception;
use Medoo\Medoo;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use stdClass;

class AuthMiddleware implements Middleware {

    /**
     * @var Medoo
     */
    private $database;

    public function __construct(Medoo $database) {
        $this->database = $database;
    }

    /**
     * {@inheritdoc}
     * @throws AuthException
     */
    public function process(Request $request, RequestHandler $handler): ResponseInterface {

        $uri = $request->getUri();
        if ($uri->getScheme()!=="https") {
          new AuthException("unsecured connection.");
        }

        $needAuth = true;

        $unProtectedPaths = [
            "Users/current",
            "Deceptive/posts/list",
            "Deceptive/declaration/generate",
            "Deceptive/declaration/show",
            "Deceptive/file/download",
            "Facebook/webhooks/verify"
        ];

        foreach ($unProtectedPaths as $path) {
            if (strpos($uri->getPath(), $path)) {
                $needAuth = false;
                break;
            }
        }

        if (!$needAuth) {
            // Validated
            return $handler->handle($request);
        }

        $session = $request->getAttribute('session', null);
        if (($session !== null) && (isset($session['user']))) {

            $request = $request->withAttribute('user', $session['user']);

            // Validated
            return $handler->handle($request);
        }

        // Auth time
        $token =  $request->getHeader('Authorization');
        if ((empty($token)) || (count($token) === 0)) {
            throw new AuthException("You didn't provide auth token! This path needs additional authorization.");
        }

        // Check token and permission
        $apiUrl = "https://www.googleapis.com/oauth2/v1/userinfo?access_token=".urlencode($token[0]);

        $json = null;
        if (!$json = @file_get_contents($apiUrl,false, stream_context_create(['http' =>['ignore_errors' => true]]))) {
            throw new AuthException("Token expired.");
        }

        /** @var stdClass $gUser */
        $gUser = json_decode($json);

        if (!$gUser) {
            throw new AuthException("Invalid token.");
        }

        if (isset($gUser->error)) {
            throw new AuthException($gUser->error->message);
        }

        if (!$gUser->verified_email) {
            throw new AuthException("Unverified account.");
        }

        /** @var array $role */
        $role = $this->database->get('roles', [
            'id',
            'level'
        ], [
            'email' => $gUser->email
        ]);

        if (empty($role)) {
            throw new AuthException("Not registered.");
        }

        if ($role['level'] === 0) {
            throw new AuthException("Unauthorized.");
        }

        // Add data for routes
        $user = new User($gUser, $role);
        $request = $request->withAttribute('user', $user);

        $_SESSION['user'] = $user;

        // Validated
        return $handler->handle($request);
    }
}
