<?php
/**
 * Created by PhpStorm.
 * User: bizmate
 * Date: 29/10/15
 * Time: 19:47
 */
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Hit;


class HitType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('position', 'text')->add('save', 'button', array('label' => 'Hit!'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Hit',
        ));
    }

    public function getName()
    {
        return 'hit';
    }
}