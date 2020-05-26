<?php
declare(strict_types=1);

namespace App\Application\Actions\Facebook\Album;

use App\Application\Actions\Facebook\FacebookAction;
use Facebook\Exceptions\FacebookSDKException;
use Psr\Http\Message\ResponseInterface as Response;

class PushPhotoAction extends FacebookAction {
    /**
     * {@inheritdoc}
     * @throws FacebookSDKException
     */
    protected function action(): Response {

        $id = $this->args['id'];
        $newPhotoID = $this->pageManager->addPhotoToAlbum($id);
        $data = [
            'newPhotoID' => $newPhotoID
        ];
        return $this->respondWithData($data);
    }
}
