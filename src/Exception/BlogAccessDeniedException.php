<?php

namespace App\Exception;

class BlogAccessDeniedException extends \Exception
{
    public function __construct(string $message = "Brak dostępu: Tylko administratorzy mogą tworzyć posty!", int $code = 403)
    {
        parent::__construct($message, $code);
    }
}