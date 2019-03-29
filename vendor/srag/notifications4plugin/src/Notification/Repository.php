<?php

namespace srag\Notifications4Plugin\Notifications4Plugins\Notification;

use ilDateTime;
use srag\DIC\Notifications4Plugins\DICTrait;
use srag\DIC\Notifications4Plugins\Plugin\PluginInterface;
use srag\Notifications4Plugin\Notifications4Plugins\Ctrl\AbstractCtrl;
use srag\Notifications4Plugin\Notifications4Plugins\Notification\Language\AbstractNotificationLanguage;
use srag\Notifications4Plugin\Notifications4Plugins\Utils\Notifications4PluginTrait;

/**
 * Class Repository
 *
 * @package srag\Notifications4Plugin\Notifications4Plugins\Notification
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
	 * @param string $notification_class
	 * @param string $language_class
	 *
	 * @return self
	 */
	public static function getInstance(string $notification_class, string $language_class): self {
		if (!isset(self::$instances[$notification_class . "_" . $language_class])) {
			self::$instances[$notification_class . "_" . $language_class] = new self($notification_class, $language_class);
		}

		return self::$instances[$notification_class . "_" . $language_class];
	}


	/**
	 * @var string|AbstractNotification
	 */
	protected $notification_class;
	/**
	 * @var string
	 */
	protected $language_class;


	/**
	 * Repository constructor
	 *
	 * @param string $notification_class
	 * @param string $language_class
	 */
	private function __construct(string $notification_class, string $language_class) {
		$this->notification_class = $notification_class;
		$this->language_class = $language_class;
	}


	/**
	 * @param AbstractNotification $notification
	 */
	public function deleteNotification(AbstractNotification $notification)/*: void*/ {
		$notification->delete();

		foreach (self::notificationLanguage($this->language_class)->getLanguagesForNotification($notification->getId()) as $language) {
			self::notificationLanguage($this->language_class)->deleteLanguage($language);
		}
	}


	/**
	 * @param AbstractNotification $notification
	 * @param PluginInterface      $plugin
	 *
	 * @return AbstractNotification
	 */
	public function duplicateNotification(AbstractNotification $notification, PluginInterface $plugin): AbstractNotification {
		/**
		 * @var AbstractNotification $duplicated_notification
		 */

		$duplicated_notification = $notification->copy();

		$duplicated_notification->setTitle($duplicated_notification->getTitle() . " ("
			. $plugin->translate("duplicated", AbstractCtrl::LANG_MODULE_NOTIFICATIONS4PLUGIN) . ")");

		$languages = [];
		foreach (self::notificationLanguage($this->language_class)->getLanguagesForNotification($notification->getId()) as $language) {
			$languages[$language->getLanguage()] = self::notificationLanguage($this->language_class)->duplicateLanguage($language);
		}
		$duplicated_notification->setLanguages($languages);

		return $duplicated_notification;
	}


	/**
	 * @return Factory
	 */
	public function factory(): Factory {
		return Factory::getInstance($this->notification_class);
	}


	/**
	 * @param AbstractNotification[] $notifications
	 *
	 * @return array
	 */
	public function getArrayForSelection(array $notifications): array {
		$array = [];

		foreach ($notifications as $notification) {
			$array[$notification->getName()] = $notification->getTitle() . " (" . $notification->getName() . ")";
		}

		return $array;
	}


	/**
	 * @param AbstractNotification[] $notifications
	 *
	 * @return array
	 */
	public function getArrayForTable(array $notifications): array {
		$data = [];

		foreach ($notifications as $notification) {
			$row = [];
			$row["id"] = $notification->getId();
			$row["title"] = $notification->getTitle();
			$row["name"] = $notification->getName();
			$row["description"] = $notification->getDescription();
			$row["default_language"] = $notification->getDefaultLanguage();
			$row["languages"] = implode(", ", array_map(function (AbstractNotificationLanguage $language): string {
				return $language->getLanguage();
			}, $notification->getLanguages()));
			$data[] = $row;
		}

		return $data;
	}


	/**
	 * @param int $id
	 *
	 * @return AbstractNotification|null
	 */
	public function getNotificationById(int $id)/*: ?Notification*/ {
		/**
		 * @var AbstractNotification|null $notification
		 */

		$notification = $this->notification_class::where([ "id" => $id ])->first();

		return $notification;
	}


	/**
	 * @param string $name
	 *
	 * @return AbstractNotification|null
	 */
	public function getNotificationByName(string $name)/*: ?Notification*/ {
		/**
		 * @var AbstractNotification|null $notification
		 */

		$notification = $this->notification_class::where([ "name" => $name ])->first();

		return $notification;
	}


	/**
	 * @return AbstractNotification[]
	 */
	public function getNotifications(): array {
		/**
		 * @var AbstractNotification[] $notifications
		 */

		$notifications = $this->notification_class::orderBy("title", "ASC")->get();

		return $notifications;
	}


	/**
	 * @param AbstractNotification $notification
	 */
	public function storeInstance(AbstractNotification $notification)/*: void*/ {
		$date = new ilDateTime(time(), IL_CAL_UNIX);

		if (empty($notification->getId())) {
			$notification->setCreatedAt($date);
		}

		$notification->setUpdatedAt($date);

		$notification->store();

		foreach ($notification->getLanguages() as $language) {
			$language->setNotificationId($notification->getId());

			self::notificationLanguage($this->language_class)->storeInstance($language);
		}
	}
}
