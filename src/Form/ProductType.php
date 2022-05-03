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

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent  $event) {
                $product = $event->getData();
                $form = $event->getForm();


                //check if the product object is "new" 
                //if no data  is passed to the form, the data is "null".
                //this should be considered a new "Product"
                if (!$product || null === $product->getId()) {
                    $form->add('save', SubmitType::class, ['label' => 'New Product']);
                }
            })
            ->add('name', TextType::class)
            ->add('prix', IntegerType::class)
            ->add('description', TextType::class)
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => "name"
            ])
            ->add('save', SubmitType::class, ['label' => 'Edit Product']);
    }
    public function configureureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductType::class
        ]);
    }
}
