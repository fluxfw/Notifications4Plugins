<?php

use srag\DIC\Notifications4Plugins\DICTrait;

/**
 * Class ilNotifications4PluginsUIHookGUI
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class ilNotifications4PluginsUIHookGUI extends ilUIHookPluginGUI {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilNotifications4PluginsPlugin::class;
}
