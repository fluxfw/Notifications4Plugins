<?php

namespace srag\Notifications4Plugin\Notifications4Plugins\Notification\Language;

/**
 * Interface RepositoryInterface
 *
 * @package srag\Notifications4Plugin\Notifications4Plugins\Notification\Language
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface RepositoryInterface {

	/**
	 * @param NotificationLanguage $language
	 */
	public function deleteLanguage(NotificationLanguage $language)/*: void*/
	;


	/**
	 * @param NotificationLanguage $language
	 *
	 * @return NotificationLanguage
	 */
	public function duplicateLanguage(NotificationLanguage $language): NotificationLanguage;


	/**
	 * @return FactoryInterface
	 */
	public function factory(): FactoryInterface;


	/**
	 * @param int $id
	 *
	 * @return NotificationLanguage|null
	 */
	public function getLanguageById(int $id)/*: ?NotificationLanguage*/
	;


	/**
	 * @param int    $notification_id
	 * @param string $language
	 *
	 * @return NotificationLanguage
	 */
	public function getLanguageForNotification(int $notification_id, string $language): NotificationLanguage;


	/**
	 * @param int $notification_id
	 *
	 * @return NotificationLanguage[]
	 */
	public function getLanguagesForNotification(int $notification_id): array;


	/**
	 * @param NotificationLanguage $language
	 */
	public function storeInstance(NotificationLanguage $language)/*: void*/
	;
}
