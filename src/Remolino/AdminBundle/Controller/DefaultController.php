<?php

namespace App\Remolino\AdminBundle\Controller;

use App\Repository\Remolino\CoreBundle\Entity\{HomeGalleryRepository};

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{   
    /** 
     * @Route({
     *     "/login",
     *     "/admin"
     * }, name="admin_index")
     */
    public function indexAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('admin_dashboard'));
        }
        return $this->redirect($this->generateUrl('admin_login'));
    }

    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function dashboardLoad(HomeGalleryRepository $homeGalleryRepository)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {                                        
            return $this->render('@admin_views/panel/dashboard.html.twig', [
                'home_gallery' => $homeGalleryRepository->findAll(),
            ]);
        }
        return $this->redirect($this->generateUrl('admin_login'));
    }    
}