<?php

namespace Luchki\YoutrackSDK\Exceptions;

use Throwable;

class InvalidValueException extends \Exception
{
        public function __construct($message = "", $code = 0, Throwable $previous = null) {
                parent::__construct($message, $code, $previous);
        }
}