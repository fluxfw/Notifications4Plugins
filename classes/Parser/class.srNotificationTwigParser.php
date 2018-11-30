<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Plugins\Notifications4Plugins\Utils\Notifications4PluginsTrait;

/**
 * Class srNotificationTwigParser
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
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
	 * @param array $options
	 */
	public function __construct(array $options = array()) {
		$this->options = array_merge($this->options, $options);
		$this->loadTwig();
	}


	/**
	 * Bootstrap twig engine
	 */
	protected function loadTwig() {
		static $loaded = false;
		if (!$loaded) {
			require_once __DIR__ . '/../../lib/twig/lib/Twig/Autoloader.php';
			Twig_Autoloader::register();
			$loaded = true;
		}
	}


	/**
	 * @param string $text
	 * @param array  $replacements
	 *
	 * @return string
	 */
	public function parse($text, array $replacements = array()) {
		$loader = new \Twig_Loader_String();
		$twig = new \Twig_Environment($loader, $this->options);

		return $twig->render($text, $replacements);
	}
}