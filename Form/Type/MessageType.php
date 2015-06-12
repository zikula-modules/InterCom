<?php
/**
 * Copyright (c) KaikMedia.com 2014
 */
namespace Zikula\IntercomModule\Form\Type;

use ServiceUtil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Zikula\IntercomModule\Form\DataTransformer\UserToIdTransformer;
//use Zikula\IntercomModule\Form\DataTransformer\GroupToIdTransformer;

class MessageType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // this assumes that the entity manager was passed in as an optio
        $em = ServiceUtil::getService('doctrine.entitymanager');
        // $entityManager = $options['em'];
        $user_transformer = new UserToIdTransformer($em);
        //$group_transformer = new GroupToIdTransformer($em);
        
        /*
         * private $sender;
         * private $recipient;
         * private $subject = '';
         * private $send;
         * private $text = '';
         * private $mtype;
         * private $seen;
         * private $replied;
         * private $notified;
         * private $storedbysender;
         * private $storedbyrecipient;
         * private $deletedbysender;
         * private $deletedbyrecipient;
         * private $conversationid;
         * private $conversation;
         */
        
        $builder->setMethod('POST')
            ->
        add($builder->create('recipient', 'text', [
            'attr' => [
                'class' => 'author_search'
            ]
        ])
            ->addModelTransformer($user_transformer))
            ->
        add('subject', 'text', array(
            'required' => false
        ))
            ->
        add('text', 'textarea', array(
            'required' => true,'attr' => array(
                'class' => 'tinymce'
            )
        ))
            ->add('save', 'submit', array(
            'label' => 'Save'
        ));
    }

    public function getName()
    {
        return 'messageform';
    }

    /**
     * OptionsResolverInterface is @deprecated and is supposed to be replaced by
     * OptionsResolver but docs not clear on implementation
     * 
     * @param OptionsResolverInterface $resolver            
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'title' => null,'content' => null
        ));
    }
}