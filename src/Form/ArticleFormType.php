<?php

namespace App\Form;

use App\Entity\Article;
use App\Form\DataTransformer\UserToStringTransformer;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ArticleFormType
 * @package App\Form
 */
class ArticleFormType extends AbstractType
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var UserToStringTransformer
     */
    private $authorTransformer;

    /**
     * ArticleFormType constructor.
     * @param UserRepository $userRepository
     * @param UserToStringTransformer $authorTransformer
     */
    public function __construct(UserRepository $userRepository, UserToStringTransformer $authorTransformer)
    {
        $this->userRepository = $userRepository;
        $this->authorTransformer = $authorTransformer;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $authors = $this->userRepository->getAllByEmailAlphabetical();

        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'help' => 'Article title',
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Content',
                'help' => 'Article content',
                'required' => false
            ])
            ->add('author', ArticleAuthorType::class)
            ->add('publishedAt', null, [
                'widget' => 'single_text'
            ]);

        $builder->get('author')
            ->addModelTransformer($this->authorTransformer);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class
        ]);
    }
}