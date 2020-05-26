<?php
declare(strict_types=1);

namespace App\Application\Actions\Facebook\WebHooks;

use App\Application\Actions\Facebook\FacebookAction;
use Psr\Http\Message\ResponseInterface as Response;

class VerifyHookAction extends FacebookAction {

    /**
     * @return Response
     */
    protected function action(): Response {

        $this->logger->info('A');

        return $this->respondWithData([]);
    }
}
