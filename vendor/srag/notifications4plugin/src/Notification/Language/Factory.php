<?php

namespace srag\Notifications4Plugin\Notifications4Plugins\Notification\Language;

use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Notifications4Plugin\Notifications4Plugins\Utils\Notifications4PluginTrait;

/**
 * Class Factory
 *
 * @package srag\Notifications4Plugin\Notifications4Plugins\Notification\Language
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Factory {

	use DICTrait;
	use Notifications4PluginTrait;
	/**
	 * @var self[]
	 */
	protected static $instances = [];


	/**
	 * @param string $language_class
	 *
	 * @return self
	 */
	public static function getInstance(string $language_class): self {
		if (!isset(self::$instances[$language_class])) {
			self::$instances[$language_class] = new self($language_class);
		}

		return self::$instances[$language_class];
	}


	/**
	 * @var string|AbstractNotificationLanguage
	 */
	protected $language_class;


	/**
	 * Factory constructor
	 *
	 * @param string $language_class
	 */
	private function __construct(string $language_class) {
		$this->language_class = $language_class;
	}


	/**
	 * @return AbstractNotificationLanguage
	 */
	public function newInstance(): AbstractNotificationLanguage {
		$language = new $this->language_class();

		return $language;
	}
}
