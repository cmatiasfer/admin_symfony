<?php

namespace App\Remolino\AdminBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use App\Remolino\CoreBundle\Service\AdminService;

/**
 * @Route("image")
 */
class ImageController extends AbstractController
{
    /**
     * @Route("/new", name="image_new", methods={"POST"})
     */
    public function new(Request $request, AdminService $adminService): Response
    {
        
        $form = $request->query->all();
        dd($form,$request);
        return new Response("hi!");
    }
}