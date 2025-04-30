<?php

namespace App\Service\Validation;


use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DtoValidator
{
    public function __construct(
        private readonly ValidatorInterface $validator
    )
    {
    }

    public function validate(object $dto): void
    {
        $errors = $this->validator->validate($dto);

        if(count($errors) > 0)
        {
            throw new UnprocessableEntityHttpException((string) $errors);
        }
    }
}