<?php
declare(strict_types=1);

namespace App\Application\Actions\GooglePhotos;

use App\Domain\Google\Photos\GooglePhotosException;
use Psr\Http\Message\ResponseInterface as Response;

class GetPhotosInsideAlbumAction extends GooglePhotosAction {
    /**
     * {@inheritdoc}
     * @throws GooglePhotosException
     */
    protected function action(): Response {
        $id = $this->args['id'];
        $pageToken = isset($this->args['pageToken']) ? $this->args['pageToken'] : null;
        $albums = $this->photosManager->getAlbumContents($id, $pageToken);
        return $this->respondWithData($albums);
    }
}
