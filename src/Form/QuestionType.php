<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\Sujet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', textType::class,["label" =>"votre question", "attr" => [ "class" =>"pouf"]])
            ->add('description',textAreaType::class)
            ->add('sujet',EntityType::class,['multiple' =>true, 'expanded' =>false, 'class'=> Sujet::class ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
