<?php

namespace srag\Notifications4Plugin\Notifications4Plugins\Sender;

use ilObjUser;

/**
 * Interface FactoryInterface
 *
 * @package srag\Notifications4Plugin\Notifications4Plugins\Sender
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface FactoryInterface {

	/**
	 * @param string       $from
	 * @param string|array $to
	 *
	 * @return ExternalMailSender
	 */
	public function externalMail(string $from = "", $to = ""): ExternalMailSender;


	/**
	 * @param int|string|ilObjUser $user_from
	 * @param int|string|ilObjUser $user_to
	 *
	 * @return InternalMailSender
	 */
	public function internalMail($user_from = 0, $user_to = ""): InternalMailSender;


	/**
	 * @param int|string|ilObjUser $user_from
	 * @param string|array         $to
	 * @param string               $method
	 * @param string               $uid
	 * @param int                  $startTime
	 * @param int                  $endTime
	 * @param int                  $sequence
	 *
	 * @return vcalendarSender
	 */
	public function vcalendar($user_from = 0, $to = "", string $method = vcalendarSender::METHOD_REQUEST, string $uid = "", int $startTime = 0, int $endTime = 0, int $sequence = 0): vcalendarSender;
}
