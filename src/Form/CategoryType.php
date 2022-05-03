<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent  $event) {
                $category = $event->getData();
                $form = $event->getForm();


                //check if the product object is "new" 
                //if no data  is passed to the form, the data is "null".
                //this should be considered a new "Product"
                if (!$category || null === $category->getId()) {
                    $form->add('save', SubmitType::class, ['label' => 'New Category']);
                }
            })
            ->add('name', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Edit Category']);
    }
    public function configureureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductType::class
        ]);
    }
}
