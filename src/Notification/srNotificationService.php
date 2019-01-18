<?php

namespace srag\Plugins\Notifications4Plugins\Notification;

use ilNotifications4PluginsPlugin;
use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Plugins\Notifications4Plugins\Utils\Notifications4PluginsTrait;

/**
 * Class srNotificationService
 *
 * @package srag\Plugins\Notifications4Plugins\Notification
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class srNotificationService {

	use DICTrait;
	use Notifications4PluginsTrait;
	const PLUGIN_CLASS_NAME = ilNotifications4PluginsPlugin::class;
	/**
	 * @var srNotification
	 */
	protected $notification;


	/**
	 * @param srNotification $notification
	 */
	public function __construct(srNotification $notification = NULL) {
		$this->notification = $notification;
	}


	/**
	 * @param string $title
	 * @param string $description
	 * @param string $name
	 * @param string $default_language
	 * @param array  $notifications
	 */
	public function create($title, $description, $name, $default_language, array $notifications = array()) {
		$this->createOrUpdate($title, $description, $name, $default_language, $notifications);
	}


	/**
	 * @param        $title
	 * @param        $description
	 * @param string $name
	 * @param        $default_language
	 * @param array  $notifications
	 */
	public function update($title, $description, $name, $default_language, array $notifications = array()) {
		$this->createOrUpdate($title, $description, $name, $default_language, $notifications);
	}


	/**
	 * @param       $title
	 * @param       $description
	 * @param       $name
	 * @param       $default_language
	 * @param array $notifications
	 */
	protected function createOrUpdate($title, $description, $name, $default_language, array $notifications = array()) {
		$this->notification = ($this->notification) ? $this->notification : new srNotification();
		$this->notification->setTitle($title);
		$this->notification->setDefaultLanguage($default_language);
		$this->notification->setDescription($description);
		$this->notification->setName($name);
		$this->notification->store();
		foreach ($notifications as $notification) {
			$this->notification->setText($notification['text'], $notification['language']);
			$this->notification->setSubject($notification['subject'], $notification['language']);
		}
		$this->notification->store();
	}
}
