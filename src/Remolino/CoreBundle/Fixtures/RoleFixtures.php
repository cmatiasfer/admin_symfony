<?php

namespace App\Remolino\CoreBundle\Fixtures;

use App\Remolino\CoreBundle\Entity\CmsRoles;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class RoleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // User Role
        $role_user = new CmsRoles();
        $role_user->setName('User/Client');
        $role_user->setRole('ROLE_USER');
        $manager->persist($role_user);

        // Admin Role
        $role_admin = new CmsRoles();
        $role_admin->setName('Administrator');
        $role_admin->setRole('ROLE_ADMIN');
        $manager->persist($role_admin);

        $manager->flush();
    }
}
