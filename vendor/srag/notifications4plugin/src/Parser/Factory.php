<?php

namespace srag\Notifications4Plugin\Notifications4Plugins\Parser;

use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Notifications4Plugin\Notifications4Plugins\Utils\Notifications4PluginTrait;

/**
 * Class Factory
 *
 * @package srag\Notifications4Plugin\Notifications4Plugins\Parser
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Factory implements FactoryInterface {

	use DICTrait;
	use Notifications4PluginTrait;
	/**
	 * @var FactoryInterface
	 */
	protected static $instance = null;


	/**
	 * @return FactoryInterface
	 */
	public static function getInstance(): FactoryInterface {
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Factory constructor
	 */
	private function __construct() {

	}


	/**
	 * @inheritdoc
	 */
	public function twig(): twigParser {
		return new twigParser();
	}
}
