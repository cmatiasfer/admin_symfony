<?php

namespace App\Remolino\AdminBundle\Controller;

use App\Remolino\CoreBundle\Entity\Social;
use App\Remolino\AdminBundle\Form\SocialType;
use App\Repository\Remolino\CoreBundle\Entity\SocialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @Route("admin/social-networks")
 */
class SocialController extends AbstractController
{
    /**
     * @Route("/", name="social_index", methods={"GET"})
     */
    public function index(SocialRepository $socialRepository): Response
    {
        $titles = array("Name","Link");
        $fields = array("name","link");
        $filters = array();

        return $this->render('@admin_views/layouts/index.html.twig', [
            'entity' => $socialRepository->findAll(),
            'route' => 'social_',
            'section_title' => 'Social',
            'section' => 'social',
            'titles' => $titles,
            'fields' => $fields,
            'filters' => $filters,
            'folder' => ''
        ]);
    }

    /**
     * @Route("/new", name="social_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $social = new Social();
        $form = $this->createForm(SocialType::class, $social);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($social);
            $entityManager->flush();
            $this->addFlash('success', 'Item Added!');

            return $this->redirectToRoute('social_index');
        }

        return $this->render('@admin_views/layouts/new.html.twig', [
            'entity' => $social,
            'route' => 'social_',
            'section_title' => 'Social',
            'section' => 'social',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="social_show", methods={"GET"})
     */
    public function show(Social $social, ObjectManager $manager): Response
    {
        $name = $social->getName();
        $titles = array("Name","Link");
        $fields = array("name","link");

        return $this->render('@admin_views/layouts/show.html.twig', [
            'entity' => $social,
            'titles' => $titles,
            'fields' => $fields,
            'route' => 'social_',
            'section_title' => 'Social',
            'section' => 'social',
            'name' => $name,
            'images' => ''
        ]);
    }

    /**
     * @Route("/{id}/edit", name="social_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Social $social, ObjectManager $manager): Response
    {
        $form = $this->createForm(SocialType::class, $social);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Item Updated!');

            return $this->redirectToRoute('social_index', [
                'id' => $social->getId(),
            ]);
        }

        return $this->render('@admin_views/layouts/edit.html.twig', [
            'entity' => $social,
            'form' => $form->createView(),
            'route' => 'social_',
            'section_title' => 'Social',
            'section' => 'social',
            'id' => $social->getId(),
            'folder' => ''     
        ]);
    }

    /**
     * @Route("/{id}", name="social_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Social $social): Response
    {
        if ($this->isCsrfTokenValid('delete'.$social->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($social);
            $entityManager->flush();
            $this->addFlash('success', 'Item Deleted!');
        }

        return $this->redirectToRoute('social_index');
    }
}