<?php

namespace App\Application\Actions\Deceptive\Post;

use App\Application\Actions\Deceptive\DeceptiveAction;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class CreateAction extends DeceptiveAction {

    /**
     * @return Response
     * @throws Exception
     */
    protected function action(): Response {

        $name = $this->args['name'];

        $body = json_decode(file_get_contents('php://input'));
        if (!is_object($body)) {
            throw new Exception('Body parameter must be object type.');
        }

        $createdPostID = $this->cms->newPost($name, $body);
        $data = [
            'newPostID' => $createdPostID
        ];
        return $this->respondWithData($data);
    }
}
