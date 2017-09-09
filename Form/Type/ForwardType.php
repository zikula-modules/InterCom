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

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zikula\GroupsModule\Entity\GroupEntity;
use Zikula\IntercomModule\Entity\Recipient\UserRecipientEntity;

class ForwardType extends AbstractType
{
    protected $entityManager;

    public function __construct($entityManager)
    {
        $this->domain = 'zikulaintercommodule';
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setMethod('POST')
            ->add('sendAsGroup', EntityType::class, [
                'class'         => GroupEntity::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'choice_label'  => 'name',
                'required'      => false,
            ])
            ->add('recipientUsers', TextType::class, [
                'mapped'    => false,
                'required'  => false,
            ])
            ->add('recipientGroups', TextType::class, [
                'mapped'    => false,
                'required'  => false,
            ])
            ->add('subject', TextType::class, [
                'required'  => false,
            ])
            ->add('text', TextareaType::class, [
                'required'  => true,
            ]);

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                // this would be your entity, i.e. SportMeetup
                $data = $event->getData();
                $recipients_string = $form->get('recipientUsers')->getData();
                $unames_array = explode(',', $recipients_string);
                foreach ($unames_array as $recipient_uname) {
                    $recipient = $this->entityManager->getRepository('Zikula\UsersModule\Entity\UserEntity')->findOneBy(['uname' => $recipient_uname]);
                    if (!$recipient) {
                        continue;
                    }
                    $user_recipient = new UserRecipientEntity();
                    $user_recipient->setUser($recipient);
                    $user_recipient->setMessage($data);
                    $data->getRecipientUsers()->add($user_recipient);
                }
                //Groups @todo
//                $recipients_string = $form->get('recipientUsers')->getData();
//                $unames_array = explode(',', $recipients_string);
//                foreach ($unames_array as $recipient_uname) {
//                    $recipient = $this->entityManager->getRepository('Zikula\UsersModule\Entity\UserEntity')->findOneBy(['uname' => $recipient_uname]);
//                    if (!$recipient) {
//
//                        continue;
//                    }
//                    $user_recipient = new UserRecipientEntity();
//                    $user_recipient->setUser($recipient);
//                    $user_recipient->setMessage($data);
//                    $data->getRecipientUsers()->add($user_recipient);
//                }
            }
        );

        if ($options['isXmlHttpRequest'] == false) {
            $builder->add('send', SubmitType::class)
                    ->add('saveAsDraft', SubmitType::class)
                    ->add('preview', SubmitType::class);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'zikula_intercom_forward_type';
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
