<?php

namespace App\Form;

use App\Entity\Products;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('reference')
            ->add('category')
            ->add('purchase_date', DateTimeType::class)
            ->add('warranty_date', DateTimeType::class)
            ->add('price')
            ->add('receipt')
            ->add('maintenance')
            ->add('manual')
            ->add('reservation_date', DateTimeType::class)
            ->add('return_date', DateTimeType::class)
            ->add('purchase_place')
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
