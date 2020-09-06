<?php

namespace App\Remolino\AdminBundle\Controller;

use App\Remolino\CoreBundle\Entity\CmsTexts;
use App\Remolino\AdminBundle\Form\CmsTextsType;
use App\Repository\Remolino\CoreBundle\Entity\CmsTextsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/core/cms-texts")
 */
class CmsTextsController extends AbstractController
{
    /**
     * @Route("/", name="admin_cms_texts_index", methods="GET")
     */
    public function index(CmsTextsRepository $cmsTextsRepository): Response
    {
        return $this->render('@admin_views/admin/_cms_texts/index.html.twig', ['cms_texts' => $cmsTextsRepository->findAll()]);
    }

    /**
     * @Route("/new", name="admin_cms_texts_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $cmsText = new CmsTexts();
        $form = $this->createForm(CmsTextsType::class, $cmsText);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cmsText);
            $em->flush();
            $this->addFlash('success', 'Item Added!');

            return $this->redirectToRoute('admin_cms_texts_index');
        }

        return $this->render('@admin_views/admin/_cms_texts/new.html.twig', [
            'cms_text' => $cmsText,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_cms_texts_show", methods="GET")
     */
    public function show(CmsTexts $cmsText): Response
    {
        return $this->render('@admin_views/admin/_cms_texts/show.html.twig', ['cms_text' => $cmsText]);
    }

    /**
     * @Route("/{id}/edit", name="admin_cms_texts_edit", methods="GET|POST")
     */
    public function edit(Request $request, CmsTexts $cmsText): Response
    {
        $form = $this->createForm(CmsTextsType::class, $cmsText);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Item Updated!');

            return $this->redirectToRoute('admin_cms_texts_index', ['id' => $cmsText->getId()]);
        }

        return $this->render('@admin_views/admin/_cms_texts/edit.html.twig', [
            'cms_text' => $cmsText,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_cms_texts_delete", methods="DELETE")
     */
    public function delete(Request $request, CmsTexts $cmsText): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cmsText->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($cmsText);
            $em->flush();
            $this->addFlash('success', 'Item Deleted!');
        }

        return $this->redirectToRoute('admin_cms_texts_index');
    }
}
