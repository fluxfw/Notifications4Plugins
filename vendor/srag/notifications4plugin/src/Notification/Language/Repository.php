<?php

namespace srag\Notifications4Plugin\Notifications4Plugins\Notification\Language;

use ilDateTime;
use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Notifications4Plugin\Notifications4Plugins\Utils\Notifications4PluginTrait;

/**
 * Class Repository
 *
 * @package srag\Notifications4Plugin\Notifications4Plugins\Notification\Language
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository {

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
	 * Repository constructor
	 *
	 * @param string $language_class
	 */
	private function __construct(string $language_class) {
		$this->language_class = $language_class;
	}


	/**
	 * @param AbstractNotificationLanguage $language
	 */
	public function deleteLanguage(AbstractNotificationLanguage $language)/*: void*/ {
		$language->delete();
	}


	/**
	 * @param AbstractNotificationLanguage $language
	 *
	 * @return AbstractNotificationLanguage
	 */
	public function duplicateLanguage(AbstractNotificationLanguage $language): AbstractNotificationLanguage {
		/**
		 * @var AbstractNotificationLanguage $duplicated_language
		 */

		$duplicated_language = $language->copy();

		return $duplicated_language;
	}


	/**
	 * @return Factory
	 */
	public function factory(): Factory {
		return Factory::getInstance($this->language_class);
	}


	/**
	 * @param int $id
	 *
	 * @return AbstractNotificationLanguage|null
	 */
	public function getLanguageById(int $id)/*: ?NotificationLanguage*/ {
		/**
		 * @var AbstractNotificationLanguage|null $language
		 */

		$language = $this->language_class::where([ "id" => $id ])->first();

		return $language;
	}


	/**
	 * @param int    $notification_id
	 * @param string $language
	 *
	 * @return AbstractNotificationLanguage
	 */
	public function getLanguageForNotification(int $notification_id, string $language): AbstractNotificationLanguage {
		/**
		 * @var AbstractNotificationLanguage $l
		 */

		$l = $this->language_class::where([ "notification_id" => $notification_id, "language" => $language ])->first();

		if ($l === null) {
			$l = $this->factory()->newInstance();

			$l->setNotificationId($notification_id);

			$l->setLanguage($language);
		}

		return $l;
	}


	/**
	 * @param int $notification_id
	 *
	 * @return AbstractNotificationLanguage[]
	 */
	public function getLanguagesForNotification(int $notification_id): array {
		/**
		 * @var AbstractNotificationLanguage[] $array
		 */

		$array = $this->language_class::where([ "notification_id" => $notification_id ])->get();

		$languages = [];

		foreach ($array as $language) {
			$languages[$language->getLanguage()] = $language;
		}

		return $languages;
	}


	/**
	 * @param AbstractNotificationLanguage $language
	 */
	public function storeInstance(AbstractNotificationLanguage $language)/*: void*/ {
		$date = new ilDateTime(time(), IL_CAL_UNIX);

		if (empty($language->getId())) {
			$language->setCreatedAt($date);
		}

		$language->setUpdatedAt($date);

		$language->store();
	}
}
