<?php


namespace App\Application\Actions\Facebook\Album;


use App\Application\Actions\Facebook\FacebookAction;
use Facebook\Exceptions\FacebookSDKException;
use Psr\Http\Message\ResponseInterface as Response;

class CreateAction extends FacebookAction {
    /**
     * {@inheritdoc}
     * @throws FacebookSDKException
     */
    protected function action(): Response {

        $name = $this->args['name'];
        $createdAlbumID = $this->pageManager->createAlbum($name);
        $data = [
            'newAlbumID' => $createdAlbumID
        ];
        return $this->respondWithData($data);
    }
}
