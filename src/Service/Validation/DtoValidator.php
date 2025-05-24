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

    public function validate(object $dto, bool $throwException = true): array
    {
        $errors = $this->validator->validate($dto);

        if(count($errors) > 0)
        {
            if($throwException){
                throw new UnprocessableEntityHttpException($this->formatErrors($errors));
            }

            return iterator_to_array($errors);
        }

        return [];
    }

    private function formatErrors(iterable $errors): string
    {
        $messages = [];

        foreach($errors as $error){
            $messages[] = sprintf('%s: %s', $error->getPropertyPath(), $error->getMessage());
        }

        return implode("\n", $messages);
    }
}