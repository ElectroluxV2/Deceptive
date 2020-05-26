<?php

namespace App\Application\Actions\Deceptive\Image;

use App\Application\Actions\Deceptive\DeceptiveAction;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class TakeFBImageAction extends DeceptiveAction {

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
