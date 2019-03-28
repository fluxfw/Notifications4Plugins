<?php

namespace srag\Plugins\Notifications4Plugins\Utils;

use srag\Plugins\Notifications4Plugins\Notification\Language\Repository as NotificationLanguageRepository;
use srag\Plugins\Notifications4Plugins\Notification\Repository as NotificationRepository;
use srag\Plugins\Notifications4Plugins\Parser\Repository as ParserRepository;
use srag\Plugins\Notifications4Plugins\Sender\Repository as SenderRepository;

/**
 * Trait Notifications4PluginsTrait
 *
 * @package srag\Plugins\Notifications4Plugins\Utils
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
trait Notifications4PluginsTrait {

	/**
	 * @return NotificationRepository
	 */
	protected static function notification(): NotificationRepository {
		return NotificationRepository::getInstance();
	}


	/**
	 * @return NotificationLanguageRepository
	 */
	protected static function notificationLanguage(): NotificationLanguageRepository {
		return NotificationLanguageRepository::getInstance();
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
