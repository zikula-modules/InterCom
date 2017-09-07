<?php

/*
 * InterCom Module for Zikula
 *
 * @copyright  InterCom Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @see https://github.com/zikula-modules/InterCom
 */

namespace Zikula\IntercomModule\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class UserToIdTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (user) to a string (id).
     *
     * @param User|null $user
     *
     * @return string
     */
    public function transform($user)
    {
        if (null === $user) {
            return '';
        }

        return $user->getUname();
    }

    /**
     * Transforms a string (uname) to an object (user).
     *
     * @param string $name
     *
     * @throws TransformationFailedException if object (user) is not found.
     *
     * @return User|null
     */
    public function reverseTransform($name)
    {
        if (!$name) {
            return null;
        }

//        $user = $this->om->getRepository('Zikula\UsersModule\Entity\UserEntity')->findOneBy(array(
//            'uname' => $name
//        ));
//
//        if (null === $user) {
//            throw new TransformationFailedException(sprintf('A user with uname "%s" does not exist!', $name));
//        }
//
//        return $user;
    }
}
