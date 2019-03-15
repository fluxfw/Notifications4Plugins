<?php

require_once __DIR__ . '/../vendor/autoload.php';

use srag\Plugins\Notifications4Plugins\Notification\Notification;
use srag\Plugins\Notifications4Plugins\Notification\NotificationLanguage;
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
	 * @return self
	 */
	public static function getInstance() {
		if (self::$instance === null) {
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
	 * @return string
	 */
	public function getPluginName() {
		return self::PLUGIN_NAME;
	}


	/**
	 * @inheritdoc
	 */
	protected function deleteData()/*: void*/ {
		self::dic()->database()->dropTable(Notification::TABLE_NAME, false);
		self::dic()->database()->dropTable(NotificationLanguage::TABLE_NAME, false);
	}
}
