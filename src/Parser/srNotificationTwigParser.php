<?php

namespace srag\Plugins\Notifications4Plugins\Parser;

use ilNotifications4PluginsPlugin;
use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Plugins\Notifications4Plugins\Utils\Notifications4PluginsTrait;
use Twig_Environment;
use Twig_Loader_String;

/**
 * Class srNotificationTwigParser
 *
 * @package srag\Plugins\Notifications4Plugins\Parser
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
class srNotificationTwigParser implements srNotificationParser {

	use DICTrait;
	use Notifications4PluginsTrait;
	const PLUGIN_CLASS_NAME = ilNotifications4PluginsPlugin::class;
	/**
	 * @var array
	 */
	protected $options = array(
		'autoescape' => false, // Do not auto escape variables by default when using {{ myVar }}
	);


	/**
	 * srNotificationTwigParser constructor
	 *
	 * @param array $options
	 */
	public function __construct(array $options = array()) {
		$this->options = array_merge($this->options, $options);
	}


	/**
	 * @inheritdoc
	 */
	public function parse($text, array $replacements = array()) {
		$loader = new Twig_Loader_String();
		$twig = new Twig_Environment($loader, $this->options);

		return $twig->render($text, $replacements);
	}
}
