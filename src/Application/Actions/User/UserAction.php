<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use Medoo\Medoo;
use Psr\Log\LoggerInterface;

abstract class UserAction extends Action {
    /**
     * @var Medoo
     */
    protected $database;

    /**
     * UserAction constructor.
     * @param LoggerInterface $logger
     * @param Medoo $database
     */
    public function __construct(LoggerInterface $logger, Medoo $database) {
        parent::__construct($logger);
        $this->database = $database;
    }
}
