<?php

namespace srag\Plugins\Notifications4Plugins\Ctrl;

use ilNotifications4PluginsPlugin;
use ilUtil;
use srag\Notifications4Plugin\Notifications4Plugins\Ctrl\AbstractCtrl;
use srag\Plugins\Notifications4Plugins\Notification\Language\NotificationLanguage;
use srag\Plugins\Notifications4Plugins\Notification\Notification;

/**
 * Class Notifications4PluginCtrl
 *
 * @package           srag\Plugins\Notifications4Plugins\Ctrl
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\Notifications4Plugins\Ctrl\Notifications4PluginCtrl: ilNotifications4PluginsConfigGUI
 */
class Notifications4PluginCtrl extends AbstractCtrl {

	const NOTIFICATION_CLASS_NAME = Notification::class;
	const LANGUAGE_CLASS_NAME = NotificationLanguage::class;
	const PLUGIN_CLASS_NAME = ilNotifications4PluginsPlugin::class;


	/**
	 * @inheritdoc
	 */
	public function executeCommand()/*: void*/ {
		ilUtil::sendInfo(self::plugin()->translate("outdated_warning","",[ilNotifications4PluginsPlugin::PLUGIN_NAME]));

		self::dic()->tabs()->activateTab(self::TAB_NOTIFICATIONS);

		parent::executeCommand();
	}
}
