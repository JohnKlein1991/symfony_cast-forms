<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ArticleAuthorType extends AbstractType
{
    public function getParent()
    {
        return EmailType::class;
    }
}