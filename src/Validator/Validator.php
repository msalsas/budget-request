<?php

namespace App\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class Validator
{
    protected $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate($entity)
    {
        $errors = $this->validator->validate($entity);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            $errorsString = substr($errorsString, strpos($errorsString, '.') + 1);

            throw new \Exception($errorsString);
        }
    }
}