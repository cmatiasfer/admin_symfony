<?php

namespace App\Remolino\AdminBundle\Controller;

use App\Remolino\CoreBundle\Entity\CmsUser;
use App\Remolino\CoreBundle\Entity\Profile;
use App\Remolino\AdminBundle\Form\CmsUserType;
use App\Remolino\AdminBundle\Form\CmsUserEditType;
use App\Remolino\AdminBundle\Form\CmsUserPasswordType;
use App\Repository\Remolino\CoreBundle\Entity\CmsUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/admin/core/users")
 */
class CmsUserController extends AbstractController
{
    /**
     * @Route("/", name="admin_cms_user_index", methods="GET")
     */
    public function index(CmsUserRepository $CmsUserRepository): Response
    {
        return $this->render('@admin_views/admin/_cms_user/index.html.twig', ['cms_users' => $CmsUserRepository->findAll()]);
    }

    /**
     * @Route("/new", name="admin_cms_user_new", methods="GET|POST")
     */
    public function new(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $cmsUser = new CmsUser();
        $form = $this->createForm(CmsUserType::class, $cmsUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $cmsUser->setCreatedAt('');
            $password = $passwordEncoder->encodePassword($cmsUser, $cmsUser->getPassword());
            $cmsUser->setPassword($password);
            $em->persist($cmsUser);
            $em->flush();
            $this->addFlash('success', 'User Added!');

            // Set User Profile
            $profile = new Profile();
            $profile->setFirstname(' ');
            $profile->setLastname(' ');
            $profile->setEmail(' ');
            $manager->persist($profile);
            $manager->flush();
            $cmsUser->setProfile($profile->getId());
            $profile->setUser($cmsUser->getId());
            $manager->persist($cmsUser);
            $manager->persist($profile);
            $manager->flush();

            return $this->redirectToRoute('admin_cms_user_index');
        }

        return $this->render('@admin_views/admin/_cms_user/new.html.twig', [
            'cms_user' => $cmsUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_cms_user_show", methods="GET")
     */
    public function show(CmsUser $cmsUser): Response
    {
        return $this->render('@admin_views/admin/_cms_user/show.html.twig', ['cms_user' => $cmsUser]);
    }

    /**
     * @Route("/{id}/edit", name="admin_cms_user_edit", methods="GET|POST")
     */
    public function edit(Request $request, CmsUser $cmsUser, UserPasswordEncoderInterface $passwordEncoder, Security $security): Response
    {
        $connectedUser = $security->getUser();

        if ($connectedUser->getId() == $cmsUser->getId() || !in_array("ROLE_ADMIN", $cmsUser->getRoles())) {
            $form = $this->createForm(CmsUserEditType::class, $cmsUser);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'User Updated!');

                return $this->redirectToRoute('admin_cms_user_index', ['id' => $cmsUser->getId()]);
            }

            return $this->render('@admin_views/admin/_cms_user/edit.html.twig', [
                'cms_user' => $cmsUser,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('admin_cms_user_index');
        }
    }

    /**
     * @Route("/{id}/edit-password", name="admin_cms_user_edit_password", methods="GET|POST")
     */
    public function editPassword(Request $request, CmsUser $cmsUser, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(CmsUserPasswordType::class, $cmsUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($cmsUser, $cmsUser->getPassword());
            $cmsUser->setPassword($password);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Password Updated!');

            return $this->redirectToRoute('admin_cms_user_index', ['id' => $cmsUser->getId()]);
        }

        return $this->render('@admin_views/admin/_cms_user/edit_password.html.twig', [
            'cms_user' => $cmsUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_cms_user_delete", methods="DELETE")
     */
    public function delete(Request $request, CmsUser $cmsUser): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cmsUser->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($cmsUser);
            $em->flush();
            $this->addFlash('success', 'User Deleted!');
        }

        return $this->redirectToRoute('admin_cms_user_index');
    }
}
