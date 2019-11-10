<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Symfony\Component\Mailer\DataCollector\MessageDataCollector;

final class MailerContext implements Context
{
    private $messageDataCollector;

    public function __construct(MessageDataCollector $messageDataCollector)
    {
        $this->messageDataCollector = $messageDataCollector;
    }

    /**
     * @Then a mail should have been sent to :recipient
     */
    public function aMailShouldHaveBeenSentToRecipient(string $recipient): void
    {
        foreach ($this->messageDataCollector->getEvents()->getMessages() as $message) {
            foreach ($message->getTo() as $toAddress) {
                if ($toAddress->getAddress() === $recipient) {
                    return;
                }
            }
        }

        throw new \RuntimeException(sprintf('No mail sent to recipient %s', $recipient));
    }
}
