<?php
declare(strict_types=1);

namespace App\Application\Actions\GooglePhotos;

use App\Application\Actions\Action;
use App\Domain\Google\Photos\GooglePhotosManager;
use Psr\Log\LoggerInterface;

abstract class GooglePhotosAction extends Action {

    /**
     * @var GooglePhotosManager
     */
    protected $photosManager;

    /**
     * UserAction constructor.
     * @param LoggerInterface $logger
     * @param GooglePhotosManager $photosManager
     */
    public function __construct(LoggerInterface $logger, GooglePhotosManager $photosManager) {
        parent::__construct($logger);
        $this->photosManager = $photosManager;
    }
}
