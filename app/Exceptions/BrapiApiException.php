<?php

namespace App\Exceptions;

use Exception;

class BrapiApiException extends Exception
{
    protected array $context = [];

    public function __construct(string $message, int $code, array $context)
    {
        parent::__construct($message, $code);
        $this->context = $context;
    }

    /**
     * Get the exception's context information.
     *
     * @return array
     */
    public function context()
    {
        return $this->context;
    }
}
