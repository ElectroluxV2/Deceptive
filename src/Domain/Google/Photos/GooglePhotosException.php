<?php
declare(strict_types=1);

namespace App\Domain\Google\Photos;

use Exception;
use Throwable;

class GooglePhotosException extends Exception {
    public function __construct($message = "", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);

        $obj = json_decode($message);
        $this->message = "PhotosLibraryClient error: ".$obj->message;
    }
}
