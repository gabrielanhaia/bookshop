<?php

declare(strict_types=1);

namespace App\Framework\Adapter\Input\Http\Controller;

use OpenApi\Attributes as OA;
use App\Framework\Exception\ValidatorException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Info(version: '0.1', title: 'Dancing Studios API')]
class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    public function __construct(
        protected readonly ValidatorInterface $validator
    ) {
    }

    protected function validate(object $requestData): void
    {
        $violations = $this->validator->validate($requestData);
        if (count($violations) > 0) {
            throw new ValidatorException('Validation error', $this->formatViolations($violations));
        }
    }

    private function formatViolations(ConstraintViolationListInterface $violations): array
    {
        $messages = [];
        foreach ($violations as $violation) {
            $messages[] = sprintf('`%s`: %s', $violation->getPropertyPath(), $violation->getMessage());
        }

        return $messages;
    }
}
