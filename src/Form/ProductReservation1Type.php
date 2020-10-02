<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use App\Entity\Products;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
//use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ProductReservation1Type extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['readonly' => true]
            ])
            // ->add('reservation_date', DateType::class, [
            //     'widget' => 'single_text',
            //     'label' => 'Date de réservation',
            //     'format' => 'yyyy-MM-dd',
            //     // 'data' => "new \DateTime()",
            //     ])
                
            ->add('return_date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de réservation',
                'format' => 'yyyy-MM-dd',
                 //'data' => new \DateTime('now + 4 weeks'),
                //  'attr' => ['readonly' => true]
                ])
            
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
