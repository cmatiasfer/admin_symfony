<?php

namespace App\Remolino\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
// Responses
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Persistence\ObjectManager;
// Include PhpSpreadsheet required namespaces
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// Use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Csv as ReaderCSV;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;
// Entity
use App\Remolino\CoreBundle\Entity\Contact;
// Repository
use App\Repository\Remolino\CoreBundle\Entity\TextsRepository;

class ContactController extends AbstractController
{
    /**
     * @Route({
     *          "es" : "/contact",
     *          "jp" : "/contact",
     *          "en" : "/contact",
     *        },
     *        name="contact",
     *        defaults={"_locale"="en"},
     *        requirements={
     *            "_locale"="en|es|jp"
     *         })
     */
    public function index($_locale)
    {
        return $this->render('@front_views/contact/contact.html.twig', [
            'lang_url' => $_locale,
        ]);
    }
    /**
     * @Route({
     *          "es" : "/contact/email",
     *          "jp" : "/contact/email",
     *          "en" : "/contact/email",
     *        },
     *        name="contact_email",
     *        defaults={"_locale"="en"},
     *        requirements={
     *            "_locale"="en|es|jp"
     *         })
     */
    public function ajaxAction(Request $request , \Swift_Mailer $mailer , ObjectManager $manager) {
        date_default_timezone_set('America/Argentina/Buenos_Aires');
       
        return $nuevo;
    }

}
