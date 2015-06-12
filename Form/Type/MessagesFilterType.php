<?php
/**
 * Copyright (c) KaikMedia.com 2014
 */
namespace Zikula\IntercomModule\Form\Type;

use ServiceUtil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MessagesFilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setMethod('GET')
            ->add('limit', 'choice', array(
            'choices' => array(
                '10' => '10',
                '25' => '25',
                '50' => '50'
            ),
            'required' => false,
            'data' => $options['limit']
        ))
            ->add('title', 'text', array(
            'required' => false,
            'data' => $options['title']
        ))
            ->add('online', 'choice', array(
            'choices' => array(
                'online' => 'Online',
                'offline' => 'Offline'
            ),
            'required' => false,
            'data' => $options['online']
        ))
            ->add('filter', 'submit', array(
            'label' => 'Filter'
        ));
    }

    public function getName()
    {
        return 'messagesfilterform';
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
            'limit' => null,
            'title' => null,
            'online' => null
        ));
    }
}