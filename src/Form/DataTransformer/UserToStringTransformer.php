<?php

namespace App\Form\DataTransformer;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class UserToStringTransformer implements DataTransformerInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UserToStringTransformer constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param User $value
     * @return mixed|string
     */
    public function transform($value)
    {
        if (null === $value) {
            return '';
        }

        if (!($value instanceof User)) {
            throw new \LogicException('WTF?!?!?!');
        }

        return $value->getEmail();
    }

    public function reverseTransform($value)
    {
        if (empty($value)) {
            return;
        }

        $user = $this->userRepository
            ->findOneBy([
                'email' => $value
            ]);
        if (is_null($user)) {
            throw new TransformationFailedException('Epic fail');
        }

        return $user;
    }
}