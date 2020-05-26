<?php
declare(strict_types=1);

namespace App\Application\Actions\Facebook\Album;

use App\Application\Actions\Facebook\FacebookAction;
use Facebook\Exceptions\FacebookSDKException;
use Psr\Http\Message\ResponseInterface as Response;

class ListAction extends FacebookAction {
    /**
     * {@inheritdoc}
     * @throws FacebookSDKException
     */
    protected function action(): Response {

        $albums = $this->pageManager->getAlbums();
        return $this->respondWithData($albums);
    }
}
