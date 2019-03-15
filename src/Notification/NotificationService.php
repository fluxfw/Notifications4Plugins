<?php

namespace srag\Plugins\Notifications4Plugins\Notification;

use ilNotifications4PluginsPlugin;
use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Plugins\Notifications4Plugins\Utils\Notifications4PluginsTrait;

/**
 * Class NotificationService
 *
 * @package srag\Plugins\Notifications4Plugins\Notification
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
class NotificationService {

	use DICTrait;
	use Notifications4PluginsTrait;
	const PLUGIN_CLASS_NAME = ilNotifications4PluginsPlugin::class;
	/**
	 * @var Notification
	 */
	protected $notification;


	/**
	 * NotificationService constructor
	 *
	 * @param Notification $notification
	 */
	public function __construct(Notification $notification = null) {
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
	 * @param string $title
	 * @param string $description
	 * @param string $name
	 * @param string $default_language
	 * @param array  $notifications
	 */
	public function update($title, $description, $name, $default_language, array $notifications = array()) {
		$this->createOrUpdate($title, $description, $name, $default_language, $notifications);
	}


	/**
	 * @param string $title
	 * @param string $description
	 * @param string $name
	 * @param string $default_language
	 * @param array  $notifications
	 */
	protected function createOrUpdate($title, $description, $name, $default_language, array $notifications = array()) {
		$this->notification = ($this->notification) ? $this->notification : new Notification();
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
