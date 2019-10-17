<?php

namespace srag\Notifications4Plugin\Notifications4Plugins\Notification\Language;

/**
 * Interface FactoryInterface
 *
 * @package srag\Notifications4Plugin\Notifications4Plugins\Notification\Language
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface FactoryInterface {

	/**
	 * @return NotificationLanguage
	 */
	public function newInstance(): NotificationLanguage;
}
