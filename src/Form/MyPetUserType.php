<?php

namespace App\Form;

use App\Entity\MyPetUser;
use App\Help\Status;
use SplEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MyPetUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Owner' => Status::OWNER,
                    'Petsitter' => Status::PETSITTER]
                ])
            ->add('lastName')
            ->add('firstName')
            ->add('email')
            ->add('age')
            ->add('description')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MyPetUser::class,
        ]);
    }
}
