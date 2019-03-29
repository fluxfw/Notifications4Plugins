<?php

namespace srag\Notifications4Plugin\Notifications4Plugins\Utils;

use srag\Notifications4Plugin\Notifications4Plugins\Notification\Language\Repository as NotificationLanguageRepository;
use srag\Notifications4Plugin\Notifications4Plugins\Notification\Repository as NotificationRepository;
use srag\Notifications4Plugin\Notifications4Plugins\Parser\Repository as ParserRepository;
use srag\Notifications4Plugin\Notifications4Plugins\Sender\Repository as SenderRepository;
use srag\Notifications4Plugin\Notifications4Plugins\UI\UI as NotificationUI;

/**
 * Trait Notifications4PluginTrait
 *
 * @package srag\Notifications4Plugin\Notifications4Plugins\Utils
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
trait Notifications4PluginTrait {

	/**
	 * @param string $notification_class
	 * @param string $language_class
	 *
	 * @return NotificationRepository
	 */
	protected static function notification(string $notification_class, string $language_class): NotificationRepository {
		return NotificationRepository::getInstance($notification_class, $language_class);
	}


	/**
	 * @param string $language_class
	 *
	 * @return NotificationLanguageRepository
	 */
	protected static function notificationLanguage(string $language_class): NotificationLanguageRepository {
		return NotificationLanguageRepository::getInstance($language_class);
	}


	/**
	 * @return NotificationUI
	 */
	protected static function notificationUI(): NotificationUI {
		return NotificationUI::getInstance();
	}


	/**
	 * @return ParserRepository
	 */
	protected static function parser(): ParserRepository {
		return ParserRepository::getInstance();
	}


	/**
	 * @return SenderRepository
	 */
	protected static function sender(): SenderRepository {
		return SenderRepository::getInstance();
	}
}
