<?php

namespace App\Remolino\AdminBundle\Controller;

use App\Remolino\CoreBundle\Entity\CmsRoles;
use App\Remolino\AdminBundle\Form\CmsRolesType;
use App\Repository\Remolino\CoreBundle\Entity\CmsRolesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/core/roles")
 */
class CmsRolesController extends AbstractController
{
    /**
     * @Route("/", name="admin_cms_roles_index", methods="GET")
     */
    public function index(CmsRolesRepository $cmsRolesRepository): Response
    {
        return $this->render('@admin_views/admin/_cms_roles/index.html.twig', ['cms_roles' => $cmsRolesRepository->findAll()]);
    }

    /**
     * @Route("/new", name="admin_cms_roles_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $cmsRole = new CmsRoles();
        $form = $this->createForm(CmsRolesType::class, $cmsRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cmsRole);
            $em->flush();
            $this->addFlash('success', 'Item Added!');


            return $this->redirectToRoute('admin_cms_roles_index');
        }

        return $this->render('@admin_views/admin/_cms_roles/new.html.twig', [
            'cms_role' => $cmsRole,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_cms_roles_show", methods="GET")
     */
    public function show(CmsRoles $cmsRole): Response
    {
        return $this->render('@admin_views/admin/_cms_roles/show.html.twig', ['cms_role' => $cmsRole]);
    }

    /**
     * @Route("/{id}/edit", name="admin_cms_roles_edit", methods="GET|POST")
     */
    public function edit(Request $request, CmsRoles $cmsRole): Response
    {
        $form = $this->createForm(CmsRolesType::class, $cmsRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Item Updated!');

            return $this->redirectToRoute('admin_cms_roles_index', ['id' => $cmsRole->getId()]);
        }

        return $this->render('@admin_views/admin/_cms_roles/edit.html.twig', [
            'cms_role' => $cmsRole,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_cms_roles_delete", methods="DELETE")
     */
    public function delete(Request $request, CmsRoles $cmsRole): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cmsRole->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($cmsRole);
            $em->flush();
            $this->addFlash('success', 'Item Deleted!');
        }

        return $this->redirectToRoute('admin_cms_roles_index');
    }
}
