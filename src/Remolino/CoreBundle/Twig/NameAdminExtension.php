<?php

namespace App\Remolino\CoreBundle\Twig;

use App\Remolino\CoreBundle\Entity\Texts;
use App\Remolino\CoreBundle\Service\AdminService;
use App\Repository\Remolino\CoreBundle\Entity\TextsRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Doctrine\Common\Persistence\ObjectManager;

class NameAdminExtension extends AbstractExtension
{
    
    public function __construct(AdminService $adminService )
    {   
        $this->adminService = $adminService;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('filter_language', [$this, 'name_admin']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('name_admin', [$this, 'name_admin']),
        ];
    }

    public function name_admin()
    {
        return $this->adminService->getNameAdmin();
    }
}
