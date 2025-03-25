<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Citizen;
use App\Entity\Events;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('date', null, [
                'widget' => 'single_text'
            ])
            ->add('creationDate', null, [
                'widget' => 'single_text'
            ])
            ->add('lastModifiedDate', null, [
                'widget' => 'single_text'
            ])
            ->add('imagePath')
            ->add('location', EntityType::class, [
                'class' => Address::class,
'choice_label' => 'id',
            ])
            ->add('citizen', EntityType::class, [
                'class' => Citizen::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Events::class,
        ]);
    }
}
