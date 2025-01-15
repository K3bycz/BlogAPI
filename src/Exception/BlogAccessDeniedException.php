<?php

namespace App\Exception;

class BlogAccessDeniedException extends \Exception
{
    public function __construct(string $message = "Access denied: Only administrators can create blogs", int $code = 403)
    {
        parent::__construct($message, $code);
    }
}