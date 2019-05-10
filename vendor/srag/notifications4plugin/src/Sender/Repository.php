<?php

namespace srag\Notifications4Plugin\Notifications4Plugins\Sender;

use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Notifications4Plugin\Notifications4Plugins\Notification\Notification;
use srag\Notifications4Plugin\Notifications4Plugins\Utils\Notifications4PluginTrait;

/**
 * Class Repository
 *
 * @package srag\Notifications4Plugin\Notifications4Plugins\Sender
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository implements RepositoryInterface {

	use DICTrait;
	use Notifications4PluginTrait;
	/**
	 * @var RepositoryInterface
	 */
	protected static $instance = null;


	/**
	 * @return RepositoryInterface
	 */
	public static function getInstance(): RepositoryInterface {
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
	 * @inheritdoc
	 */
	public function factory(): FactoryInterface {
		return Factory::getInstance();
	}


	/**
	 * @inheritdoc
	 */
	public function send(Sender $sender, Notification $notification, array $placeholders = [], string $language = "")/*: void*/ {
		$parser = self::parser()->getParserForNotification($notification);

		$sender->setSubject(self::parser()->parseSubject($parser, $notification, $placeholders, $language));

		$sender->setMessage(self::parser()->parseText($parser, $notification, $placeholders, $language));

		$sender->send();
	}
}
