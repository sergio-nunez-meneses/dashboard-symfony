<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use App\Entity\Products;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
// use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ProductReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['readonly' => true]
            ])
            // ->add('reference')
            // ->add('category')
            // ->add('purchase_date')
            // ->add('warranty_date')
            // ->add('price')
            // ->add('receipt')
            // ->add('maintenance')
            // ->add('manual')
            ->add('reservation_date', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'data' => new \DateTime('now'),
                // 'attr' => ['readonly' => true]
                ])
            // ->add('return_date', DateType::class, [
            //     'widget' => 'single_text',
            //     'format' => 'yyyy-MM-dd',
            //     'data' => new \DateTime('now + 4 weeks'),
            //     'attr' => ['readonly' => true]
            //     ])
            ->add('availability', ChoiceType::class, [
                'choices' => [
                    'Unavailable' => false,
                ],
                'attr' => ['readonly' => true]
            ])
            // ->add('purchase_place')
            // ->add('id_user', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
