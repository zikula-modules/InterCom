<?php

/**
 */

namespace Zikula\IntercomModule\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PreferencesType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('active', 'choice', ['choices' => ['0' => 'Off', '1' => 'On'],
                    'multiple' => false,
                    'expanded' => true,
                    'required' => true])
                ->add('maintain', 'textarea', ['required' => false])
                ->add('allowhtml', 'choice', ['choices' => ['0' => 'Off', '1' => 'On'],
                    'multiple' => false,
                    'expanded' => true,
                    'required' => true])
                ->add('allowsmilies', 'choice', ['choices' => ['0' => 'Off', '1' => 'On'],
                    'multiple' => false,
                    'expanded' => true,
                    'required' => true])
                ->add('disable_ajax', 'choice', ['choices' => ['0' => 'Off', '1' => 'On'],
                    'multiple' => false,
                    'expanded' => true,
                    'required' => true])
                //Limitations
                ->add('limitarchive', 'integer', [
                    'constraints' => [
                        new Assert\GreaterThan(['value' => 0]),
                ]])
                ->add('limitoutbox', 'integer', [
                    'constraints' => [
                        new Assert\GreaterThan(['value' => 0]),
                ]])
                ->add('limitinbox', 'integer', [
                    'constraints' => [
                        new Assert\GreaterThan(['value' => 0]),
                ]])
                ->add('perpage', 'integer', [
                    'constraints' => [
                        new Assert\GreaterThan(['value' => 0]),
                ]])
                //protection
                ->add('protection_on', 'choice', ['choices' => ['0' => 'Off', '1' => 'On'],
                    'multiple' => false,
                    'expanded' => true,
                    'required' => true])
                ->add('protection_time', 'text', ['required' => false])
                ->add('protection_amount', 'text', ['required' => false])
                ->add('protection_mail', 'choice', ['choices' => ['0' => 'Off', '1' => 'On'],
                    'multiple' => false,
                    'expanded' => true,
                    'required' => true])
                //user prompt
                ->add('userprompt', 'text', ['required' => false])
                ->add('userprompt_display', 'choice', ['choices' => ['0' => 'Off', '1' => 'On'],
                    'multiple' => false,
                    'expanded' => true,
                    'required' => true])
                //Welcome
                ->add('welcomemessage_send', 'choice', ['choices' => ['0' => 'Off', '1' => 'On'],
                    'multiple' => false,
                    'expanded' => true,
                    'required' => true])
                ->add('welcomemessagesender', 'text', ['required' => false])
                ->add('welcomemessagesubject', 'text', ['required' => false])
                ->add('welcomemessage', 'text', ['required' => false])
                ->add('intlwelcomemessage', 'text', ['required' => false])
                ->add('savewelcomemessage', 'choice', ['choices' => ['0' => 'Off', '1' => 'On'],
                    'multiple' => false,
                    'expanded' => true,
                    'required' => true])
                //Email
                ->add('allow_emailnotification', 'choice', ['choices' => ['0' => 'Off', '1' => 'On'],
                    'multiple' => false,
                    'expanded' => true,
                    'required' => true])
                ->add('force_emailnotification', 'choice', ['choices' => ['0' => 'Off', '1' => 'On'],
                    'multiple' => false,
                    'expanded' => true,
                    'required' => true])
                ->add('mailsender', 'text', ['required' => false])
                ->add('mailsubject', 'text', ['required' => false])
                ->add('fromname', 'text', ['required' => false])
                ->add('from_email', 'email', ['required' => false])
                //Autoreply
                ->add('allow_autoreply', 'choice', ['choices' => ['0' => 'Off', '1' => 'On'],
                    'multiple' => false,
                    'expanded' => true,
                    'required' => true])
                //Mode
                ->add('mode', 'choice', ['choices' => ['0' => 'Classic', '1' => 'Conversation'],
                    'multiple' => false,
                    'expanded' => true,
                    'required' => true])
                //Layout
                ->add('layout', 'choice', ['choices' => ['classic' => 'Classic', 'conversation' => 'Conversation'],
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true])
                ->add('support_messages_enabled', 'choice', ['choices' => ['0' => 'Off', '1' => 'On'],
                    'multiple' => false,
                    'expanded' => true,
                    'required' => true])
                ->add('system_notifications_enabled', 'choice', ['choices' => ['0' => 'Off', '1' => 'On'],
                    'multiple' => false,
                    'expanded' => true,
                    'required' => true])
                ->add('save', 'submit')
                ->add('cancel', 'submit');
    }

    public function getName() {
        return 'preferences_form';
    }

    /**
     * OptionsResolverInterface is @deprecated and is supposed to be replaced by
     * OptionsResolver but docs not clear on implementation
     * 
     * @param OptionsResolverInterface $resolver            
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults([]);
    }

}
