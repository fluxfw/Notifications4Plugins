<?php

namespace srag\Plugins\Notifications4Plugins\Sender;

use ilNotifications4PluginsPlugin;
use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Notifications4Plugins\Exception\Notifications4PluginsException;
use srag\Plugins\Notifications4Plugins\Notification\Notification;
use srag\Plugins\Notifications4Plugins\Utils\Notifications4PluginsTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\Notifications4Plugins\Sender
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository {

	use DICTrait;
	use Notifications4PluginsTrait;
	const PLUGIN_CLASS_NAME = ilNotifications4PluginsPlugin::class;
	/**
	 * @var self
	 */
	protected static $instance = null;


	/**
	 * @return self
	 */
	public static function getInstance(): self {
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Repository constructor
	 */
	private function __construct() {

	}


	/**
	 * @return Factory
	 */
	public function factory(): Factory {
		return Factory::getInstance();
	}


	/**
	 * @param Sender       $sender   A concrete srNotificationSender object, e.g. srNotificationMailSender
	 * @param Notification $notification
	 * @param array        $placeholders
	 * @param string       $language Omit to choose the default language
	 *
	 * @throws Notifications4PluginsException
	 */
	public function send(Sender $sender, Notification $notification, array $placeholders = array(), string $language = "")/*: void*/ {
		$parser = self::parser()->getParserForNotification($notification);

		$sender->setSubject(self::parser()->parseSubject($parser, $notification, $placeholders, $language));

		$sender->setMessage(self::parser()->parseText($parser, $notification, $placeholders, $language));

		$sender->send();
	}
}
