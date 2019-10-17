<?php

namespace srag\Notifications4Plugin\Notifications4Plugins\Notification\Language;

use ilDateTime;

/**
 * Interface NotificationLanguage
 *
 * @package srag\Notifications4Plugin\Notifications4Plugins\Notification\Language
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface NotificationLanguage {

	/**
	 * @return int
	 */
	public function getId(): int;


	/**
	 * @param int $id
	 */
	public function setId(int $id)/*: void*/
	;


	/**
	 * @return int
	 */
	public function getNotificationId(): int;


	/**
	 * @param int $notification_id
	 */
	public function setNotificationId(int $notification_id)/*: void*/
	;


	/**
	 * @return string
	 */
	public function getLanguage(): string;


	/**
	 * @param string $language
	 */
	public function setLanguage(string $language)/*: void*/
	;


	/**
	 * @return string
	 */
	public function getSubject(): string;


	/**
	 * @param string $subject
	 */
	public function setSubject(string $subject)/*: void*/
	;


	/**
	 * @return string
	 */
	public function getText(): string;


	/**
	 * @param string $text
	 */
	public function setText(string $text)/*: void*/
	;


	/**
	 * @return ilDateTime
	 */
	public function getCreatedAt(): ilDateTime;


	/**
	 * @param ilDateTime $created_at
	 */
	public function setCreatedAt(ilDateTime $created_at)/*: void*/
	;


	/**
	 * @return ilDateTime
	 */
	public function getUpdatedAt(): ilDateTime;


	/**
	 * @param ilDateTime $updated_at
	 */
	public function setUpdatedAt(ilDateTime $updated_at)/*: void*/
	;
}
