<?php

namespace App\Service;

use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FormService
{
    /** @var ValidatorInterface  */
    private $validator;

    /** @var ConstraintViolationList */
    private $errors;

    /**
     * FormService constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param $entity
     * @return bool
     */
    public function isValid($entity): bool
    {
        $this->errors = $this->validator->validate($entity);

        return $this->errors->count() === 0;
    }

    /**
     * @param array $errors
     * @return array
     */
    public function getErrors($errors = []): array
    {
        foreach ($this->errors->getIterator() as $error) {
            $errors[$error->getPropertyPath()] = $error->getMessage();
        }

        return $errors;
    }
}
