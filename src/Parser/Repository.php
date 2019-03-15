<?php

namespace srag\Plugins\Notifications4Plugins\Parser;

use ilNotifications4PluginsPlugin;
use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Plugins\Notifications4Plugins\Notification\Notification;
use srag\Plugins\Notifications4Plugins\Utils\Notifications4PluginsTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\Notifications4Plugins\Parser
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository {

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
	public static function getInstance() {
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Repository constructor
	 */
	private function __construct() {

	}


	/**
	 * @return Factory
	 */
	public function factory() {
		return Factory::getInstance();
	}


	/**
	 * @param Notification $notification
	 *
	 * @return Parser
	 */
	public function getParserForNotification(Notification $notification) {
		return $this->factory()->twig();
	}


	/**
	 * @param Parser       $parser
	 * @param Notification $notification
	 * @param array        $replacements
	 * @param string       $language
	 *
	 * @return string
	 */
	public function parseSubject(Parser $parser, Notification $notification, array $replacements = array(), $language = '') {
		return $parser->parse($notification->getSubject($language), $replacements);
	}


	/**
	 * @param Parser       $parser
	 * @param Notification $notification
	 * @param array        $replacements
	 * @param string       $language
	 *
	 * @return string
	 */
	public function parseText(Parser $parser, Notification $notification, array $replacements = array(), $language = '') {
		return $parser->parse($notification->getText($language), $replacements);
	}
}
