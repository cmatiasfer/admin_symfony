<?php

namespace App\Remolino\AdminBundle\Controller;

use App\Remolino\CoreBundle\Entity\BlockPage;
use App\Remolino\AdminBundle\Form\BlockPageType;
use App\Repository\Remolino\CoreBundle\Entity\BlockPageRepository;
use App\Repository\Remolino\CoreBundle\Entity\PagePageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Remolino\AdminBundle\Form\PageType;

/**
 * @Route("admin/core/block-pages")
 */
class BlockPageController extends AbstractController
{
    /**
     * @Route("/", name="block_page_index", methods={"GET"})
     */
    public function index(BlockPageRepository $blockPageRepository): Response
    {
        $titles = array("Page","Title","Listing Order","Visible");
        $fields = array("page","title","listingOrder","Visible");
        $filters = array();

        return $this->render('@admin_views/layouts/index.html.twig', [
            'entity' => $blockPageRepository->findAll(),
            'route' => 'block_page_',
            'section_title' => 'Block Page',
            'section' => 'block_page',
            'titles' => $titles,
            'fields' => $fields,
            'filters' => $filters,
            'folder' => ''
        ]);
    }

    /**
     * @Route("/new", name="block_page_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $blockPage = new BlockPage();
        $form = $this->createForm(BlockPageType::class, $blockPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($blockPage);
            $entityManager->flush();
            $this->addFlash('success', 'Item Added!');

            return $this->redirectToRoute('block_page_index');
        }

        return $this->render('@admin_views/layouts/new.html.twig', [
            'entity' => $blockPage,
            'route' => 'block_page_',
            'section_title' => 'Block Page',
            'section' => 'block_page',
            'form' => $form->createView(),
            'folder' => ''
        ]);
    }

    /**
     * @Route("/{id}", name="block_page_show", methods={"GET"})
     */
    public function show(BlockPage $blockPage, BlockPageRepository $blockPageRepository): Response
    {
        $titles = array("Page","Title","Description","Route","Listing Order", "Visible","SEO Title","SEO URL","SEO Description");
        $fields = array("page","title","Description","Route","listingOrder","visible","seoTITLE","seoURL","seoDesc");

        return $this->render('@admin_views/layouts/show.html.twig', [
            'entity' => $blockPage,
            'titles' => $titles,
            'fields' => $fields,
            'name' => $blockPage->getTitle(),
            'route' => 'block_page_',
            'section' => 'block_page',
            'section_title' => 'Block Page',
            'folder' => '',
            'images' => ''
        ]);
    }

    /**
     * @Route("/{id}/edit", name="block_page_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, BlockPage $blockPage): Response
    {
        $form = $this->createForm(BlockPageType::class, $blockPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Item Updated!');

            return $this->redirectToRoute('block_page_index', [
                'id' => $blockPage->getId(),
            ]);
        }

        return $this->render('@admin_views/layouts/edit.html.twig', [
            'entity' => $blockPage,
            'form' => $form->createView(),
            'route' => 'block_page_',
            'section_title' => 'Block Page',
            'section' => 'block_page',
            'id' => $blockPage->getId(),
            'folder' => ''
        ]);
    }

    /**
     * @Route("/{id}", name="block_page_delete", methods={"DELETE"})
     */
    public function delete(Request $request, BlockPage $blockPage): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blockPage->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($blockPage);
            $entityManager->flush();
            $this->addFlash('success', 'Item Deleted!');
        }

        return $this->redirectToRoute('block_page_index');
    }
}