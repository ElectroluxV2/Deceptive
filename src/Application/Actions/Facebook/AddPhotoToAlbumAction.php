<?php
declare(strict_types=1);

namespace App\Application\Actions\Facebook;

use App\Domain\Google\Photos\GooglePhotosException;
use Facebook\Exceptions\FacebookSDKException;
use Psr\Http\Message\ResponseInterface as Response;

class AddPhotoToAlbumAction extends FacebookAction {
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
