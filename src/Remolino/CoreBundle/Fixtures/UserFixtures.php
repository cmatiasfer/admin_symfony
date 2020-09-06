<?php

namespace App\Remolino\CoreBundle\Fixtures;
use App\Remolino\CoreBundle\Entity\CmsUser;
use App\Remolino\CoreBundle\Entity\Profile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        /* Añadir 3 Usuarios y 3 Perfiles */

        /* 
            Usuarios default: cmatiasfer[user,admin], admin, user
        */
        
        // Admin
        $user = new CmsUser();
        $user->setUsername('cmatiasfer');
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $user->setStatus(true);
        $user->setCreatedAt('');
        $password = $this->encoder->encodePassword($user, '123456');
        $user->setPassword($password);
        $manager->persist($user);

        // Admin Profile
        $profile = new Profile();
        $profile->setFirstname('Matias Fernando');
        $profile->setLastname('Colque');
        $profile->setEmail('c.matiasfer@gmail.com');
        $manager->persist($profile);
        $manager->flush();

        $user->setProfile($profile->getId());
        $profile->setUser($user->getId());
        $manager->persist($user);
        $manager->persist($profile);
        $manager->flush();

        // Generic Admin
        $user = new CmsUser();
        $user->setUsername('admin');
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $user->setStatus(1);
        $user->setCreatedAt('');
        $password = $this->encoder->encodePassword($user, '123456');
        $user->setPassword($password);
        $manager->persist($user);

        // Generic Profile
        $profile = new Profile();
        $profile->setFirstname(' ');
        $profile->setLastname(' ');
        $profile->setEmail(' ');
        $manager->persist($profile);
        $manager->flush();

        $user->setProfile($profile->getId());
        $profile->setUser($user->getId());
        $manager->persist($user);
        $manager->persist($profile);
        $manager->flush();

        // User
        $user = new CmsUser();
        $user->setUsername('user');
        $user->setRoles(['ROLE_USER']);
        $user->setStatus(1);
        $user->setCreatedAt('');
        $password = $this->encoder->encodePassword($user, '123456');
        $user->setPassword($password);
        $manager->persist($user);

        // User Profile
        $profile = new Profile();
        $profile->setFirstname(' ');
        $profile->setLastname(' ');
        $profile->setEmail(' ');
        $manager->persist($profile);
        $manager->flush();

        $user->setProfile($profile->getId());
        $profile->setUser($user->getId());
        $manager->persist($user);
        $manager->persist($profile);
        $manager->flush();
    }
}

