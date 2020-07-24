<?php
namespace App\Controller;

use Da\TwoFA\Manager;
use Da\TwoFA\Service\TOTPSecretKeyUriGeneratorService;
use Da\TwoFA\Service\QrCodeDataUriGeneratorService;

class TWOFASecurityController
{
    private $secretKey;
    private $companyName = "TEST_GHOSTLEXLY";
    public function __construct(string $secretKey = "")
    {
        $this->secretKey = $secretKey;
    }

    /**
     * Generate a QR Code to scan with an app.
     * @param $email
     * @return string
     * @return => The URL to the QR Code image.
     */
    public function generateQRCode($email)
    {
        $uriGenerator = (new TOTPSecretKeyUriGeneratorService($this->companyName, $email, $this->secretKey))->run();
        $uri = (new QrCodeDataUriGeneratorService($uriGenerator))->run();

        dump("SECRET KEY IS: $this->secretKey");

        return $uri;
    }

    /**
     * Verify if the code is valid.
     * @param $code
     * @return bool|int
     * @throws \Da\TwoFA\Exception\InvalidCharactersException
     * @throws \Da\TwoFA\Exception\InvalidSecretKeyException
     */
    public function verifyCode($code)
    {
        $manager = new Manager();
        return $manager->verify($code, $this->secretKey);
    }

    /**
     * Generate a secret key for each user.
     * @return mixed|string
     * @throws \Da\TwoFA\Exception\InvalidSecretKeyException
     */
    public static function generateSecretKey()
    {
        $manager = new Manager();
        return $manager->generateSecretKey();
    }
}