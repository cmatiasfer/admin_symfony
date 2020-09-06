<?php 
namespace App\Service;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;


class YTService {
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public function addEmbed($linkYT){
        $linkEmbed = str_replace("watch?v=" , "embed/" ,$linkYT);

        return $linkEmbed;
    }
    
}