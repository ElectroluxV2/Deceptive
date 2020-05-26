<?php
declare(strict_types=1);

namespace App\Application\Actions\GooglePhotos\Photo;

use App\Application\Actions\GooglePhotos\GooglePhotosAction;
use App\Domain\Google\Photos\GooglePhotosException;
use Psr\Http\Message\ResponseInterface as Response;

class ListUserPhotosAction extends GooglePhotosAction {
    /**
     * {@inheritdoc}
     * @throws GooglePhotosException
     */
    protected function action(): Response {
        $pageToken = isset($this->args['pageToken']) ? $this->args['pageToken'] : null;
        $albums = $this->photosManager->getContents($pageToken);
        return $this->respondWithData($albums);
    }
}
