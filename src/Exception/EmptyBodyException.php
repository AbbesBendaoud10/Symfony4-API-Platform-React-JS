<?php

namespace App\Exception;
use Throwable;

class EmptyBodyException extends \Exception{
    
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct('The Body is Empty', $code, $previous);
    }

}