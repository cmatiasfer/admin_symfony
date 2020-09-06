<?php

namespace App\Remolino\AdminBundle\Controller;

use App\Remolino\CoreBundle\Entity\Profile;
use App\Remolino\AdminBundle\Form\ProfileType;
use App\Remolino\AdminBundle\Form\CmsUserPasswordType;
use App\Repository\Remolino\CoreBundle\Entity\ProfileRepository;
use App\Repository\Remolino\CoreBundle\Entity\CmsUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/admin/core/profile")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/{id}", name="admin_profile_show", methods={"GET"})
     */
    public function show($id, ProfileRepository $profileRepository): Response
    {
        $profile = $profileRepository->findOneBy(['id' => $id]);

        return $this->render('@admin_views/admin/profile/show.html.twig', [
            'profile' => $profile
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_profile_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Profile $profile): Response
    {
        $form = $this->createForm(ProfileType::class, $profile);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Profile Updated!');

            return $this->redirectToRoute('admin_profile_show', [
                'id' => $profile->getId(),
            ]);
        }

        return $this->render('@admin_views/admin/profile/edit.html.twig', [
            'profile' => $profile,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit-password", name="admin_profile_edit_password", methods="GET|POST")
     */
    public function editPassword(Request $request, Profile $profile, UserPasswordEncoderInterface $passwordEncoder, ObjectManager $manager, CmsUserRepository $cmsUserRepository, Security $security): Response
    {
        $user = $cmsUserRepository->findOneBy(['id'=>$profile->getUser()]);
        $connectedUser = $security->getUser();

        if ($user->getId() == $connectedUser->getId()) {

            $form = $this->createForm(CmsUserPasswordType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $password = $passwordEncoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);
                $manager->persist($user);
                $manager->flush();
                $this->addFlash('success', 'Password Updated!');

                return $this->redirectToRoute('admin_profile_show', [
                    'id' => $profile->getId(),
                ]);
            }

            return $this->render('@admin_views/admin/profile/edit_password.html.twig', [
                'cms_user' => $user,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('admin_profile_show', [
                'id' => $profile->getId(),
            ]);
        }
    }

    /* User Profile Data */
    public function getHeaderProfile(ProfileRepository $profileRepository, $user)
    {
        $profile = $profileRepository->findOneBy(["user" => $user]);
        return $this->render('@admin_views/panel/profile_header.html.twig', ['profile' =>  $profile]);
    }

    public function getSidebarProfile(ProfileRepository $profileRepository, $user)
    {
        $profile = $profileRepository->findOneBy(["user" => $user]);

        return $this->render('@admin_views/panel/profile_sidebar.html.twig', ['profile' =>  $profile]);
    }
}