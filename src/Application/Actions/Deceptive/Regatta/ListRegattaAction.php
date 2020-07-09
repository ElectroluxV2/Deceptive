<?php

namespace App\Application\Actions\Deceptive\Regatta;

use App\Application\Actions\Deceptive\DeceptiveAction;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class ListRegattaAction extends DeceptiveAction {

    /**
     * @return Response
     * @throws Exception
     */
    protected function action(): Response {

        $limit = $this->args['limit'];
        $pageToken = 0;
        if (isset($this->args['pageToken'])) {
            $pageToken = $this->args['pageToken'];
        }

        return $this->respondWithData([
            'regatta' => $this->cms->getRegattaMgr()->list($limit, $pageToken)
        ]);
    }
}
