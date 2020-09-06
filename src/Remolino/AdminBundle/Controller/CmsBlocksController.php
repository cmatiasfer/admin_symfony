<?php

namespace App\Remolino\AdminBundle\Controller;

use App\Remolino\CoreBundle\Entity\CmsBlocks;
use App\Remolino\AdminBundle\Form\CmsBlocksType;
use App\Repository\Remolino\CoreBundle\Entity\CmsBlocksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/core/blocks")
 */
class CmsBlocksController extends AbstractController
{
    /**
     * @Route("/", name="admin_cms_blocks_index", methods="GET")
     */
    public function index(CmsBlocksRepository $cmsBlocksRepository): Response
    {
        return $this->render('@admin_views/admin/_cms_blocks/index.html.twig', ['cms_blocks' => $cmsBlocksRepository->findBy( [] , ["listingOrder" => "ASC" ]) ]);
    }

    /**
     * @Route("/new", name="admin_cms_blocks_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $cmsBlock = new CmsBlocks();
        $form = $this->createForm(CmsBlocksType::class, $cmsBlock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cmsBlock);
            $em->flush();
            $this->addFlash('success', 'Item Added!');

            return $this->redirectToRoute('admin_cms_blocks_index');
        }

        return $this->render('@admin_views/admin/_cms_blocks/new.html.twig', [
            'cms_block' => $cmsBlock,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_cms_blocks_show", methods="GET")
     */
    public function show(CmsBlocks $cmsBlock): Response
    {
        return $this->render('@admin_views/admin/_cms_blocks/show.html.twig', ['cms_block' => $cmsBlock]);
    }

    /**
     * @Route("/{id}/edit", name="admin_cms_blocks_edit", methods="GET|POST")
     */
    public function edit(Request $request, CmsBlocks $cmsBlock): Response
    {
        $form = $this->createForm(CmsBlocksType::class, $cmsBlock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Item Updated!');

            return $this->redirectToRoute('admin_cms_blocks_index', ['id' => $cmsBlock->getId()]);
        }

        return $this->render('@admin_views/admin/_cms_blocks/edit.html.twig', [
            'cms_block' => $cmsBlock,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_cms_blocks_delete", methods="DELETE")
     */
    public function delete(Request $request, CmsBlocks $cmsBlock): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cmsBlock->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($cmsBlock);
            $em->flush();
            $this->addFlash('success', 'Item Deleted!');

        }

        return $this->redirectToRoute('admin_cms_blocks_index');
    }


}
