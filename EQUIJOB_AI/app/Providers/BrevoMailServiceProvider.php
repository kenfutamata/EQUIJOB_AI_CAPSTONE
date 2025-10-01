<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mime\RawMessage;
use Symfony\Component\Mime\Email;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client;

class BrevoMailServiceProvider extends ServiceProvider
{
    public function register() {}

    public function boot()
    {
        Mail::extend('brevo', function () {
            $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', env('BREVO_API_KEY'));
            $apiInstance = new TransactionalEmailsApi(new Client(), $config);

            return new class($apiInstance) implements TransportInterface {
                protected $apiInstance;

                public function __construct($apiInstance)
                {
                    $this->apiInstance = $apiInstance;
                }

                public function send(RawMessage $message, Envelope $envelope = null): ?SentMessage
                {
                    // Convert RawMessage to Email
                    if ($message instanceof Email) {
                        $symfonyEmail = $message;
                    } else {
                        throw new \InvalidArgumentException('Brevo transport only supports Email messages.');
                    }

                    $emailData = new SendSmtpEmail([
                        'sender' => [
                            'email' => env('MAIL_FROM_ADDRESS'),
                            'name' => env('MAIL_FROM_NAME')
                        ],
                        'to' => array_map(fn($addr) => [
                            'email' => $addr->getAddress(),
                            'name'  => $addr->getName()
                        ], $symfonyEmail->getTo()),
                        'subject' => $symfonyEmail->getSubject(),
                        'htmlContent' => $symfonyEmail->getHtmlBody() ?? $symfonyEmail->getTextBody(),
                    ]);

                    $this->apiInstance->sendTransacEmail($emailData);

                    // Return a SentMessage to satisfy Symfony/Laravel
                    return new SentMessage(
                        $symfonyEmail,
                        $envelope ?? Envelope::create($symfonyEmail)
                    );
                }

                public function __toString(): string
                {
                    return 'brevo-api';
                }
            };
        });
    }
}
