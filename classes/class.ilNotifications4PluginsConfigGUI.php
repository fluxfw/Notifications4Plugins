<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\ActiveRecordConfig\Notifications4Plugins\ActiveRecordConfigGUI;
use srag\Plugins\Notifications4Plugins\Ctrl\Notifications4PluginCtrl;

/**
 * Class ilNotifications4PluginsConfigGUI
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 *
 * @deprecated
 */
class ilNotifications4PluginsConfigGUI extends ActiveRecordConfigGUI {

	const PLUGIN_CLASS_NAME = ilNotifications4PluginsPlugin::class;
	/**
	 * @var array
	 */
	protected static $tabs = [
		Notifications4PluginCtrl::TAB_NOTIFICATIONS => [
			Notifications4PluginCtrl::class,
			Notifications4PluginCtrl::CMD_LIST_NOTIFICATIONS
		]
	];
}
