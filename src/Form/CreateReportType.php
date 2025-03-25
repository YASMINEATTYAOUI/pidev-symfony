<?php

namespace App\Form;

use App\Entity\Citizen;
use App\Entity\Report;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('creationDate', null, [
                'widget' => 'single_text'
            ])
            ->add('lastModifiedDate', null, [
                'widget' => 'single_text'
            ])
            ->add('reportType')
            ->add('attachments')
            ->add('responseStatus')
            ->add('responseText')
            ->add('responseDate', null, [
                'widget' => 'single_text'
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
            'data_class' => Report::class,
        ]);
    }
}
