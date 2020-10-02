<?php

namespace App\Form;

use App\Entity\Products;
use AppBundle\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormTypeInterface;


class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('reference')
            ->add('category', ChoiceType::class, [
                'choices' => [
                'Livre' => 'Livre',
                'Electroménager' => 'Electroménager',
                'Multimédia' => 'Multimédia',
                'Sport' => 'Sport',
                'Téléphonie' => 'Téléphonie',
                'Multimédia' => 'Multimédia',
                'Vidéo' => 'Vidéo',
                'Jeu' => 'Jeu'
                ],
            ])
            ->add('purchase_date', DateType::class, array(
                "widget" => 'single_text',
                "format" => 'yyyy-MM-dd',
                
            ))
            ->add('warranty_date', DateType::class, array(
                "widget" => 'single_text',
                "format" => 'yyyy-MM-dd',
                
            ))
            ->add('price')
            ->add('receipt', FileType::class, [
                'label' => 'Facture :  (PDF file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024M',
                        'mimeTypes' => [
                            'application/jpg',
                            'application/png',
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
            ->add('maintenance')
            ->add('manual', FileType::class, [
                'label' => 'manuel d\'utilisation : (PDF file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024M',
                        'mimeTypes' => [
                            'application/jpg',
                            'application/png',
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ]) 
            ->add('availability', ChoiceType::class, [
                'choices' => [
                    'Available' => true,
                    'Unavailable' => false
                ],
                'attr' => ['readonly' => true]
            ])
            ->add('purchase_place')
            //->add('id_user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
            'translation_domain' => 'forms'
        ]);
    }
}
