<?php

/*
 * InterCom Module for Zikula
 *
 * @copyright  InterCom Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @see https://github.com/zikula-modules/InterCom
 */

namespace Zikula\IntercomModule\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * UserPreferencesType.
 */
class UserPreferencesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('ic_note', ChoiceType::class, [
            'choices'  => ['0' => 'Off', '1' => 'On'],
            'multiple' => false,
            'expanded' => true,
            'required' => true,
        ])
        ->add('ic_ar', ChoiceType::class, [
            'choices'  => ['0' => 'Off', '1' => 'On'],
            'multiple' => false,
            'expanded' => true,
            'required' => true,
        ])
        ->add('ic_art', TextareaType::class, [
            'required' => false,
        ])
        ->add('save', SubmitType::class)
        ->add('cancel', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'zikula_intercom_module_user_preferences_form';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'isXmlHttpRequest' => false,
        ]);
    }
}
