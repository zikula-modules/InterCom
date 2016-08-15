<?php
/**
 * Copyright (c) KaikMedia.com 2014
 */
namespace Zikula\IntercomModule\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

class UserToIdTransformer implements DataTransformerInterface
{

    /**
     *
     * @var ObjectManager
     */
    private $om;

    /**
     *
     * @param ObjectManager $om            
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (user) to a string (id).
     * 
     * @param Customer|null $customer            
     * @return string
     */
    public function transform($user)
    {
        if (null === $user) {
            return "";
        }
        
        return $user->getUname();
    }

    /**
     * Transforms a string (uname) to an object (user).
     * 
     * @param string $name            
     * @return User|null
     * @throws TransformationFailedException if object (user) is not found.
     */
    public function reverseTransform($name)
    {
        if (!$name) {
            return null;
        }
        
        $user = $this->om->getRepository('Zikula\UsersModule\Entity\UserEntity')->findOneBy(array(
            'uname' => $name
        ));
            
        if (null === $user) {
            throw new TransformationFailedException(sprintf('A user with uname "%s" does not exist!', $name));
        }
        
        return $user;
    }
}