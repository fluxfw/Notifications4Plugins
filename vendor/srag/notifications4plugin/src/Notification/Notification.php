<?php

namespace srag\Notifications4Plugin\Notifications4Plugins\Notification;

use ilDateTime;
use srag\Notifications4Plugin\Notifications4Plugins\Notification\Language\NotificationLanguage;

/**
 * Interface Notification
 *
 * @package srag\Notifications4Plugin\Notifications4Plugins\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Notification {

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
	 * @return string
	 */
	public function getName(): string;


	/**
	 * @param string $name
	 */
	public function setName(string $name)/*: void*/
	;


	/**
	 * @return string
	 */
	public function getTitle(): string;


	/**
	 * @param string $title
	 */
	public function setTitle(string $title)/*: void*/
	;


	/**
	 * @return string
	 */
	public function getDescription(): string;


	/**
	 * @param string $description
	 */
	public function setDescription(string $description)/*: void*/
	;


	/**
	 * @return string
	 */
	public function getDefaultLanguage(): string;


	/**
	 * @param string $default_language
	 */
	public function setDefaultLanguage(string $default_language)/*: void*/
	;


	/**
	 * @return string
	 */
	public function getParser(): string;


	/**
	 * @param string $parser
	 */
	public function setParser(string $parser)/*: void*/
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


	/**
	 * @return NotificationLanguage[]
	 */
	public function getLanguages(): array;


	/**
	 * @param NotificationLanguage[] $languages
	 */
	public function setLanguages(array $languages)/*: void*/
	;


	/**
	 * @param NotificationLanguage $language
	 */
	public function addLanguage(NotificationLanguage $language)/*: void*/
	;


	/**
	 * @param string $language
	 *
	 * @return string
	 */
	public function getSubject(string $language = ""): string;


	/**
	 * @param string $subject
	 * @param string $language
	 */
	public function setSubject(string $subject, string $language)/*: void*/
	;


	/**
	 * @param string $language
	 *
	 * @return string
	 */
	public function getText(string $language = ""): string;


	/**
	 * @param string $text
	 * @param string $language
	 */
	public function setText(string $text, string $language)/*: void*/
	;
}
