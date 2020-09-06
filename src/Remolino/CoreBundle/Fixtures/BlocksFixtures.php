<?php

namespace App\Remolino\CoreBundle\Fixtures;

use App\Remolino\CoreBundle\Entity\CmsSections;
use App\Remolino\CoreBundle\Entity\CmsBlocks;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class BlocksFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $adminBlock = new CmsBlocks();

        $adminBlock->setName('Admin');
        $adminBlock->setListingOrder(200);
        $adminBlock->setVisible(true);
        $adminBlock->setIconClass("fa fa-shield");
        $adminBlock->setRoute("");

        $manager->persist($adminBlock);

        $manager->flush();

        $sections  = array(
            0=>array(
                    "Name"=>"Users",
                    "listingOrder"=>100,
                    "visible"=>true,
                    "iconClass"=>"fa fa-users",
                    "route" => "admin_cms_user_",
                    "description" => "",
                    "block" => $adminBlock
                ),
            1=>array(
                    "Name"=>"Texts",
                    "listingOrder"=>200,
                    "visible"=>true,
                    "iconClass"=>"fa fa-text-height",
                    "route" => "admin_cms_texts_",
                    "description" => "",
                    "block" => $adminBlock
                ),
            2=>array(
                    "Name"=>"Blocks",
                    "listingOrder"=>300,
                    "visible"=>true,
                    "iconClass"=>"fa fa-bars",
                    "route" => "admin_cms_blocks_",
                    "description" => "",
                    "block" => $adminBlock
                ),
            3=>array(
                    "Name"=>"Sections",
                    "listingOrder"=>400,
                    "visible"=>true,
                    "iconClass"=>"fa fa-minus",
                    "route" => "admin_cms_sections_",
                    "description" => "",
                    "block" => $adminBlock
                )
        );

        foreach ($sections as $section){
            $cmsSections = new CmsSections();
            $cmsSections->setName($section["Name"]);
            $cmsSections->setListingOrder($section["listingOrder"]);
            $cmsSections->setVisible($section["visible"]);
            $cmsSections->setIconClass($section["iconClass"]);
            $cmsSections->setRoute($section["route"]);
            $cmsSections->setDescription($section["description"]);
            $cmsSections->setBlock($section["block"]);
            $manager->persist($cmsSections);
            $manager->flush();
        }

        // $this->addReference($adminBlock->getId(), $adminBlock);

        // Admin Role
        $dashBlock = new CmsBlocks();
        $dashBlock->setName('Dashboard');
        $dashBlock->setListingOrder(100);
        $dashBlock->setVisible(true);
        $dashBlock->setIconClass("fa fa-dashboard");
        $dashBlock->setRoute("admin_dashboard");
        $manager->persist($dashBlock);
        $manager->flush();
        
        $dashBlock = new CmsBlocks();
        $dashBlock->setName('Home');
        $dashBlock->setListingOrder(300);
        $dashBlock->setVisible(true);
        $dashBlock->setIconClass("fa fa-home");
        $dashBlock->setRoute("home");
        $manager->persist($dashBlock);
        $manager->flush();
        
        $dashBlock = new CmsBlocks();
        $dashBlock->setName('Page');
        $dashBlock->setListingOrder(9997);
        $dashBlock->setVisible(true);
        $dashBlock->setIconClass("fa fa-shield");
        $dashBlock->setRoute("page");
        $manager->persist($dashBlock);
        $manager->flush();
        
        $dashBlock = new CmsBlocks();
        $dashBlock->setName('Contact');
        $dashBlock->setListingOrder(9998);
        $dashBlock->setVisible(true);
        $dashBlock->setIconClass("fa fa-shield");
        $dashBlock->setRoute("contact");
        $manager->persist($dashBlock);
        $manager->flush();

        $dashBlock = new CmsBlocks();
        $dashBlock->setName('Text');
        $dashBlock->setListingOrder(9999);
        $dashBlock->setVisible(true);
        $dashBlock->setIconClass("fa fa-shield");
        $dashBlock->setRoute("texts");
        $manager->persist($dashBlock);
        $manager->flush();

    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }

    public function getOrder() {
        return 1;
    }
}