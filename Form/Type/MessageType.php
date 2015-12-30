<?php

/**
 * Copyright (c) KaikMedia.com 2014
 */

namespace Zikula\IntercomModule\Form\Type;

//use Zikula\IntercomModule\Form\DataTransformer\GroupToIdTransformer;
use Symfony\Component\Form\AbstractType as SymfonyAbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Zikula\Common\I18n\TranslatableInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Zikula\IntercomModule\Form\DataTransformer\UserToIdTransformer;
use Zikula\IntercomModule\Form\DataTransformer\GroupToIdTransformer;

class MessageType extends SymfonyAbstractType implements TranslatableInterface {

    protected $domain;
    protected $entityManager;

    public function __construct($entityManager) {
        $this->domain = 'zikulaintercommodule';
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->setMethod('POST')
                ->add($builder->create('recipient', 'text', ['invalid_message' => $this->__('User not found.'),'attr' => ['class' => 'author_search']])
                        ->addModelTransformer(new UserToIdTransformer($this->entityManager)))
                ->add($builder->create('groups', 'text', ['mapped' => false,'required' => false,'invalid_message' => $this->__('Group not found.'), 'attr' => ['class' => 'author_search']])
                        ->addModelTransformer(new GroupToIdTransformer($this->entityManager)))
                ->add('subject', 'text', [
                    'required' => false
                ])
                ->add('text', 'textarea', [
                    'required' => true, 'attr' => [
                        'class' => 'tinymce'
                    ]
                ]);
   
        if ($options['isXmlHttpRequest'] == false) {
            $builder->add('save', 'submit', [
                'label' => $this->__('Send'),
                'attr' => [
                    'class' => 'btn-success'
                ]
            ]);
        }       

    }

    public function getName() {
        return 'messageform';
    }   
    
    /**
     * OptionsResolverInterface is @deprecated and is supposed to be replaced by
     * OptionsResolver but docs not clear on implementation
     *
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'isXmlHttpRequest' => false,
        ));
    }

    /**
     * singular translation for modules.
     *
     * @param string $msg Message.
     *
     * @return string
     */
    public function __($msg) {
        return __($msg, $this->domain);
    }

    /**
     * Plural translations for modules.
     *
     * @param string $m1 Singular.
     * @param string $m2 Plural.
     * @param int    $n  Count.
     *
     * @return string
     */
    public function _n($m1, $m2, $n) {
        return _n($m1, $m2, $n, $this->domain);
    }

    /**
     * Format translations for modules.
     *
     * @param string       $msg   Message.
     * @param string|array $param Format parameters.
     *
     * @return string
     */
    public function __f($msg, $param) {
        return __f($msg, $param, $this->domain);
    }

    /**
     * Format pural translations for modules.
     *
     * @param string       $m1    Singular.
     * @param string       $m2    Plural.
     * @param int          $n     Count.
     * @param string|array $param Format parameters.
     *
     * @return string
     */
    public function _fn($m1, $m2, $n, $param) {
        return _fn($m1, $m2, $n, $param, $this->domain);
    }
}
