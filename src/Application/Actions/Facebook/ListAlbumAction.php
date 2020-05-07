<?php
declare(strict_types=1);

namespace App\Application\Actions\Facebook;

use App\Domain\Google\Photos\GooglePhotosException;
use Facebook\Exceptions\FacebookSDKException;
use Psr\Http\Message\ResponseInterface as Response;

class ListAlbumAction extends FacebookAction {
    /**
     * {@inheritdoc}
     * @throws FacebookSDKException
     */
    protected function action(): Response {

        $id = $this->args['id'];
        $albums = $this->pageManager->getAlbumContents($id);
        return $this->respondWithData($albums);
    }
}
