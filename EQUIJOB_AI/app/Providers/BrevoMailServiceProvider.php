<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Mail\MailManager;
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
        $this->app->resolving(MailManager::class, function (MailManager $mailManager) {
            $mailManager->extend('brevo', function () {
                $config = \SendinBlue\Client\Configuration::getDefaultConfiguration()
                    ->setApiKey('api-key', env('BREVO_API_KEY'));
                $apiInstance = new \SendinBlue\Client\Api\TransactionalEmailsApi(new \GuzzleHttp\Client(), $config);

                return new class($apiInstance) implements \Symfony\Component\Mailer\Transport\TransportInterface {
                    protected $apiInstance;

                    public function __construct($apiInstance)
                    {
                        $this->apiInstance = $apiInstance;
                    }

                    public function send(\Symfony\Component\Mime\RawMessage $message, \Symfony\Component\Mailer\Envelope $envelope = null): ?\Symfony\Component\Mailer\SentMessage
                    {
                        if (!($message instanceof \Symfony\Component\Mime\Email)) {
                            throw new \InvalidArgumentException('Brevo transport only supports Email messages.');
                        }

                        $emailData = new \SendinBlue\Client\Model\SendSmtpEmail([
                            'sender' => [
                                'email' => env('MAIL_FROM_ADDRESS'),
                                'name'  => env('MAIL_FROM_NAME'),
                            ],
                            'to' => array_map(fn($addr) => [
                                'email' => $addr->getAddress(),
                                'name'  => $addr->getName()
                            ], $message->getTo()),
                            'subject'     => $message->getSubject(),
                            'htmlContent' => $message->getHtmlBody() ?? $message->getTextBody(),
                        ]);

                        $this->apiInstance->sendTransacEmail($emailData);

                        return new \Symfony\Component\Mailer\SentMessage(
                            $message,
                            $envelope ?? \Symfony\Component\Mailer\Envelope::create($message)
                        );
                    }

                    public function __toString(): string
                    {
                        return 'brevo-api';
                    }
                };
            });
        });
    }
}
