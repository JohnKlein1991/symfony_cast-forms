<?php


namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\UniqueUserEmail;

/**
 * Class UserRegistrationFormModel
 * @package App\Form\Model
 */
class UserRegistrationFormModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @UniqueUserEmail()
     */
    public $email;
    /**
     * @Assert\NotBlank(message="Urur! NOt blank!")
     * @Assert\Length(min="4", minMessage="Urur! Too short!")
     */
    public $password;
    /**
     * @Assert\IsTrue(message="Required Field!")
     */
    public $agree_to_terms;
}