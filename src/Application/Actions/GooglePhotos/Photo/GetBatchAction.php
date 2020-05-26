<?php
declare(strict_types=1);

namespace App\Application\Actions\GooglePhotos\Photo;

use App\Application\Actions\GooglePhotos\GooglePhotosAction;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class GetBatchAction extends GooglePhotosAction {
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
