<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class ViewUserAction extends UserAction {
    /**
     * {@inheritdoc}
     */
    protected function action(): Response {
        //$user = $this->request->getAttribute('user');
        //$this->logger->info("User data was viewed.", ['issuer' => $user->jsonSerialize()]);
        return $this->respondWithData(null);//$user->export());
    }
}
