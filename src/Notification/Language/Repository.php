<?php

namespace srag\Plugins\Notifications4Plugins\Notification\Language;

use ilDateTime;
use ilNotifications4PluginsPlugin;
use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Plugins\Notifications4Plugins\Utils\Notifications4PluginsTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\Notifications4Plugins\Notification\Language
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository {

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
	public static function getInstance(): self {
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Repository constructor
	 */
	private function __construct() {

	}


	/**
	 * @param NotificationLanguage $language
	 */
	public function deleteLanguage(NotificationLanguage $language)/*: void*/ {
		$language->delete();
	}


	/**
	 * @param NotificationLanguage $language
	 *
	 * @return NotificationLanguage
	 */
	public function duplicateLanguage(NotificationLanguage $language): NotificationLanguage {
		/**
		 * @var NotificationLanguage $cloned_language
		 */

		$cloned_language = $language->copy();

		return $cloned_language;
	}


	/**
	 * @return Factory
	 */
	public function factory(): Factory {
		return Factory::getInstance();
	}


	/**
	 * @param int $id
	 *
	 * @return NotificationLanguage|null
	 */
	public function getLanguageById(int $id)/*: ?NotificationLanguage*/ {
		/**
		 * @var NotificationLanguage|null $language
		 */

		$language = NotificationLanguage::where([ "id" => $id ])->first();

		return $language;
	}


	/**
	 * @param int    $notification_id
	 * @param string $language
	 *
	 * @return NotificationLanguage
	 */
	public function getLanguageForNotification(int $notification_id, string $language): NotificationLanguage {
		/**
		 * @var NotificationLanguage $l
		 */

		$l = NotificationLanguage::where([ "notification_id" => $notification_id, "language" => $language ])->first();

		if ($l === null) {
			$l = self::notificationLanguage()->factory()->newInstance();

			$l->setNotificationId($notification_id);

			$l->setLanguage($language);
		}

		return $l;
	}


	/**
	 * @param int $notification_id
	 *
	 * @return NotificationLanguage[]
	 */
	public function getLanguagesForNotification(int $notification_id): array {
		/**
		 * @var NotificationLanguage[] $array
		 */

		$array = NotificationLanguage::where([ "notification_id" => $notification_id ])->get();

		$languages = [];

		foreach ($array as $language) {
			$languages[$language->getLanguage()] = $language;
		}

		return $languages;
	}


	/**
	 * @param NotificationLanguage $language
	 */
	public function storeInstance(NotificationLanguage $language)/*: void*/ {
		$date = new ilDateTime(time(), IL_CAL_UNIX);

		if (empty($language->getId())) {
			$language->setCreatedAt($date);
		}

		$language->setUpdatedAt($date);

		$language->store();
	}
}
