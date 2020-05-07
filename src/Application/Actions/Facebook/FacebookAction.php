<?php
declare(strict_types=1);

namespace App\Application\Actions\Facebook;

use App\Application\Actions\Action;
use App\Domain\Facebook\FacebookPageManager;
use App\Domain\Google\Photos\GooglePhotosManager;
use Facebook\Facebook;
use Psr\Log\LoggerInterface;

abstract class FacebookAction extends Action {

    /**
     * @var FacebookPageManager
     */
    protected $pageManager;

    /**
     * UserAction constructor.
     * @param LoggerInterface $logger
     * @param FacebookPageManager $pageManager
     */
    public function __construct(LoggerInterface $logger, FacebookPageManager $pageManager) {
        parent::__construct($logger);
        $this->pageManager = $pageManager;
    }
}
