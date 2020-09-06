<?php

namespace App\Remolino\CoreBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Repository\Remolino\CoreBundle\Entity\TextsRepository;

/**
 * Description of CommonManager
 */
class CommonManager
{
    protected $container;

    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * Container
     * @return type
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Get doctrine manager
     * @return type
     */
    public function getManager()
    {
        return $this->container->get('doctrine')->getManager();
    }

    /**
     * Get session
     * @return type
     */
    public function getSession()
    {
        return $this->container->get('session');
    }

    /**
     * Get server base url
     * @return type
     */
    public function getServerBaseUrl()
    {
        return $this->getContainer()->get('twig.global')->getServerBaseUrl();
    }

    /**
     * Search translation text on db
     * @return type
     */
    public function getTranslation($key = null, TextsRepository $textsRepository)
    {
        return $textsRepository->findOneBy(array('variable' => $key));
    }
}