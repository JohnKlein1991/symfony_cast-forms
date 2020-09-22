<?php

namespace App\Form;

use App\Entity\Article;
use App\Form\DataTransformer\UserToStringTransformer;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
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
        /** @var Article|null $article */
        $article = $options['data'] ?? null;
        $isEdit = !is_null($article) && $article->getId();

        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'help' => 'Article title',
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Content',
                'help' => 'Article content',
                'required' => false,
                'rows' => 20
            ])
            ->add('author', ArticleAuthorType::class, [
                'disabled' => $isEdit
            ])
            ->add('location', ChoiceType::class, [
                'placeholder' => 'Choise a location',
                'choices' => [
                    'The Solar System' => 'solar_system',
                    'Near a star' => 'star',
                    'Interstellar Space' => 'interstellar_space',
                ],
                'required' => false
            ]);
        ;

        if ($options['include_published_at']) {
            $builder
                ->add('publishedAt', null, [
                    'widget' => 'single_text',
                ]);
        }

        $builder
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {
                    /** @var Article|null $data */
                    $data = $event->getData();
                    if (is_null($data)) {
                        return;
                    }

                    $this->setupSpecificLocationNameField(
                        $event->getForm(),
                        $data->getLocation()
                    );
                }
            );
        $builder->get('author')
            ->addModelTransformer($this->authorTransformer);
        $builder->get('location')
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    $form = $event->getForm();
                    $this->setupSpecificLocationNameField(
                        $form->getParent(),
                        $form->getData()
                    );
                }
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'include_published_at' => false,
        ]);
    }

    private function getLocationNameChoices(string $location)
    {
        $planets = [
            'Mercury',
            'Venus',
            'Earth',
            'Mars',
            'Jupiter',
            'Saturn',
            'Uranus',
            'Neptune',
        ];
        $stars = [
            'Polaris',
            'Sirius',
            'Alpha Centauari A',
            'Alpha Centauari B',
            'Betelgeuse',
            'Rigel',
            'Other'
        ];
        $locationNameChoices = [
            'solar_system' => array_combine($planets, $planets),
            'star' => array_combine($stars, $stars),
            'interstellar_space' => null,
        ];
        return $locationNameChoices[$location];
    }

    private function setupSpecificLocationNameField(FormInterface $form, ?string $location)
    {
        if (is_null($location)) {
            $form->remove('specificLocationName');

            return;
        }

        $choices = $this->getLocationNameChoices($location);

        if (is_null($choices)) {
            $form->remove('specificLocationName');

            return;
        }

        $form
            ->add('specificLocationName', ChoiceType::class, [
                'placeholder' => 'Where exactly',
                'choices' => $choices,
                'required' => false
            ]);
    }
}