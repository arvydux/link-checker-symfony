<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ListUploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ...
            ->add('file', FileType::class, [
                'label'       => 'URL list (text file)',
                'mapped'      => false,
                'constraints' => [
                    new File([
                        'maxSize'          => '1024k',
                        'mimeTypes'        => [
                            'text/*',
                        ],
                        'mimeTypesMessage' => 'Please upload a text file with list of valid URLs',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}