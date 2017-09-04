<?php
/**
 * Copyright (c) KaikMedia.com 2014
 */
namespace Zikula\IntercomModule\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

class GroupToIdTransformer implements DataTransformerInterface
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
     * @param string $uname            
     * @return User|null
     * @throws TransformationFailedException if object (user) is not found.
     */
    public function reverseTransform($uname)
    {
        if (! $uname) {
            return null;
        }
        return null;
        $user = $this->om->getRepository('Zikula\Module\UsersModule\Entity\UserEntity')->findOneBy(array(
            'uname' => $uname
        ));
        
        if (null === $user) {
            throw new TransformationFailedException(sprintf('A user with uid "%s" does not exist!', $uname));
        }
        
        return $user;
    }
}