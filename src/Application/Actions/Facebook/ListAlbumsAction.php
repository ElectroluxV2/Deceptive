<?php
declare(strict_types=1);

namespace App\Application\Actions\Facebook;

use App\Domain\Google\Photos\GooglePhotosException;
use Facebook\Exceptions\FacebookSDKException;
use Psr\Http\Message\ResponseInterface as Response;

class ListAlbumsAction extends FacebookAction {
    /**
     * {@inheritdoc}
     * @throws FacebookSDKException
     */
    protected function action(): Response {

        $albums = $this->pageManager->getAlbums();
        return $this->respondWithData($albums);
    }
}
