<?php

namespace srag\Notifications4Plugin\Notifications4Plugins\Notification;

use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Notifications4Plugin\Notifications4Plugins\Utils\Notifications4PluginTrait;

/**
 * Class Factory
 *
 * @package srag\Notifications4Plugin\Notifications4Plugins\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Factory implements FactoryInterface {

	use DICTrait;
	use Notifications4PluginTrait;
	/**
	 * @var FactoryInterface[]
	 */
	protected static $instances = [];


	/**
	 * @param string $notification_class
	 *
	 * @return FactoryInterface
	 */
	public static function getInstance(string $notification_class): FactoryInterface {
		if (!isset(self::$instances[$notification_class])) {
			self::$instances[$notification_class] = new self($notification_class);
		}

		return self::$instances[$notification_class];
	}


	/**
	 * @var string|Notification
	 */
	protected $notification_class;


	/**
	 * Factory constructor
	 *
	 * @param string $notification_class
	 */
	private function __construct(string $notification_class) {
		$this->notification_class = $notification_class;
	}


	/**
	 * @inheritdoc
	 */
	public function newInstance(): Notification {
		$notification = new $this->notification_class();

		return $notification;
	}
}
