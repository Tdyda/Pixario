<?php

namespace App\Api\Http\Validation;


use App\Api\Http\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class RequestValidator
{
    public function __construct(
        private ValidatorInterface $validator
    ) {
    }

    public function validate(object $dto, bool $throwException = true): array
    {
        $violations = $this->validator->validate($dto);

        if (count($violations) === 0) {
            return [];
        }

        $errors = $this->formatErrors($violations);

        if ($throwException) {
            throw new ValidationException($errors);
        }

        return $errors;
    }

    private function formatErrors(iterable $violations): array
    {
        $errors = [];

        foreach ($violations as $violation) {
            $field = $violation->getPropertyPath();
            $errors[$field][] = $violation->getMessage();
        }

        return $errors;
    }
}