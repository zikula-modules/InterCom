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

class GroupToIdTransformer implements DataTransformerInterface
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
     * @param Customer|null $user
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
     * @param string $uname
     *
     * @throws TransformationFailedException if object (user) is not found.
     *
     * @return User|null
     */
    public function reverseTransform($uname)
    {
        if (!$uname) {
            return null;
        }

        return null;
//        $user = $this->om->getRepository('Zikula\Module\UsersModule\Entity\UserEntity')->findOneBy(array(
//            'uname' => $uname
//        ));
//
//        if (null === $user) {
//            throw new TransformationFailedException(sprintf('A user with uid "%s" does not exist!', $uname));
//        }
//
//        return $user;
    }
}
