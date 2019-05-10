<?php

namespace srag\Notifications4Plugin\Notifications4Plugins\Sender;

use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Notifications4Plugin\Notifications4Plugins\Utils\Notifications4PluginTrait;

/**
 * Class Factory
 *
 * @package srag\Notifications4Plugin\Notifications4Plugins\Sender
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
	public function externalMail(string $from = "", $to = ""): ExternalMailSender {
		return new ExternalMailSender($from, $to);
	}


	/**
	 * @inheritdoc
	 */
	public function internalMail($user_from = 0, $user_to = ""): InternalMailSender {
		return new InternalMailSender($user_from, $user_to);
	}


	/**
	 * @inheritdoc
	 */
	public function vcalendar($user_from = 0, $to = "", string $method = vcalendarSender::METHOD_REQUEST, string $uid = "", int $startTime = 0, int $endTime = 0, int $sequence = 0): vcalendarSender {
		return new vcalendarSender($user_from, $to, $method, $uid, $startTime, $endTime, $sequence);
	}
}
