<?php

namespace App\Form;

use App\Entity\Bag;
use App\Entity\Type;
use App\Entity\user;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('brand')
            ->add('color')
            ->add('img', FileType::class, [
                'label'=> 'image',
                'mapped' => false,
                'required' => false,
            ])
            // ->add('user', EntityType::class, [
            //     'class' => user::class,
            //     'choice_label' => 'email',
            // ])
            // ->add('owner', EntityType::class, [
            //     'class' => user::class,
            //     'choice_label' => 'email',
            // ])
            ->add('Type', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'name',
            ])
            ->add('Submit', SubmitType::class,[
                'label' => 'Ajouter un sac'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bag::class,
        ]);
    }
}
