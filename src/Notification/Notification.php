<?php

namespace srag\Plugins\Notifications4Plugins\Notification;

use srag\Notifications4Plugin\Notifications4Plugins\Notification\AbstractNotification;
use srag\Plugins\Notifications4Plugins\Notification\Language\NotificationLanguage;

/**
 * Class Notification
 *
 * @package srag\Plugins\Notifications4Plugins\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @deprecated
 */
class Notification extends AbstractNotification {

	const TABLE_NAME = "sr_notification";
	const LANGUAGE_CLASS_NAME = NotificationLanguage::class;
}
