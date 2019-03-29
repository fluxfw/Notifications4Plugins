<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\DIC\Notifications4Plugins\Util\LibraryLanguageInstaller;
use srag\Plugins\Notifications4Plugins\Notification\Language\NotificationLanguage;
use srag\Plugins\Notifications4Plugins\Notification\Notification;
use srag\RemovePluginDataConfirm\Notifications4Plugins\PluginUninstallTrait;

/**
 * Class ilNotifications4PluginsPlugin
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class ilNotifications4PluginsPlugin extends ilUserInterfaceHookPlugin {

	use PluginUninstallTrait;
	const PLUGIN_ID = "notifications4pl";
	const PLUGIN_NAME = "Notifications4Plugins";
	const PLUGIN_CLASS_NAME = self::class;
	const REMOVE_PLUGIN_DATA_CONFIRM_CLASS_NAME = Notifications4PluginsConfirm::class;
	/**
	 * @var self
	 */
	protected static $instance;


	/**
	 * @return self
	 */
	public static function getInstance(): self {
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
	public function getPluginName(): string {
		return self::PLUGIN_NAME;
	}


	/**
	 * @inheritdoc
	 */
	public function updateLanguages($a_lang_keys = null) {
		parent::updateLanguages($a_lang_keys);

		LibraryLanguageInstaller::getInstance()->withPlugin(self::plugin())->withLibraryLanguageDirectory(__DIR__
			. "/../vendor/srag/removeplugindataconfirm/lang")->updateLanguages();

		LibraryLanguageInstaller::getInstance()->withPlugin(self::plugin())->withLibraryLanguageDirectory(__DIR__
			. "/../vendor/srag/notifications4plugin/lang")->updateLanguages();
	}


	/**
	 * @inheritdoc
	 */
	protected function deleteData()/*: void*/ {
		self::dic()->database()->dropTable(Notification::TABLE_NAME, false);
		self::dic()->database()->dropTable(NotificationLanguage::TABLE_NAME, false);
	}
}
