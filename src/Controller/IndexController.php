<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="welcomepage")
     */
    public function index()
    {
        $secretKey = TWOFASecurityController::generateSecretKey(); // It's different for each user. Put it in DB.
        $TWOFASecurity = new TWOFASecurityController($secretKey);
        $TWOFAQrCode = $TWOFASecurity->generateQRCode("test@gmail.com");

        return $this->render("/welcomepage/index.html.twig", ["qrcode_imgurl" => $TWOFAQrCode]);
    }

    /**
     * @Route("/verify/{secretKey}/{code}", name="verifycode")
     * @param $secretKey
     * @param $code
     * @throws \Da\TwoFA\Exception\InvalidCharactersException
     * @throws \Da\TwoFA\Exception\InvalidSecretKeyException
     */
    public function verifyCode($secretKey, $code)
    {
        $TWOFASecurity = new TWOFASecurityController($secretKey);
        dump($TWOFASecurity->verifyCode($code));
        exit();
    }
}