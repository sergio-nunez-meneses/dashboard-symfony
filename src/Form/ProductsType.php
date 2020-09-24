<?php

namespace App\Form;

use App\Entity\Products;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ProductsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('reference')
            ->add('category')
            ->add('purchase_date', DateType::class, array(
                "widget" => 'single_text',
                "format" => 'yyyy-MM-dd',
                "data" => new \DateTime()
            ))
            ->add('warranty_date', DateType::class, array(
                "widget" => 'single_text',
                "format" => 'yyyy-MM-dd',
                "data" => new \DateTime()
            ))
            ->add('price')
            ->add('receipt', FileType::class, [
                'label' => "Ticket d'achat",

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
            ->add('maintenance')
            ->add('manual', FileType::class, [
                'label' => "Manuel entretien",

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
            ->add('reservation_date', DateType::class, array(
                "widget" => 'single_text',
                "format" => 'yyyy-MM-dd',
                "data" => new \DateTime()
            ))
            ->add('return_date', DateType::class, array(
                "widget" => 'single_text',
                "format" => 'yyyy-MM-dd',
                "data" => new \DateTime()
            ))
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
