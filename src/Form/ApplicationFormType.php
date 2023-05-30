<?php

namespace App\Form;

use App\Entity\Application;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ApplicationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('discription')
//            ->add('time_of_creation')
//            ->add('lst_edit_time')
            ->add('username', HiddenType::class, [
                'data' => $_SERVER['REQUEST_URI'],
            ])
            ->add('filename',FileType::class)
            ->add('status', ChoiceType::class, [
//                'multiple' => true,
                'expanded' => true, // render check-boxes
                'choices' => [
                    'Ожидает обработки' => 'Ожидает обработки',
                    'Принят' => 'Принят',
                    'Требуется доп.информация' => 'Требуется доп.информация',
                ],
                'attr' => array('disabled' => 'disabled')
            ])
            ->add('comments')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Application::class,
        ]);
    }
}
