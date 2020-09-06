<?php

namespace App\Remolino\CoreBundle\Twig;

use App\Remolino\CoreBundle\Entity\Texts;
use App\Repository\Remolino\CoreBundle\Entity\TextsRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Doctrine\Common\Persistence\ObjectManager;

class LangExtension extends AbstractExtension
{
    
    public function __construct(TextsRepository $em )
    {   
        $this->text = $em;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('filter_language', [$this, 'language']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('tt', [$this, 'tt']),
        ];
    }

    public function tt($variable , $type = '')
    {   
        $uppercase_type = ucwords($type); 
        $res_variable = $this->text->findOneBy(['variable' => $variable]);
        
        $getterTitle = 'gettitle';
        $getterText = 'gettext';
        $getterSeoTitle = 'getSeoTitle';
        $getterSeoDesc = 'getSeoDesc';
        if($res_variable != null){
            switch($uppercase_type){
                case 'TITLE':
                    return $res_variable->$getterTitle();
                break;
                case 'TEXT':
                    return $res_variable->$getterText();
                    die;
                break;
                case 'SEO_TITLE':
                    return $res_variable->$getterSeoTitle();
                    die;
                break;
                case 'SEO_DESC':
                    return $res_variable->$getterSeoDesc();
                    die;
                break;
                default:
                    return $res_variable->$getterTitle();
                    die;
                break;
            }
        }else{
            return 'variable:'.$variable.' no found';
        }
    }
}
