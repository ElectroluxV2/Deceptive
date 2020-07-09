<?php
declare(strict_types=1);

namespace App\Application\Actions\Deceptive\Regatta;

use App\Application\Actions\Deceptive\DeceptiveAction;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteRegattaAction extends DeceptiveAction {

    /**
     * @return Response
     * @throws Exception
     */
    protected function action(): Response {

        $id = (int) $this->args['id'];
        return $this->respondWithData([
            'deleted' => $this->cms->getRegattaMgr()->delete($id)
        ]);
    }
}
