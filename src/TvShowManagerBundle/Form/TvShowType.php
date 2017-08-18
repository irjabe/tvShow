<?php

namespace TvShowManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TvShowManagerBundle\Entity\TvShow;

class TvShowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('type', TextType::class, [
                'label' => 'Type',
            ])
            ->add('url', TextType::class, [
                'label' => 'Url',
            ])
            ->add('year', TextType::class, [
                'label' => 'Year',
            ])
            ->add('save', SubmitType::class, ['label' => 'Submit'])
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TvShow::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'tv_show_manager_bundle_tv_show';
    }

}
