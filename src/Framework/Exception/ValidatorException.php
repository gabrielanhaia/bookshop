<?php

namespace App\Framework\Exception;

use Throwable;

class ValidatorException extends \Symfony\Component\Validator\Exception\ValidatorException
{
    private array $violations;

    public function __construct(
        string     $message = '',
        array $violations = [],
        int        $code = 0,
        ?Throwable $previous = null
    ) {
        $this->violations = $violations;
        parent::__construct($message, $code, $previous);
    }

    public function getViolations(): array
    {
        return $this->violations;
    }
}
