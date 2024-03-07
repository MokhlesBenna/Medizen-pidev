<?php
namespace App\Service;

use Symfony\Component\Notifier\NotifierInterface;
use App\Notification\NewTopicNotification;
use Symfony\Component\Notifier\Recipient\AdminRecipient;

class CustomNotificationService
{
    private $notifier;

    public function __construct(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    public function sendNewTopicNotification(): void
    {
        // Créer une nouvelle instance de la notification
        $notification = new NewTopicNotification();

        // Envoyer la notification à tous les administrateurs
        $this->notifier->send($notification, new AdminRecipient());
    }
}
