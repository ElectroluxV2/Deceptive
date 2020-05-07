<?php
declare(strict_types=1);

namespace App\Application\Actions\GooglePhotos;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class GetPhotoBatchAction extends GooglePhotosAction {
    /**
     * {@inheritdoc}
     * @throws Exception
     */
    protected function action(): Response {
        $ids = json_decode(file_get_contents('php://input'));
        if (!is_array($ids)) {
            throw new Exception('Ids parameter must be array type.');
        }

        $photo = $this->photosManager->getPhotos($ids);
        return $this->respondWithData($photo);
    }
}
