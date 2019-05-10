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
final class Repository implements RepositoryInterface {

	use DICTrait;
	use Notifications4PluginTrait;
	/**
	 * @var RepositoryInterface[]
	 */
	protected static $instances = [];


	/**
	 * @param string $language_class
	 *
	 * @return RepositoryInterface
	 */
	public static function getInstance(string $language_class): RepositoryInterface {
		if (!isset(self::$instances[$language_class])) {
			self::$instances[$language_class] = new self($language_class);
		}

		return self::$instances[$language_class];
	}


	/**
	 * @var string|NotificationLanguage
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
	 * @inheritdoc
	 */
	public function deleteLanguage(NotificationLanguage $language)/*: void*/ {
		$language->delete();
	}


	/**
	 * @inheritdoc
	 */
	public function duplicateLanguage(NotificationLanguage $language): NotificationLanguage {
		/**
		 * @var NotificationLanguage $duplicated_language
		 */

		$duplicated_language = $language->copy();

		return $duplicated_language;
	}


	/**
	 * @inheritdoc
	 */
	public function factory(): FactoryInterface {
		return Factory::getInstance($this->language_class);
	}


	/**
	 * @inheritdoc
	 */
	public function getLanguageById(int $id)/*: ?NotificationLanguage*/ {
		/**
		 * @var NotificationLanguage|null $language
		 */

		$language = call_user_func($this->language_class . "::where", [ "id" => $id ])->first();

		return $language;
	}


	/**
	 * @inheritdoc
	 */
	public function getLanguageForNotification(int $notification_id, string $language): NotificationLanguage {
		/**
		 * @var NotificationLanguage $l
		 */

		$l = call_user_func($this->language_class . "::where", [ "notification_id" => $notification_id, "language" => $language ])->first();

		if ($l === null) {
			$l = $this->factory()->newInstance();

			$l->setNotificationId($notification_id);

			$l->setLanguage($language);
		}

		return $l;
	}


	/**
	 * @inheritdoc
	 */
	public function getLanguagesForNotification(int $notification_id): array {
		/**
		 * @var NotificationLanguage[] $array
		 */

		$array = call_user_func($this->language_class . "::where", [ "notification_id" => $notification_id ])->get();

		$languages = [];

		foreach ($array as $language) {
			$languages[$language->getLanguage()] = $language;
		}

		return $languages;
	}


	/**
	 * @inheritdoc
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
