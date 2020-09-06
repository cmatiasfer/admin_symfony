<?php

namespace App\Remolino\FrontBundle\Controller;

use App\Remolino\CoreBundle\Entity\HomeItems;
use App\Repository\Remolino\CoreBundle\Entity\HomeItemsRepository;
use App\Repository\Remolino\CoreBundle\Entity\HomeSlidesRepository;
use App\Repository\Remolino\CoreBundle\Entity\SliderRepository;
use App\Remolino\CoreBundle\Entity\Texts;
use App\Repository\Remolino\CoreBundle\Entity\TextsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/{_locale}", name="home" , defaults={"_locale"="en"}, requirements={
     *     "_locale"="en|es|jp"
     * })
     */
    public function index($_locale, Request $request)
    {
        return $this->render('@front_views/home/home.html.twig', [
            'lang_url' => $_locale
        ]);
    }
}