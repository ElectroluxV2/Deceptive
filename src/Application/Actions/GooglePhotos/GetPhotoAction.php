<?php
declare(strict_types=1);

namespace App\Application\Actions\GooglePhotos;

use App\Domain\Google\Photos\GooglePhotosException;
use Psr\Http\Message\ResponseInterface as Response;

class GetPhotoAction extends GooglePhotosAction {
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
