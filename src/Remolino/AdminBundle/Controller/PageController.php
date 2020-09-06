<?php

namespace App\Remolino\AdminBundle\Controller;

use App\Remolino\CoreBundle\Entity\Page;
use App\Repository\Remolino\CoreBundle\Entity\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Remolino\AdminBundle\Form\PageType;

/**
 * @Route("admin/pages")
 */
class PageController extends AbstractController
{
    /**
     * @Route("/", name="page_index", methods={"GET"})
     */
    public function index(PageRepository $pageRepository): Response
    {
        $titles = array("Title","Listing Order","Visible");
        $fields = array("title","listingOrder","Visible");
        $filters = array();

        return $this->render('@admin_views/layouts/index.html.twig', [
            'entity' => $pageRepository->findAll(),
            'route' => 'page_',
            'section_title' => 'Page',
            'section' => 'page',
            'titles' => $titles,
            'fields' => $fields,
            'filters' => $filters,
            'folder' => ''
        ]);
    }

    /**
     * @Route("/new", name="page_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $page = new Page();
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($page);
            $entityManager->flush();
            $this->addFlash('success', 'Item Added!');

            return $this->redirectToRoute('page_index');
        }

        return $this->render('@admin_views/layouts/new.html.twig', [
            'entity' => $page,
            'route' => 'page_',
            'section_title' => 'Page',
            'section' => 'page',
            'form' => $form->createView(),
            'folder' => ''
        ]);
    }

    /**
     * @Route("/{id}", name="page_show", methods={"GET"})
     */
    public function show(Page $page ,PageRepository $pageRepository): Response
    {
        $titles = array("Title","Description","Route","Listing Order","Visible on Header Menu","Visible on Footer Menu","Visible","SEO Title","SEO URL","SEO Description");
        $fields = array("title","Description","Route","listingOrder","headerVisible","footerVisible","visible","seoTITLE","seoURL","seoDesc");

        return $this->render('@admin_views/layouts/show.html.twig', [
            'entity' => $page,
            'titles' => $titles,
            'fields' => $fields,
            'name' => $page->getTitle(),
            'route' => 'page_',
            'section' => 'page',
            'section_title' => 'Page',
            'folder' => '',
            'images' => ''
        ]);
    }

    /**
     * @Route("/{id}/edit", name="page_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Page $page): Response
    {
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Item Updated!');

            return $this->redirectToRoute('page_index', [
                'id' => $page->getId(),
            ]);
        }

        return $this->render('@admin_views/layouts/edit.html.twig', [
            'entity' => $page,
            'form' => $form->createView(),
            'route' => 'page_',
            'section_title' => 'Page',
            'section' => 'page',
            'id' => $page->getId(),
            'folder' => ''
        ]);
    }

    /**
     * @Route("/{id}", name="page_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Page $page): Response
    {
        if ($this->isCsrfTokenValid('delete'.$page->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($page);
            $entityManager->flush();
            $this->addFlash('success', 'Item Deleted!');
        }

        return $this->redirectToRoute('page_index');
    }
}