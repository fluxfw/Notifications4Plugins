<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use srag\Plugins\Notifications4Plugins\Utils\Notifications4PluginsTrait;
use srag\RemovePluginDataConfirm\Notifications4Plugins\AbstractRemovePluginDataConfirm;

/**
 * Class Notifications4PluginsConfirm
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy Notifications4PluginsConfirm: ilUIPluginRouterGUI
 */
class Notifications4PluginsConfirm extends AbstractRemovePluginDataConfirm {

	use Notifications4PluginsTrait;
	const PLUGIN_CLASS_NAME = ilNotifications4PluginsPlugin::class;
}
