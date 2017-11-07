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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PreferencesType.
 */
class PreferencesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('active', ChoiceType::class, [
            'choices'  => ['0' => 'Off', '1' => 'On'],
            'multiple' => false,
            'expanded' => true,
            'required' => true,
            ])
        ->add('maintain', TextareaType::class, [
            'required' => false,
            ])
        ->add('allowhtml', ChoiceType::class, [
            'choices'  => ['0' => 'Off', '1' => 'On'],
            'multiple' => false,
            'expanded' => true,
            'required' => true,
            ])
        ->add('allowsmilies', ChoiceType::class, [
            'choices'  => ['0' => 'Off', '1' => 'On'],
            'multiple' => false,
            'expanded' => true,
            'required' => true,
            ])
        ->add('disable_ajax', ChoiceType::class, [
            'choices'  => ['0' => 'Off', '1' => 'On'],
            'multiple' => false,
            'expanded' => true,
            'required' => true,
            ])
        // Limitations
        ->add('limitarchive', IntegerType::class, [
            'constraints' => [
                new Assert\GreaterThan(['value' => 0]),
            ],
            ])
        ->add('limitoutbox', IntegerType::class, [
            'constraints' => [
                new Assert\GreaterThan(['value' => 0]),
            ],
            ])
        ->add('limitinbox', IntegerType::class, [
            'constraints' => [
                new Assert\GreaterThan(['value' => 0]),
            ],
            ])
        ->add('perpage', IntegerType::class, [
            'constraints' => [
                new Assert\GreaterThan(['value' => 0]),
            ],
            ])
        // protection
        ->add('protection_on', ChoiceType::class, [
            'choices'  => ['0' => 'Off', '1' => 'On'],
            'multiple' => false,
            'expanded' => true,
            'required' => true,
            ])
        ->add('protection_time', TextType::class, [
            'required' => false,
            ])
        ->add('protection_amount', TextType::class, [
            'required' => false,
            ])
        ->add('protection_mail', ChoiceType::class, [
            'choices'  => ['0' => 'Off', '1' => 'On'],
            'multiple' => false,
            'expanded' => true,
            'required' => true,
            ])
        // user prompt
        ->add('userprompt', TextType::class, [
            'required' => false,
            ])
        ->add('userprompt_display', ChoiceType::class, [
            'choices'  => ['0' => 'Off', '1' => 'On'],
            'multiple' => false,
            'expanded' => true,
            'required' => true,
            ])
        // Welcome
        ->add('welcomemessage_send', ChoiceType::class, [
            'choices'  => ['0' => 'Off', '1' => 'On'],
            'multiple' => false,
            'expanded' => true,
            'required' => true,
            ])
        ->add('welcomemessagesender', TextType::class, [
            'required' => false,
            ])
        ->add('welcomemessagesubject', TextType::class, [
            'required' => false,
            ])
        ->add('welcomemessage', TextType::class, [
            'required' => false,
            ])
        ->add('intlwelcomemessage', TextType::class, [
            'required' => false,
            ])
        ->add('savewelcomemessage', ChoiceType::class, [
            'choices'  => ['0' => 'Off', '1' => 'On'],
            'multiple' => false,
            'expanded' => true,
            'required' => true,
            ])
        // Email
        ->add('allow_emailnotification', ChoiceType::class, [
            'choices'  => ['0' => 'Off', '1' => 'On'],
            'multiple' => false,
            'expanded' => true,
            'required' => true,
            ])
        ->add('force_emailnotification', ChoiceType::class, [
            'choices'  => ['0' => 'Off', '1' => 'On'],
            'multiple' => false,
            'expanded' => true,
            'required' => true,
            ])
        ->add('mailsender', TextType::class, [
            'required' => false,
            ])
        ->add('mailsubject', TextType::class, [
            'required' => false,
            ])
        ->add('fromname', TextType::class, [
            'required' => false,
            ])
        ->add('from_email', TextType::class, [
            'required' => false,
            ])
        // Autoreply
        ->add('allow_autoreply', ChoiceType::class, [
            'choices'  => ['0' => 'Off', '1' => 'On'],
            'multiple' => false,
            'expanded' => true,
            'required' => true,
            ])
        // Layout
        ->add('layout', ChoiceType::class, [
            'choices'  => ['classic' => 'Classic', 'conversation' => 'Conversation'],
            'multiple' => false,
            'expanded' => false,
            'required' => true,
            ])
        // Labels
        ->add('labels_enabled', ChoiceType::class, [
            'choices'  => ['0' => 'Off', '1' => 'On'],
            'multiple' => false,
            'expanded' => true,
            'required' => true,
            ])
        ->add('user_labels_enabled', ChoiceType::class, [
            'choices'  => ['0' => 'Off', '1' => 'On'],
            'multiple' => false,
            'expanded' => true,
            'required' => true,
            ])
        // notifications
        ->add('notifications_enabled', ChoiceType::class, [
            'choices'  => ['0' => 'Off', '1' => 'On'],
            'multiple' => false,
            'expanded' => true,
            'required' => true,
            ])
        // Support messages
        ->add('support_enabled', ChoiceType::class, [
            'choices'  => ['0' => 'Off', '1' => 'On'],
            'multiple' => false,
            'expanded' => true,
            'required' => true,
            ])
        // Group sender
        ->add('group_sender_enabled', ChoiceType::class, [
            'choices'  => ['0' => 'Off', '1' => 'On'],
            'multiple' => false,
            'expanded' => true,
            'required' => true,
            ])
        ->add('group_sender_type', TextType::class, [
            'required' => true,
            ])
        // Multiple recipients
        ->add('multiple_user_recipients_enabled', ChoiceType::class, [
            'choices'  => ['0' => 'Off', '1' => 'On'],
            'multiple' => false,
            'expanded' => true,
            'required' => true,
            ])
        // Group recipients
        ->add('group_recipient_enabled', ChoiceType::class, [
            'choices'  => ['0' => 'Off', '1' => 'On'],
            'multiple' => false,
            'expanded' => true,
            'required' => true,
            ])
        ->add('multiple_group_recipients_enabled', ChoiceType::class, [
            'choices'  => ['0' => 'Off', '1' => 'On'],
            'multiple' => false,
            'expanded' => true,
            'required' => true,
            ])
        //Drafts
        ->add('drafts_enabled', ChoiceType::class, [
            'choices'  => ['0' => 'Off', '1' => 'On'],
            'multiple' => false,
            'expanded' => true,
            'required' => true,
            ])
        //Stored
        ->add('stored_enabled', ChoiceType::class, [
            'choices'  => ['0' => 'Off', '1' => 'On'],
            'multiple' => false,
            'expanded' => true,
            'required' => true,
            ])
        //Trash
        ->add('trash_enabled', ChoiceType::class, [
            'choices'  => ['0' => 'Off', '1' => 'On'],
            'multiple' => false,
            'expanded' => true,
            'required' => true,
            ])
        //Drafts
        ->add('user_preferences_enabled', ChoiceType::class, [
            'choices'  => ['0' => 'Off', '1' => 'On'],
            'multiple' => false,
            'expanded' => true,
            'required' => true,
            ])
        ->add('save', SubmitType::class)
        ->add('cancel', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'zikula_intercom_module_preferences_form';
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
