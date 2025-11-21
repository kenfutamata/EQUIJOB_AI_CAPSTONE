<?php

namespace App\Exceptions;

/**
 * Custom exception thrown when the name on a certificate
 * does not match the applicant's name.
 */
class CertificateNameMismatchException extends \Exception
{
    // This class doesn't need any special methods.
    // It just needs to exist so it can be "caught" by the controller.
}