<?php

namespace App\Remolino\CoreBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of TwigGlobal
 */
class TwigGlobal
{
    protected $container = null;

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function setSession($session)
    {
        $this->session = $session;
    }

    public function getSession()
    {
        return $this->session;
    }

    public function getManager()
    {
        return $this->container->get('doctrine')->getManager();
    }

    public function checkUse($bundle)
    {
        //////////////////////////////////////////////
        //Add global twig var for core configuration//
        //////////////////////////////////////////////
        $bundles = $this->container->getParameter('kernel.bundles');

        if (isset($bundles[$bundle])) {
            return true;
        } else {
            return false;
        }
    }

    public function getParameter($parameter)
    {
        $parameter = $this->container->getParameter($parameter);

        return $parameter;
    }
}
