<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\User;
use Psr\Http\Message\ResponseInterface as Response;

class ListUsersAction extends UserAction {
    /**
     * {@inheritdoc}
     */
    protected function action(): Response {
        /** @var User $users */
        $users = $this->database->select('roles', ['id', 'email', 'level']);
        $user = $this->request->getAttribute('user');
        $this->logger->info("Users list was viewed.", ['issuer' => $user->jsonSerialize()]);
        return $this->respondWithData($users);
    }
}
