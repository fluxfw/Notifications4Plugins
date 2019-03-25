<?php

namespace srag\Plugins\Notifications4Plugins\Parser;

use ilNotifications4PluginsPlugin;
use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Notifications4Plugins\Exception\Notifications4PluginsException;
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
	public static function getInstance()/*: self*/ {
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
	public function factory()/*: Factory*/ {
		return Factory::getInstance();
	}


	/**
	 * @param Notification $notification
	 *
	 * @return Parser
	 */
	public function getParserForNotification(Notification $notification)/*: Parser*/ {
		// Currently only one parser type
		return $this->factory()->twig();
	}


	/**
	 * @param Parser       $parser
	 * @param Notification $notification
	 * @param array        $placeholders
	 * @param string       $language
	 *
	 * @return string
	 *
	 * @throws Notifications4PluginsException
	 */
	public function parseSubject(Parser $parser, Notification $notification, array $placeholders = array(),/*string*/
		$language = "")/*: string*/ {
		return $parser->parse($notification->getSubject($language), $placeholders);
	}


	/**
	 * @param Parser       $parser
	 * @param Notification $notification
	 * @param array        $placeholders
	 * @param string       $language
	 *
	 * @return string
	 *
	 * @throws Notifications4PluginsException
	 */
	public function parseText(Parser $parser, Notification $notification, array $placeholders = array(),/*string*/
		$language = "")/*: string*/ {
		return $parser->parse($notification->getText($language), $placeholders);
	}
}
