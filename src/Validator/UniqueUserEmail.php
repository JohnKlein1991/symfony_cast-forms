<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 * @Annotation
 */
class UniqueUserEmail extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'The user with the "{{ value }}" email is already exists  ';
}
