<?php

namespace srag\Plugins\Notifications4Plugins\Notification;

use ilNotifications4PluginsPlugin;
use ilSelectInputGUI;
use srag\CustomInputGUIs\Notifications4Plugins\PropertyFormGUI\PropertyFormGUI;
use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Plugins\Notifications4Plugins\Utils\Notifications4PluginsTrait;

/**
 * Class UI
 *
 * @package srag\Plugins\Notifications4Plugins\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class UI {

	use DICTrait;
	use Notifications4PluginsTrait;
	const PLUGIN_CLASS_NAME = ilNotifications4PluginsPlugin::class;
	/**
	 * @var self
	 */
	protected static $instance = null;


	/**
	 * @return self
	 */
	public static function getInstance()/*: self*/ {
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * UI constructor
	 */
	private function __construct() {

	}


	/**
	 * @param string $post_key
	 * @param array  $placeholder_types
	 *
	 * @return array
	 */
	public function templateSelection(/*string*/
		$post_key, array $placeholder_types)/*: array*/ {
		return [
			$post_key => [
				PropertyFormGUI::PROPERTY_CLASS => ilSelectInputGUI::class,
				PropertyFormGUI::PROPERTY_REQUIRED => true,
				PropertyFormGUI::PROPERTY_OPTIONS => [ "" => "" ] + self::notification()->getArrayForSelection(),
				"setTitle" => self::plugin()->translate("template_selection", "", [ ilNotifications4PluginsPlugin::PLUGIN_NAME ]),
				"setInfo" => self::output()->getHTML([
					self::plugin()->translate("template_selection_info", "", [ ilNotifications4PluginsPlugin::PLUGIN_NAME ]),
					"<br><br>",
					self::dic()->ui()->factory()->listing()->descriptive($placeholder_types)
				])
			]
		];
	}
}
