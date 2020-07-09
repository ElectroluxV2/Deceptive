<?php

namespace App\Application\Actions\Deceptive\Post;

use App\Application\Actions\Deceptive\DeceptiveAction;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class ListPostsAction extends DeceptiveAction {

    /**
     * @return Response
     * @throws Exception
     */
    protected function action(): Response {

        $limit = $this->args['limit'];
        $after = 0;
        if (isset($this->args['after'])) {
            $after = $this->args['after'];
        }

        return $this->respondWithData($this->cms->getPosts($limit, $after));
    }
}
