<?php

namespace App\Validator;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueUserEmailValidator extends ConstraintValidator
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * UniqueUserEmailValidator constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint UniqueUserEmail */
        if (!$constraint instanceof UniqueUserEmail) {
            throw new UnexpectedTypeException($constraint, UniqueUserEmail::class);
        }

        $user = $this->userRepository
            ->findOneBy([
                'email' => $value
            ]);

        if (!is_null($user)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
