<?php
namespace Webit\Bundle\NotificationBundle\Listeners;

use Webit\Bundle\NotificationBundle\Notification\Event\EventNotification;

use Symfony\Component\DependencyInjection\ContainerAware;
use Webit\Bundle\NotificationBundle\Notification\RecipientInterface;
use Webit\Bundle\NotificationBundle\Entity\NotificationLog;

class PostSendListener extends ContainerAware {
	public function onPostSend(EventNotification $event) {
		$em = $this->container->get('doctrine.orm.entity_manager');
		$notification = $event->getNotification();

		if($result = $event->getResult()) {
			if(is_object($notification) && method_exists($notification, 'getSuccess')) {
				$success = $notification->getSuccess();
			} else {
				$success = $result;
			}
			
			if($success) {
				$log = new NotificationLog();
					$log->setType($notification->getType());
					$log->setHash($notification->getHash());
					$log->setMedia($event->getMedia());			
					$log->setRecipient($this->getRecipientInfo($event->getRecipient(), $event->getMedia()));
				$em->persist($log);
				$em->flush();
			}
		}
	}
	
	private function getRecipientInfo(RecipientInterface $recipient, $media) {
		switch($media) {
			case 'sms':
				return $recipient->getPhoneNo();
			break;
			case 'email':
				return $recipient->getEmail();
			break;
		}
		
		return null;
	}
}
?>