<?php

namespace App\Remolino\AdminBundle\Controller;

use App\Remolino\CoreBundle\Entity\CmsSections;
use App\Remolino\AdminBundle\Form\CmsSectionsType;
use App\Repository\Remolino\CoreBundle\Entity\CmsSectionsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/core/sections")
 */
class CmsSectionsController extends AbstractController
{
    /**
     * @Route("/", name="admin_cms_sections_index", methods="GET")
     */
    public function index(CmsSectionsRepository $cmsSectionsRepository): Response
    {
        return $this->render('@admin_views/admin/_cms_sections/index.html.twig', [ 'cms_sections' => $cmsSectionsRepository->findBy( [] , ["listingOrder" => "ASC" ]) ]);
    }

    /**
     * @Route("/new", name="admin_cms_sections_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $cmsSection = new CmsSections();
        $form = $this->createForm(CmsSectionsType::class, $cmsSection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cmsSection);
            $em->flush();
            $this->addFlash('success', 'Item Added!');

            return $this->redirectToRoute('admin_cms_sections_index');
        }

        return $this->render('@admin_views/admin/_cms_sections/new.html.twig', [
            'cms_section' => $cmsSection,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_cms_sections_show", methods="GET")
     */
    public function show(CmsSections $cmsSections): Response
    {
        return $this->render('@admin_views/admin/_cms_sections/show.html.twig', ['cms_sections' => $cmsSections]);
    }

    /**
     * @Route("/{id}/edit", name="admin_cms_sections_edit", methods="GET|POST")
     */
    public function edit(Request $request, CmsSections $cmsSection): Response
    {
        $form = $this->createForm(CmsSectionsType::class, $cmsSection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Item Updated!');

            return $this->redirectToRoute('admin_cms_sections_index', ['id' => $cmsSection->getId()]);
        }

        return $this->render('@admin_views/admin/_cms_sections/edit.html.twig', [
            'cms_section' => $cmsSection,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_cms_sections_delete", methods="DELETE")
     */
    public function delete(Request $request, CmsSections $cmsSection): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cmsSection->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($cmsSection);
            $em->flush();
            $this->addFlash('success', 'Item Deleted!');
        }

        return $this->redirectToRoute('admin_cms_sections_index');
    }
}
