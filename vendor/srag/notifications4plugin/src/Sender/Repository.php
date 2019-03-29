<?php

namespace srag\Notifications4Plugin\Notifications4Plugins\Sender;

use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Notifications4Plugin\Notifications4Plugins\Exception\Notifications4PluginException;
use srag\Notifications4Plugin\Notifications4Plugins\Notification\AbstractNotification;
use srag\Notifications4Plugin\Notifications4Plugins\Utils\Notifications4PluginTrait;

/**
 * Class Repository
 *
 * @package srag\Notifications4Plugin\Notifications4Plugins\Sender
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository {

	use DICTrait;
	use Notifications4PluginTrait;
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
	 * @param Sender               $sender   A concrete srNotificationSender object, e.g. srNotificationMailSender
	 * @param AbstractNotification $notification
	 * @param array                $placeholders
	 * @param string               $language Omit to choose the default language
	 *
	 * @throws Notifications4PluginException
	 */
	public function send(Sender $sender, AbstractNotification $notification, array $placeholders = array(), string $language = "")/*: void*/ {
		$parser = self::parser()->getParserForNotification($notification);

		$sender->setSubject(self::parser()->parseSubject($parser, $notification, $placeholders, $language));

		$sender->setMessage(self::parser()->parseText($parser, $notification, $placeholders, $language));

		$sender->send();
	}
}
