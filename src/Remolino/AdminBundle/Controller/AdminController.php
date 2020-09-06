<?php

namespace App\Remolino\AdminBundle\Controller;

use App\Remolino\CoreBundle\Entity\StudioCategory;
use App\Remolino\CoreBundle\Entity\Project;
use App\Remolino\CoreBundle\Entity\ProjectGallery;
use App\Repository\Remolino\CoreBundle\Entity\ProjectRepository;
use App\Repository\Remolino\CoreBundle\Entity\ProjectGalleryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Remolino\AdminBundle\Form\ProjectType;
use App\Remolino\CoreBundle\Service\AdminService;
use App\Remolino\AdminBundle\Form\StudioCategoryType;
use Jawira\CaseConverter\Convert;

/**
 * @Route("admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/{section}/list", name="list", methods={"GET","POST"})
     */
    public function index(Request $request, $section, AdminService $adminService): Response
    {
		$adminService->start($section);
        if($adminService->RouteisValid()) {
			$config = $adminService->getConfigSection();
			$columns = $adminService->getColumns();
			$dataTable = $adminService->getConfigDataTable();
			$path_view = $adminService->getPathView();
			$actions = $adminService->getActions();
			
			
			$rows = [];
			$formSearchbar = false;
			if($adminService->hasSearchbar()){
				$formSearchbar = $this->createForm( $adminService->getFormClass(), [] )->createView();
			}else{	
				$rows = $adminService->getRowsForTable();
			}

			$folder = new Convert($section);
			
			return $this->render($path_view, [
                'columns' => $columns,
				'datatable'=> $dataTable,
                'entity' => $rows,
                'folder_media' => $folder->fromKebab()->toSnake(),
				'form_search_bar' => $formSearchbar,
                'section' => $section,
				'section_title' => $config["config"]["TITLE"],
				'actions' => $actions,	
            ]);
        }else{
            return $this->redirectToRoute('admin_dashboard');
        }
	}

    /**
     * @Route("/{section}/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request, $section, AdminService $adminService): Response
    {
		$adminService->start($section);
		if($adminService->RouteisValid())
		{
			$config = $adminService->getConfigSection();
			$nameEntity = $adminService->convertPascalCase($section);
			$entityClass = "App\Remolino\CoreBundle\Entity\\" . $nameEntity;
			$formClass = "App\Remolino\AdminBundle\Form\\" . $nameEntity . 'Type';
			
			$entity = new $entityClass();

			if (empty($adminService->getConfigDropzone()) ) {
				$form = $this->createForm($formClass, $entity);
            } else {
				$form = $this->createForm($formClass, $entity, ["configDropzone" => $adminService->getConfigDropzone()]);
			}
			
			$form->handleRequest($request);
			
			if ($form->isSubmitted() && $form->isValid()) {
				if($adminService->hasDimensionChange()){
					$adminService->changeDimensionImage($form->getData());
				}
                
				$em = $this->getDoctrine()->getManager();
				$em->persist($entity);
				$em->flush();
				$this->addFlash('success', 'Item Added!');
				
				return $this->redirectToRoute('list',['section' => $section]);
			}
			$plugins = $adminService->plugins();
			
			$folder = new Convert($section);
			return $this->render('@admin_views/admin/new.html.twig', [
				'section_title' => $config["config"]["TITLE"],
				'section' => $section,
				'form' => $form->createView(),
				'folder_media' => $folder->fromKebab()->toSnake(),
				'gallery' => !empty($adminService->getConfigDropzone()),
				'plugins' => $plugins,
			]);

			return new Response("new");
		}else{
			return $this->redirectToRoute('admin_dashboard');
		}
	}
    /**
     * @Route("/{section}/{id}/show", name="show", methods={"GET"})
     */
    public function show($section, $id, AdminService $adminService): Response
    {
		$em = $this->getDoctrine()->getManager();
		$adminService->start($section);
		if($adminService->RouteisValid())
		{
			$config = $adminService->getConfig($section);
			$nameEntity = $adminService->convertPascalCase($section);
			$entityClass = "App\Remolino\CoreBundle\Entity\\" . $nameEntity;
			
			$find = $em->getRepository("AppRemo:".$nameEntity)->findOneBy([ "id" => $id]);
			if($section == 'home' || $section == 'contact' ){
				$name = $find->getName();	
			}
			if($section == 'texts'){
				$name = $find->getTitleEN();	
			}
			if($section == 'studio_category' || $section == 'studio' || $section == 'project' || $section == 'project_category'){
				$name = $find->getNameEN();	
			}
			
			$titles = $adminService->getCCPropertiesEntity($section);
			$fields = $adminService->getCCPropertiesEntity($section);
			// dd($find , $titles);
			return $this->render('@admin_views/layouts/show_custom.html.twig', [
				'entity' => $find,
				'titles' => $titles,
				'fields' => $fields,
				'section_title' => $config["config"]["TITLE"],
				'section' => $section,
				'name' => $name,
				'images' => ''	,
				'folder_media' => (array_key_exists("PREVIEW_LIST", $config )) ? $config["PREVIEW_LIST"] : '' ,
			]);
			
		}else{
			return $this->redirectToRoute('admin_dashboard');
		}
	}

	/**
     * @Route("/{section}/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, AdminService $adminService, $section, $id): Response
    {
		$adminService->start($section);
		if($adminService->RouteisValid())
		{
			$em = $this->getDoctrine()->getManager();
			$config = $adminService->getConfigSection();
			$nameEntity = $adminService->convertPascalCase($section);
			
			$row = $em->getRepository("AppRemo:".$nameEntity)->findOneBy([ "id" => $id]);
			$formClass = "App\Remolino\AdminBundle\Form\\" . $nameEntity . 'Type';
			if (empty($adminService->getConfigDropzone()) ) {
				$form = $this->createForm($formClass, $row);
            } else {
				$form = $this->createForm($formClass, $row, ["configDropzone" => $adminService->getConfigDropzone()]);
			}
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {
				if($adminService->hasDimensionChange()){
					$adminService->changeDimensionImage($form->getData());
				}

				$this->getDoctrine()->getManager()->flush();
				$this->addFlash('success', 'Item Updated!');

				return $this->redirectToRoute('list',['section' => $section ]);
			}
			$plugins = $adminService->plugins();
			
			$folder = new Convert($section);			
			return $this->render('@admin_views/admin/edit.html.twig', [
				'row' => $row,
				'form' => $form->createView(),
				'section_title' => $config["config"]["TITLE"],
				'section' => $section,
				'id' => $row->getId(),
				'folder_media' => $folder->fromKebab()->toSnake(),
				'gallery' => !empty($adminService->getConfigDropzone()),
				'plugins' => $plugins,
			]);
		}else{
			return $this->redirectToRoute('admin_dashboard');
		}
	}
	
	/**
     * @Route("/{section}/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request,$section,$id , AdminService $adminService): Response
    {
		$adminService->start($section);
		if($adminService->RouteisValid())
		{
			$em = $this->getDoctrine()->getManager();
			$nameEntity = $adminService->convertPascalCase($section);
			$row = $em->getRepository("AppRemo:".$nameEntity)->findOneBy([ "id" => $id]);

			if ($this->isCsrfTokenValid('delete'.$row->getId(), $request->request->get('_token'))) {
				$em = $this->getDoctrine()->getManager();
				$em->remove($row);
				$em->flush();
				$this->addFlash('success', 'Item Deleted!');
			}
			return $this->redirectToRoute('list',['section' => $section]);
		}else{
			return $this->redirectToRoute('admin_dashboard');
		}
    }
}