<?php

namespace App\Application\Actions\Deceptive;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class ParseFBImageAction extends DeceptiveAction {

    /**
     * @return Response
     * @throws Exception
     */
    protected function action(): Response {

        $ID = $this->args['id'];
        $imageIDInDB = $this->cms->parseFBImage($ID);
        $data = [
            'imageID' => $imageIDInDB
        ];
        return $this->respondWithData($data);
    }
}
