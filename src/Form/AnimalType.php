<?php
Namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AnimalType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder,array $option){
        //parent::buildForm($builder,$option);
        $builder->setMethod('POST')
                        ->add('tipo', TextType::class,[
                            'label'=>'tipo de animal'
                        ])
                        ->add('color', TextType::class)
                        ->add('raza', TextType::class)
                        ->add('submit', SubmitType::class,[
                                'label'=>'Crear Animal',
                                'attr'=>['class'=>'btn btn success']
                                ]);
                    
    }
}
