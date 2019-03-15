<?php

namespace srag\Plugins\Notifications4Plugins\Sender;

use ilNotifications4PluginsPlugin;
use ilObjUser;
use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Plugins\Notifications4Plugins\Utils\Notifications4PluginsTrait;

/**
 * Class Factory
 *
 * @package srag\Plugins\Notifications4Plugins\Sender
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Factory {

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
	 * Factory constructor
	 */
	private function __construct() {

	}


	/**
	 * @param int|string|ilObjUser $user_from
	 * @param int|string|ilObjUser $user_to
	 *
	 * @return InternalMailSender
	 */
	public function internal($user_from = 0, $user_to = '') {
		return new InternalMailSender($user_from, $user_to);
	}


	/**
	 * @param string|array $to
	 * @param string       $from
	 *
	 * @return MailSender
	 */
	public function mail($to = '', $from = '') {
		return new MailSender($to, $from);
	}


	/**
	 * @param string|array         $to
	 * @param int|string|ilObjUser $user_from
	 * @param string               $method
	 * @param int                  $startTime
	 * @param int                  $endTime
	 * @param int                  $sequence
	 *
	 * @return vcalendarSender
	 */
	public function vcalendar($to = '', $user_from = 0, $method = vcalendarSender::METHOD_REQUEST, $uid = '', $startTime = 0, $endTime = 0, $sequence = 0) {
		return new vcalendarSender($to, $user_from, $method, $uid, $startTime, $endTime, $sequence);
	}
}
