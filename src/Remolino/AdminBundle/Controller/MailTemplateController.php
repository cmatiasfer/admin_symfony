<?php

namespace App\Remolino\AdminBundle\Controller;

use App\Remolino\CoreBundle\Entity\MailTemplate;
//use App\Remolino\CoreBundle\Entity\HomeGallery;
use App\Repository\Remolino\CoreBundle\Entity\MailTemplateRepository;
use App\Remolino\AdminBundle\Form\MailTemplateType;
//use App\Repository\Remolino\CoreBundle\Entity\HomeGalleryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("admin/mail_template")
 */
class MailTemplateController extends AbstractController
{

    /**
     * @Route("/", name="mail_template_index", methods={"GET"})
     */
    public function index(MailTemplateRepository $mailTemplateRepository): Response
    {
        $titles = array("Template","Send Type","For","Subject");
        $fields = array("nameTemplate","typeSend","mailTo","subject");
        $filters = array();
        return $this->render('@admin_views/layouts/index.html.twig', [
            'entity' => $mailTemplateRepository->findAll(),
            'route' => 'mail_template_',
            'section_title' => 'Plantillas Email',
            'section' => 'mail_template',
            'titles' => $titles,
            'fields' => $fields,
            'filters' => $filters
        ]);
    }

    /**
     * @Route("/new", name="mail_template_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {

        $mailTemplate = new MailTemplate();
        $form = $this->createForm(MailTemplateType::class, $mailTemplate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mailTemplate);
            $entityManager->flush();

            return $this->redirectToRoute('mail_template_index');
        }

        return $this->render('@admin_views/layouts/new.html.twig', [
            'entity' => $mailTemplate,
            'route' => 'mail_template_',
            'section' => 'mail_template',
            'section_title' => 'Plantillas Email',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="mail_template_show", methods={"GET"})
     */
    public function show(MailTemplate $mailTemplate): Response
    {
        $nameTemplate = $mailTemplate->getNameTemplate();
        $titles = array("Name Template");
        $fields = array("nameTemplate");


        //$row = $homeRepository->findOneBy(['id' => 1]);
        // $resGallery = $homeGalleryRepository->findBy(['home' => $row],['listingOrder' => "ASC"]);

        return $this->render('@admin_views/layouts/show.html.twig', [
            'entity' => $mailTemplate,
            'titles' => $titles,
            'fields' => $fields,
            'route' => 'mail_template_',
            'section_title' => 'Plantillas Email',
            'name' => $nameTemplate,
            'images' => ""
        ]);
    }

    /**
     * @Route("/{id}/edit", name="mail_template_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MailTemplate $mailTemplate): Response
    {
        $form = $this->createForm(MailTemplateType::class, $mailTemplate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mail_template_index', [
                'id' => $mailTemplate->getId(),

            ]);
        }

        return $this->render('@admin_views/layouts/edit.html.twig', [
            'entity' => $mailTemplate,
            'form' => $form->createView(),
            'route' => 'mail_template_',
            'section_title' => 'Mail Templates',
            'section' => 'mail_template',
            'folder' => '',
            'id' => $mailTemplate->getId()
        ]);
    }

    /**
     * @Route("/{id}", name="mail_template_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MailTemplate $mailTemplate): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mailTemplate->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mailTemplate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mail_template_index');
    }
}
