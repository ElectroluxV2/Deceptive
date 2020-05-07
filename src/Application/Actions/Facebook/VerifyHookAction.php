<?php
declare(strict_types=1);

namespace App\Application\Actions\Facebook;

use App\Domain\Google\Photos\GooglePhotosException;
use Facebook\Exceptions\FacebookSDKException;
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
