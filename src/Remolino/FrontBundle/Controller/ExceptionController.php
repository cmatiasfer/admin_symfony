<?php

namespace App\Remolino\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ExceptionController extends AbstractController
{
    public function showException()
    {
        $lang = 'en';
        $error404 = true;
        // $image = $imagesWebRepository->findOneBy(["visible" => true , "section" => "404"],[], 1);
        return $this->render('@front_views/404/error404.html.twig', [
            'lang_url' => $lang,
            'error404' => $error404,
            'image' => $image
        ]);
    }
}
