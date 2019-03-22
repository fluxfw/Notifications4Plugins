<?php

use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Plugins\Notifications4Plugins\Utils\Notifications4PluginsTrait;

/**
 * Class ilNotifications4PluginsUIHookGUI
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class ilNotifications4PluginsUIHookGUI extends ilUIHookPluginGUI {

	use DICTrait;
	use Notifications4PluginsTrait;
	const PLUGIN_CLASS_NAME = ilNotifications4PluginsPlugin::class;
}
