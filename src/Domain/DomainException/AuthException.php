<?php
declare(strict_types=1);

namespace App\Domain\DomainException;

use Throwable;

class AuthException extends DomainException {
    public function __construct($message = "", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);

        $this->message = "Authentication failure: ".$message;
        $this->code = 401;
    }
}
