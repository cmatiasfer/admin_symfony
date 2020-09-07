<?php

namespace App\Remolino\AdminBundle\Controller;

use App\Repository\Remolino\CoreBundle\Entity\HomeGalleryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Persistence\ObjectManager;
use App\Remolino\CoreBundle\Service\ImageService;
use App\Remolino\CoreBundle\Service\AdminService;
use Jawira\CaseConverter\Convert;

/**
 * @Route("/gallery/{section}")
 */
class GalleryController extends AbstractController
{
	/**
	 * @Route("/upload", name="upload_image")
	 */
	public function uploadImage(Request $request, ImageService $imageService, AdminService $adminService, $section) 
	{
		/* $type = $request->get("type"); */
		$id = $request->get("currentId");
		$file = $request->files->get('file');
		
		$adminService->start($section);
		
		$em = $this->getDoctrine()->getManager();
		$data = [];
		$msg = "";

		$_section = new Convert($section);
		
		try {
			$code = 200;
			$error = false;
			//RESIZE IMAGES
			$fileTemp = $file->getPathname();
			$type = $file->getMimeType();
			if($type != 'video/mp4'){
				$configSection = $adminService->getConfigSection();
				$adminService->modifyImage( $fileTemp, $configSection["config"]["DROPZONE"]["rules_image"] );
			}
			
			$row = $em->getRepository("AppRemo:".$_section->toPascal())->findOneBy([ "id" => $id]);
			
			
			$nameGallery = new Convert($section.'_gallery');
			$countImages = $em->getRepository("AppRemo:".$nameGallery->toPascal())->count([ $_section->toCamel() => $id ]);
			$orderFinal = ($countImages * 100)+100;
			
			$pathEntityGallery = "App\Remolino\CoreBundle\Entity\\" . $nameGallery->toPascal();
			$entityGallery = new $pathEntityGallery();
			$setEntityOneToMany = 'set'.$_section->toPascal();
			$entityGallery->$setEntityOneToMany($row);
			$entityGallery->setMainImageFile($file);
			$entityGallery->setUpdatedAt(new \DateTime());
			$entityGallery->setListingOrder($orderFinal);
			/* $entityGallery->setTypeImage($type); */
			$entityGallery->setVisible(true);
			
			$em->persist($entityGallery);
			$em->flush();
	  
		} catch (Exception $ex) {
			$code = 500;
			$error = true;
			$listError = "An error has occurred trying to set the current form - Error: {$ex->getMessage()}";
			$msg="ERROR SERVER";
		}        
	  
		$response = [
			'code' => $code,
			'error' => $error,
			'data' => $code == 200 ? $data : $listError,
			'msg' => $msg
		];
		
		return new Response(json_encode($response));
	}

	/**
	 * @Route("/show", name="show_images")
	 */
	public function showImages(Request $request, AdminService $adminService, $section)
	{
		$id = $request->get("currentId");
		$em = $this->getDoctrine()->getManager();

		$_section = new Convert($section);
		$nameEntityGallery = new Convert($section.'_gallery');
	  
		$row = $em
			->getRepository("AppRemo:".$_section->toPascal())
			->findOneBy(["id" => $id]);
		$entityGallery = $em
			->getRepository("AppRemo:".$nameEntityGallery->toPascal())
			->findBy([$_section->toCamel() => $row] , ['listingOrder' => "ASC"]);
		$countImages = $em->getRepository("AppRemo:".$nameEntityGallery->toPascal())->count([$_section->toCamel() => $row]);
		$orderFinal = ($countImages * 100)+100;
		$myFilesFinal = array();
	  
	  
		if($countImages >= 0){
			$myFiles= array();
			$myFilesOrder = array();
			$i=0;
			foreach($entityGallery as $key => $value){
				$myFiles[$i]["name"]=$entityGallery[$key]->getMainImage();
				$myFiles[$i]["size"]=200;
				$myFilesOrder[$i]["id"]=$entityGallery[$key]->getId(); 
				$myFilesOrder[$i]["order"]=$entityGallery[$key]->getListingOrder(); 
				$i++;
			}
			$myFilesFinal[0] = $myFiles;
			$myFilesFinal[1] = $myFilesOrder;
		}else{
			$myFilesFinal["status"]=false;
		}
	 
		$myFilesJSON = new JsonResponse($myFilesFinal);
		return $myFilesJSON;
	}
	
	/**
	 * @Route("/delete", name="delete_images")
	 */
	public function deleteImage(Request $request, AdminService $adminService , $section)
	 {
		$em = $this->getDoctrine()->getManager();
		$id = $request->get("currentId");
		$JSONlistingOrder = $request->get("listingOrder");
	  
	
		$nameEntityGallery = new Convert($section.'_gallery');

		
		$rowGallery = $em->getRepository("AppRemo:".$nameEntityGallery->toPascal())->findOneBy(["id" => $id]);
	  
		if(!is_null($rowGallery))
		{
			$em->remove($rowGallery);
			$em->flush($rowGallery);
			$delete["status"] = "OK";
			
			$convertlistingOrder = json_decode($JSONlistingOrder, true);
			
			foreach ($convertlistingOrder as $key => $value) {
				$row = $em->getRepository("AppRemo:".$nameEntityGallery->toPascal())->findOneBy(["id" => $value['id']]);
				
				$row->setListingOrder($value['order']);
				$em->persist($row);
				$em->flush($row); 
			}
		}else{
			$delete["status"] = "ERROR";
		}
		return new JsonResponse($delete);
  	}

  /**
  * @Route("/update_listing_order", name="update_listing_order")
  */
  public function updateListingOrder(Request $request, AdminService $adminService, $section) {
	$em = $this->getDoctrine()->getManager();
	//Get listingOrder as string
	$JSONlistingOrder = $request->get("listingOrder");
	//Convert to array
	$listingOrder = json_decode($JSONlistingOrder, true);
	
	
	$nameEntityGallery = new Convert($section.'_gallery');
	foreach ($listingOrder as $key => $value) {
		$row = $em
			  ->getRepository("AppRemo:".$nameEntityGallery->toPascal())
			  ->findOneBy(["id" => $value['id']]);
		$row->setListingOrder($value['order']);
		$em->persist($row);
		$em->flush($row); 
	}
	$delete["status"] = "OK";
	return new JsonResponse($delete);
  }
  
  /**
   * @Route("/edit_form/{id}", name="form_edit", methods="GET|POST")
   */
  public function formEdit(Request $request, AdminService $adminService, $section, $id): Response
  {
	$em = $this->getDoctrine()->getManager();
	$nameEntityGallery = $adminService->convertPascalCase($section);
	
	$find = $em->getRepository("AppRemo:".$nameEntityGallery."Gallery")->findOneBy([ "id" => $id]);
	$entityGalleryClass = "App\Remolino\CoreBundle\Entity\\" . $nameEntityGallery."Gallery";
	$entityGallery = new $entityGalleryClass();
	
	$formClass = "App\Remolino\AdminBundle\Form\\" . $nameEntityGallery . 'GalleryType';
	
	$form = $this->createForm($formClass, $find);
	$form->handleRequest($request);

	if ($form->isSubmitted() && $form->isValid()) {
		$this->getDoctrine()->getManager()->flush();
		return new Response('close');
	}

	return $this->render('@admin_views/layouts/ajax_forms/ajax_edit.html.twig', [
		'entity' => $find,
		'form' => $form->createView(),
		'section' => $section
	]);
  }
}