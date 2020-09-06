<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Yaml\Yaml;

class ResponseFormService 
{
  private $em;

  public function __construct(EntityManagerInterface $em)
  {
      $this->em = $em;
      // $this->yaml = $yaml;
  }

  function getVariables(){
    $formResponse = $this->em->getRepository("AppRemo:Text")->findOneBy(["variable" => "WEB_EMAIL_RESPONSE_OK"]);
    $formError = $this->em->getRepository("AppRemo:Text")->findOneBy(["variable" => "WEB_EMAIL_RESPONSE_ERROR"]);
    $formSubject = $this->em->getRepository("AppRemo:Text")->findOneBy(["variable" => "WEB_EMAIL_SUBJECT"]);
    $formEmailTo = $this->em->getRepository("AppRemo:Text")->findOneBy(["variable" => "WEB_EMAIL_SEND_TO"]);

    $code = 200;
    if (is_null($formResponse)) {
      $code = 500;
      $error = true;
      $listError[] = "variable WEB_EMAIL_RESPONSE_OK not found";
      $msg="ERROR SERVER";
    }
    
    if (is_null($formError)) {
      $code = 500;
      $error = true;
      $listError[] = "variable WEB_EMAIL_RESPONSE_ERROR not found";
      $msg="ERROR SERVER";
    }
    
    if (is_null($formSubject)) {
        $code = 500;
        $error = true;
        $listError[] = "variable WEB_EMAIL_SUBJECT not found";
        $msg="ERROR SERVER";
    }

    if (is_null($formEmailTo)) {
        $code = 500;
        $error = true;
        $listError[] = "variable WEB_EMAIL_SEND_TO not found";
        $msg="ERROR SERVER";
    }
    
    $response = [
      'code' => $code,
      'error' => $error,
      'data' => $code == 200 ? $data : $listError,
      'msg' => $msg
    ];

    return $response;
  }
}
