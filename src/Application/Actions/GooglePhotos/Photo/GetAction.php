<?php
declare(strict_types=1);

namespace App\Application\Actions\GooglePhotos\Photo;

use App\Application\Actions\GooglePhotos\GooglePhotosAction;
use App\Domain\Google\Photos\GooglePhotosException;
use Psr\Http\Message\ResponseInterface as Response;

class GetAction extends GooglePhotosAction {
    /**
     * {@inheritdoc}
     * @throws GooglePhotosException
     */
    protected function action(): Response {
        $id = $this->args['id'];
        $photo = $this->photosManager->getPhoto($id);
        return $this->respondWithData($photo);
    }
}
