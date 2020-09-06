<?php

namespace App\Remolino\AdminBundle\Controller;

use App\Remolino\CoreBundle\Entity\CmsBlocks;
use App\Repository\Remolino\CoreBundle\Entity\CmsBlocksRepository;
use App\Remolino\CoreBundle\Entity\CmsSections;
use App\Repository\Remolino\CoreBundle\Entity\CmsSectionsRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/blocks")
 */
class SidebarController extends AbstractController
{
    
    public function show(CmsBlocksRepository $cmsBlocksRepository, $currentSection, $currentPath)
    {
        $blocks = $cmsBlocksRepository->findBy(["visible" => true ], ["listingOrder" => "ASC" ]);
        
        return $this->render('@admin_views/panel/menu.html.twig', [
            'blocks'            => $blocks ,
            'currentSection'    => $currentSection,
            'currentPath'       => $currentPath
        ]);
    }
    
}