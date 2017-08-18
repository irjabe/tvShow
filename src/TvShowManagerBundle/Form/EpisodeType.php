<?php

namespace TvShowManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TvShowManagerBundle\Entity\Episode;

class EpisodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('season', IntegerType::class, [
                'label' => 'Season',
                'attr' => ['min' => 1]
            ])
            ->add('number', IntegerType::class, [
                'label' => 'Number',
                'attr' => ['min' => 1]
            ])
            ->add('note', IntegerType::class, [
                'label' => 'Note',
                'attr' => ['min' => 1, 'max' => 5]
            ])
            ->add('save', SubmitType::class, ['label' => 'Submit'])
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Episode::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'tv_show_manager_bundle_episode_type';
    }

}
