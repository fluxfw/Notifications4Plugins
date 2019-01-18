<?php

require_once __DIR__ . '/../vendor/autoload.php';

use srag\Plugins\Notifications4Plugins\Notification\srNotification;
use srag\Plugins\Notifications4Plugins\Notification\srNotificationLanguage;
use srag\Plugins\Notifications4Plugins\Utils\Notifications4PluginsTrait;
use srag\RemovePluginDataConfirm\Notifications4Plugins\PluginUninstallTrait;

/**
 * Class ilNotifications4PluginsPlugin
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class ilNotifications4PluginsPlugin extends ilUserInterfaceHookPlugin {

	use PluginUninstallTrait;
	use Notifications4PluginsTrait;
	const PLUGIN_ID = 'notifications4pl';
	const PLUGIN_NAME = 'Notifications4Plugins';
	const PLUGIN_CLASS_NAME = self::class;
	const REMOVE_PLUGIN_DATA_CONFIRM_CLASS_NAME = Notifications4PluginsConfirm::class;
	/**
	 * @var self
	 */
	protected static $instance;


	/**
	 * Singleton Access to this plugin
	 *
	 * @return self
	 */
	public static function getInstance() {
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * ilNotifications4PluginsPlugin constructor
	 */
	public function __construct() {
		parent::__construct();
	}


	/**
	 * Get Plugin Name. Must be same as in class name il<Name>Plugin
	 * and must correspond to plugins subdirectory name.
	 *
	 * Must be overwritten in plugin class of plugin
	 * (and should be made final)
	 *
	 * @return    string    Plugin Name
	 */
	public function getPluginName() {
		return self::PLUGIN_NAME;
	}


	/**
	 * @inheritdoc
	 */
	protected function deleteData()/*: void*/ {
		self::dic()->database()->dropTable(srNotification::TABLE_NAME, false);
		self::dic()->database()->dropTable(srNotificationLanguage::TABLE_NAME, false);
	}
}
