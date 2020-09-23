<?php

namespace App\Form;

use App\Entity\Products;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ProductReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            // ->add('reference')
            // ->add('category')
            // ->add('purchase_date')
            // ->add('warranty_date')
            // ->add('price')
            // ->add('receipt')
            // ->add('maintenance')
            // ->add('manual')
            ->add('reservation_date', DateType::class, [
                "widget" => 'single_text',
                "format" => 'yyyy-MM-dd',
                "data" => new \DateTime()]
            )
            // ->add('return_date')
            ->add('availability')
            // ->add('purchase_place')
            // ->add('id_user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
