<?php

namespace App\Remolino\CoreBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Yaml\Yaml;
use App\Remolino\CoreBundle\Service\ImageService;
use Jawira\CaseConverter\Convert;
use \Gumlet\ImageResize;

class AdminService
{
    private $em;
    private $imageService;

    public function __construct(EntityManagerInterface $em, ImageService $imageService)
    {
        $this->em = $em;
        $this->pathConfigAdmin = dirname(__DIR__).'/Resources/admin.yaml';
        $this->configAdmin = Yaml::parseFile($this->pathConfigAdmin);
        $this->section = "";
        $this->configSection = [];
        $this->categoryTree = array();
        $this->imageService = $imageService;
    }

    function start($section){
        $this->setSection($section);
        $configSection = [];
        if(isset($this->configAdmin["admin"]["entities"][$section])){
            $configSection = $this->configAdmin["admin"]["entities"][$section];
        }
        $this->setConfigSection($configSection);
    }

    function setSection($section){
        $this->section = $section;
    }
    
    /**
     * @return Array
     */
    function getSection(){
        return $this->section;
    }
    
    function setConfigSection($configSection){
        $this->configSection = $configSection;
    }

    /**
     * @return Array
     */
    function getConfigSection(){
        return $this->configSection;
    }

    /**
     * @return Array
     */
    function getFileAdmin()
    {
        return $this->configAdmin;

    }
    
    /**
     * @return String
     */
    function getNameAdmin()
    {
        return $this->configAdmin["admin"]["name"];
    }

    /**
     * Busqueda del parametro $section en la lista "Route" de archivo admin.yaml.
     *
     * @return boolean
     */
    function RouteisValid()
    {
        $isValid = false;
        foreach ($this->configAdmin["admin"]["entities"] as $nameSection => $configSection) {
            if ($nameSection == $this->section) {
                $isValid = true;
            }
        }

        return $isValid;
    }

    /**
     *
     *
     * @return string
     */
    function convertPascalCase($text)
    {
        $newText = str_replace('_', '', ucwords($text, '_'));
        return $newText;
    }
    

    function getCCPropertiesEntity($section)
    {
        $nameEntity = $this->convertPascalCase($section);
        $listProperties = $this->em->getClassMetadata("AppRemo:".$nameEntity)->fieldNames;
        $propertiesCamelCase = [];
        $count = 0;
        foreach($listProperties as $SCProperties => $CCProperties ){
            if( $SCProperties != "updated_at" ){
                $propertiesCamelCase[$count] = $CCProperties;
                $count++;
            }
        }
        return $propertiesCamelCase;
    }

    /**
     * Retorna existencia de key list y search_bar en admin.yaml
     *
     * @return boolean
     */
    function hasSearchbar()
    {
        $formClass = $this->getFormClass();
        return (is_null($formClass))? false : true ;
    }


    function dataForm($section)
    {
        $filters = [];
        foreach($this->configAdmin as $key => $configType){
            if(( array_key_exists("FILTER", $this->getConfigSection($section)) )){
                $filters = $configType['entities'][$section]['config']['FILTER']['FIELDS_INITIAL'];
            }
        }
        return $filters;
    }

    function configForm($section)
    {
        $config = [];
        $sectionConfig = $this->getConfigSection($section);
        foreach($this->configAdmin as $key => $configType){
            if(( array_key_exists("FIELDS_CONFIG", $sectionConfig['FILTER']) )){
            $config = $configType['entities'][$section]['config']['FILTER']['FIELDS_CONFIG'];
            }
        }
        return $config;
    }

    function getPropertiesString($section)
    {
        $nameEntity = $this->convertPascalCase($section);
        $listProperties = $this->em->getClassMetadata("AppRemo:".$nameEntity)->fieldMappings;
        $fieldsTypeString = [];

        foreach($listProperties as $property => $listData){
            if ($property != 'id') {
                if ($listData["type"] == "string" || $listData["type"] == "text") {
                    array_push($fieldsTypeString, 't.'.$listData['fieldName']);
                }
            }
        }
        return $fieldsTypeString;
    }

    function filter($section, $parameters)
    {
        $nameEntity = $this->convertPascalCase($section);
        $find = $this->em->getRepository("AppRemo:".$nameEntity)->createQueryBuilder('t');
        if( array_key_exists("keyword", $parameters) ){
            $listPropertiesString = $this->getPropertiesString($section);

            foreach($listPropertiesString as $key => $value ){
                $find->orWhere($value." LIKE :word");
            }

            $find->setParameter('word', '%'.$parameters['keyword'].'%');
        }

        if( array_key_exists("visible", $parameters) &&  $parameters['visible'] ){
            $find->andWhere("t.visible = ' ".$parameters['visible']." ' " );
        }

        if( array_key_exists("inHome", $parameters) &&  $parameters['inHome'] ){
            $find->andWhere("t.inHome = ' ".$parameters['inHome']." ' " );
        }

        if( array_key_exists("category", $parameters) && !is_null($parameters['category']) ){
            $category = $parameters['category'];
            $idCategory = $category->getId();
            $find->andWhere("t.category = ' ".$idCategory." ' " );
        }

        $result = $find->getQuery()->execute();

        return $result;
    }

    function getTreeItems($tree,$levels,$level=0)
    {
        $selectOptions = array();
        //Recorro los items de este nivel
        foreach ($tree as $treeElement){
            //Guardo el nivel actual al array de niveles
            $levels[$level] = $treeElement['name'];
            if(count($levels)>1){
                //Obtengo el item con sus padres
                $selectOptions[] = array('id'=>$treeElement['id'],'name'=>implode(' / ',$levels));
            }else{
                //Obtengo el item solo (1 nivel)
                $selectOptions[] = array('id'=>$treeElement['id'],'name'=>$treeElement['name']);
            }
            //Compruebo si tiene hijos
            if(isset($treeElement['children'])){
                //Subo un nivel
                $level++;
                //Vuelvo a llamarme a mi mismo, pero con los hijos
                $hijos = $this->getTreeItems($treeElement['children'],$levels,$level);
                //Le sumo los hijos a los items existentes del "select"
                $selectOptions = array_merge($selectOptions,$hijos);
                //Una vez obtenidos los hijos, reseteo nivel
                unset($levels[$level]);
                $level--;
            }
        }
        // Devuelvo los items para el select
        return $selectOptions;
    }

    function getTreeItemsAutocomplete($tree,$levels,$level=0)
    {
        $selectOptions = array();
        //Recorro los items de este nivel
        foreach ($tree as $treeElement){
            //Guardo el nivel actual al array de niveles
            $levels[$level] = $treeElement['value'];
            if(count($levels)>1){
                //Obtengo el item con sus padres
                $selectOptions[] = array('data'=>$treeElement['data'],'value'=>implode(' / ',$levels));
            }else{
                //Obtengo el item solo (1 nivel)
                $selectOptions[] = array('data'=>$treeElement['data'],'value'=>$treeElement['value']);
            }
            //Compruebo si tiene hijos
            if(isset($treeElement['children'])){
                //Subo un nivel
                $level++;
                //Vuelvo a llamarme a mi mismo, pero con los hijos
                $hijos = $this->getTreeItemsAutocomplete($treeElement['children'],$levels,$level);
                //Le sumo los hijos a los items existentes del "select"
                $selectOptions = array_merge($selectOptions,$hijos);
                //Una vez obtenidos los hijos, reseteo nivel
                unset($levels[$level]);
                $level--;
            }
        }
        // Devuelvo los items para el select
        return $selectOptions;
    }

    function getCategoryTree($category)
    {
        $category = $this->em->getRepository('AppRemo:Categories')->findOneBy(['id' => $category]);

        array_push($this->categoryTree,$category);

        if ($category->getParent()) {
            $parent = $this->em->getRepository('AppRemo:Categories')->findOneBy(['id' => $category->getParent()]);
            $this->getCategoryTree($parent->getId());
        }

        return $this->categoryTree;
    }

    function getTreeItemsNavbar($tree,$levels,$level=0)
    {
        $selectOptions = array();
        //Recorro los items de este nivel
        foreach ($tree as $treeElement){
            //Guardo el nivel actual al array de niveles
            $levels[$level] = $treeElement['seo_url'];
            if (count($levels)>1) {
                //Obtengo el item con sus padres
                $selectOptions[] = array('id'=>$treeElement['id'],'parent_id'=>$treeElement['parent_id'],'name'=>$treeElement['name'], 'main_image'=>$treeElement['main_image'],'seo_url'=>implode('/',$levels));
            } else {
                //Obtengo el item solo (1 nivel)
                $selectOptions[] = array('id'=>$treeElement['id'],'parent_id'=>$treeElement['parent_id'],'name'=>$treeElement['name'], 'main_image'=>$treeElement['main_image'],'seo_url'=>$treeElement['seo_url']);
            }
            //Compruebo si tiene hijos
            if(isset($treeElement['children'])){
                //Subo un nivel
                $level++;
                //Vuelvo a llamarme a mi mismo, pero con los hijos
                $hijos = $this->getTreeItemsNavbar($treeElement['children'],$levels,$level);
                //Le sumo los hijos a los items existentes del "select"
                $selectOptions = array_merge($selectOptions,$hijos);
                //Una vez obtenidos los hijos, reseteo nivel
                unset($levels[$level]);
                $level--;
            }
        }
        //Devuelvo los items para el select
        return $selectOptions;
    }

    function buildTree(array $elements, $parentId = 0)
    {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }

    function buildTreeAutocomplete(array $elements, $parentId = 0)
    {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTreeAutocomplete($elements, $element['data']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }

    function filterTree($tree, $levels = [], $level = 0){
        foreach($tree as $treeElement){
            $levels[$level] = $treeElement['name'];
        }
        return $level;
    }

    function getConfigDropzone()
    {
        $listFields = [];
        if(isset($this->configSection['config']['DROPZONE'])){
            $listFields = $this->configSection['config']['DROPZONE'];
        }
        return $listFields;
    }

    function eliminarTildes($cadena, $encode=true)
    {
        //Codificamos la cadena en formato utf8 en caso de que nos de errores
        if($encode){
        $cadena = utf8_encode($cadena);
        }

        //Ahora reemplazamos las letras
        $cadena = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $cadena
        );

        $cadena = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $cadena );

        $cadena = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $cadena );

        $cadena = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $cadena );

        $cadena = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $cadena );

        $cadena = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C'),
            $cadena
        );

        return $cadena;
    }

    function getFormClass(){
        $entityClass = null;
        
        if(isset($this->configSection["list"]["search_bar"]) && $this->configSection["list"]["search_bar"] == true){
            $section = new Convert($this->section."_type");
            $entityClass = "App\Remolino\AdminBundle\Form\Searchbar\\" . $section->toPascal();
        }
        return $entityClass;
    }

    function getSearchBarFields(){
        $fields = $this->configSection["list"]["list"]["search_bar"]["fields"];
        return $fields;
    }
    
    function getOrderBy(){
        $orderBy = [];
        if(isset($this->configSection["list"]["order_by"])){
            $orderBy = $this->configSection["list"]["order_by"];
        }
        return $orderBy;
    }
    
    function getColumns(){
        $columns = [];
        if(isset($this->configSection["list"]["columns"])){
            $columns = $this->configSection["list"]["columns"];
        }
        return $columns;
    }

    function getPageLength(){
        $pageLength = 20;
        if(isset($this->configSection["list"]["page_length"])){
            $pageLength = $this->configSection["list"]["page_length"];
        }
        return $pageLength;
    }
    
    function getPathView(){
        $path = '@admin_views/layouts/index.html.twig';
        if($this->hasSearchbar()){
            $path = '@admin_views/layouts/index_ajax.html.twig';
        }else{
            if(isset($this->configSection["list"]["path_view"])){
                $path = $this->configSection["list"]["path_view"];
            }
        }
        return $path;
    }
    
    /**
     * 
     *
     * @return Array
     */
    function getRowsForTable()
    {
        $nameEntity = $this->convertPascalCase($this->section);
        $orderBy = $this->getOrderby();
            
        $rows = $this->em->getRepository("AppRemo:".$nameEntity)->findBy([], $orderBy);
        return $rows;
    }
    
    /**
     * 
     *
     * @return Array
     */
    function getConfigDataTable()
    {   
        $data["searching"] = ($this->hasSearchbar())? false : true;
        $data["order"] = (count($this->getOrderby()) > 0)? true : false;
        $data["jsonColumn"] = $this->convertToJsonDatatable($this->getColumns());;
        $data["url"] = "/admin/datatable";
        $data["length"] = $this->getPageLength();
        return $data;
    }
    
    /**
     * 
     * @return Array
     */
    function convertToJsonDatatable($columns)
    {
        $data = [];
        $check["title"] = ""; 
        $check["data"] = "colCheck";
        array_push($data,$check);
        
        foreach($columns as $title => $field){
            $datableArray["title"] = $title;
            $datableArray["data"] = $field;
            array_push($data,$datableArray);
        }
        //getMode
        $actions["title"] = "Acciones"; 
        $actions["data"] = "actions";
        
        array_push($data, $actions);
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    function getActions(){
        $actions["add"] = true;
        $actions["edit"] = true;
        $actions["delete"] = true;
        /* $actions["actions"]["preview"] = true; */
        if(isset($this->configSection["list"]["actions"]["add"])){
            $actions["add"] = $this->configSection["list"]["actions"]["add"];
        }
        if(isset($this->configSection["list"]["actions"]["edit"])){
            $actions["edit"] = $this->configSection["list"]["actions"]["edit"];
        }
        if(isset($this->configSection["list"]["actions"]["delete"])){
            $actions["delete"] = $this->configSection["list"]["actions"]["delete"];
        }
        /* if(isset($this->configSection["actions"]["preview"])){
            $actions["actions"]["preview"] = $this->configSection["actions"]["preview"];
        } */
        return $actions;
    }
    
    /**
     * Returns base64 encoded string prepended by "data:image/"
     *
     * @param $filename string
     * @param $filetype string 
     * @return string
     */
    function base64_encode_image($filename=string, $filetype=string)
    {
        if ($filename) {
            $imgbinary = fread(fopen($filename, "r"), filesize($filename));
                return 'data:image/' . $filetype 
                        . ';base64,' . base64_encode($imgbinary);
        }
    }

    /**
     * Retorno de campos de tipo imagen
     *
     * @return boolean 
     */
    function hasDimensionChange(){
        $hasDimensionChange = false;
        if(isset($this->configSection['config']['images_upload'])){
            $hasDimensionChange = true;
        }
        return $hasDimensionChange;
    }

    function changeDimensionImage($data){
        $listImagesUpload = $this->configSection["config"]["images_upload"];
        
        foreach($listImagesUpload as $field => $rules){
            $getter = 'get'.ucfirst($field.'File');
            $image = $data->$getter();
            
            if(isset($image)){
                if(isset($rules["exceptions"])){
                    $rulesByException = $this->findRules($rules["exceptions"],$data); 
                    if(!empty($rulesByException)){
                        $rules = $rulesByException;
                    }
                }
                $this->modifyImage($image->getPathname(), $rules);
            }
        }
    }
    /**
     * @return array 
     */
    function findRules($exceptions, $formData){
        $rules= [];
        foreach($exceptions as $field => $values){
            $getter = 'get'.ucfirst($field);
            $formValue = $formData->$getter();
            foreach($values as $exceptionValue => $_rules){
                if($formValue == $exceptionValue){
                    $rules = $_rules;
                }
            }
        }
        return $rules;
    }

    function modifyImage($pathImage,$rules)
    {
        $dimensions = $rules["dimensions"];
        $gumlet = new ImageResize($pathImage);
        
        $gumlet->quality_jpg = 100;
        foreach($rules['mode']  as $mode){
            if($mode == 'resizeToWidth'){
                $gumlet->resizeToWidth($dimensions["width"]);
            }
            if($mode == 'crop'){
                $gumlet->crop($dimensions["width"],$dimensions["height"]);
            }
        }
        $gumlet->save($pathImage);
    }

    /**
     * @return Array
     */
    function plugins()
    {   
        $statusPlugin["cropper"] = true;
        $statusPlugin["dropzone"] = true;
        $statusPlugin["ckeditor"] = true;
        $statusPlugin["gmap"] = false;
        $hasPlugins = isset($this->configSection["plugins"]);
        if($hasPlugins){
            $plugins = $this->configSection["plugins"];
            
            if(isset($plugins["cropper"])){
                $statusPlugin["cropper"] = $plugins["cropper"];
            }
            if(isset($plugins["dropzone"])){
                $statusPlugin["dropzone"] = $plugins["dropzone"];
            }
            if(isset($plugins["ckeditor"])){
                $statusPlugin["ckeditor"] = $plugins["ckeditor"];
            }
            if(isset($plugins["gmap"])){
                $statusPlugin["gmap"] = $plugins["gmap"];
            }
        }
        return $statusPlugin;
    }

    function pathGallery(){
        
    } 
}