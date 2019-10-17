<?php

namespace srag\Notifications4Plugin\Notifications4Plugins\Ctrl;

use ilConfirmationGUI;
use ilUtil;
use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Notifications4Plugin\Notifications4Plugins\Notification\Language\Repository as NotificationLanguageRepository;
use srag\Notifications4Plugin\Notifications4Plugins\Notification\Language\RepositoryInterface as NotificationLanguageRepositoryInterface;
use srag\Notifications4Plugin\Notifications4Plugins\Notification\Notification;
use srag\Notifications4Plugin\Notifications4Plugins\Notification\Repository as NotificationRepository;
use srag\Notifications4Plugin\Notifications4Plugins\Notification\RepositoryInterface as NotificationRepositoryInterface;
use srag\Notifications4Plugin\Notifications4Plugins\UI\NotificationFormGUI;
use srag\Notifications4Plugin\Notifications4Plugins\UI\NotificationsTableGUI;
use srag\Notifications4Plugin\Notifications4Plugins\Utils\Notifications4PluginTrait;

/**
 * Class AbstractCtrl
 *
 * @package srag\Notifications4Plugin\Notifications4Plugins\Ctrl
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
abstract class AbstractCtrl implements CtrlInterface {

	use DICTrait;
	use Notifications4PluginTrait;
	/**
	 * @var string
	 *
	 * @abstract
	 */
	const NOTIFICATION_CLASS_NAME = "";
	/**
	 * @var string
	 *
	 * @abstract
	 */
	const LANGUAGE_CLASS_NAME = "";


	/**
	 * @inheritdoc
	 */
	protected static function notification(): NotificationRepositoryInterface {
		return NotificationRepository::getInstance(static::NOTIFICATION_CLASS_NAME, static::LANGUAGE_CLASS_NAME);
	}


	/**
	 * @inheritdoc
	 */
	protected static function notificationLanguage(): NotificationLanguageRepositoryInterface {
		return NotificationLanguageRepository::getInstance(static::LANGUAGE_CLASS_NAME);
	}


	/**
	 * AbstractCtrl constructor
	 */
	public function __construct() {

	}


	/**
	 * @inheritdoc
	 */
	public function executeCommand()/*: void*/ {
		$cmd = self::dic()->ctrl()->getCmd();

		switch ($cmd) {
			case self::CMD_ADD_NOTIFICATION:
			case self::CMD_APPLY_FILTER:
			case self::CMD_CREATE_NOTIFICATION:
			case self::CMD_DELETE_NOTIFICATION:
			case self::CMD_DELETE_NOTIFICATION_CONFIRM:
			case self::CMD_DUPLICATE_NOTIFICATION:
			case self::CMD_EDIT_NOTIFICATION:
			case self::CMD_LIST_NOTIFICATIONS:
			case self::CMD_RESET_FILTER:
			case self::CMD_UPDATE_NOTIFICATION:
				$this->{$cmd}();
				break;

			default:
				break;
		}
	}


	/**
	 *
	 */
	protected function listNotifications()/*: void*/ {
		$table = $this->getNotificationsTable();

		self::output()->output($table);
	}


	/**
	 *
	 */
	protected function applyFilter()/*: void*/ {
		$table = $this->getNotificationsTable(self::CMD_APPLY_FILTER);

		$table->writeFilterToSession();

		$table->resetOffset();

		//$this->redirect(self::CMD_LIST_NOTIFICATIONS);
		$this->listNotifications(); // Fix reset offset
	}


	/**
	 *
	 */
	protected function resetFilter()/*: void*/ {
		$table = $this->getNotificationsTable(self::CMD_RESET_FILTER);

		$table->resetOffset();

		$table->resetFilter();

		//$this->redirect(self::CMD_LIST_NOTIFICATIONS);
		$this->listNotifications(); // Fix reset offset
	}


	/**
	 *
	 */
	protected function addNotification()/*: void*/ {
		$notification = self::notification()->factory()->newInstance();

		$form = $this->getNotificationForm($notification);

		self::output()->output($form);
	}


	/**
	 *
	 */
	protected function createNotification()/*: void*/ {
		$notification = self::notification()->factory()->newInstance();

		$form = $this->getNotificationForm($notification);

		if (!$form->storeForm()) {
			self::output()->output($form);

			return;
		}

		self::notification()->storeInstance($form->getObject());

		ilUtil::sendSuccess(self::plugin()->translate("added_notification", self::LANG_MODULE_NOTIFICATIONS4PLUGIN, [
			$form->getObject()->getTitle()
		]), true);

		$this->redirect(self::CMD_LIST_NOTIFICATIONS);
	}


	/**
	 *
	 */
	protected function editNotification()/*: void*/ {
		$notification = $this->getNotification();

		$form = $this->getNotificationForm($notification);

		self::output()->output($form);
	}


	/**
	 *
	 */
	protected function updateNotification()/*: void*/ {
		$notification = $this->getNotification();

		$form = $this->getNotificationForm($notification);

		if (!$form->storeForm()) {
			self::output()->output($form);

			return;
		}

		self::notification()->storeInstance($form->getObject());

		ilUtil::sendSuccess(self::plugin()->translate("saved_notification", self::LANG_MODULE_NOTIFICATIONS4PLUGIN, [
			$form->getObject()->getTitle()
		]), true);

		$this->redirect(self::CMD_LIST_NOTIFICATIONS);
	}


	/**
	 *
	 */
	protected function duplicateNotification()/*: void*/ {
		$notification = $this->getNotification();

		$cloned_notification = self::notification()->duplicateNotification($notification, self::plugin());

		self::notification()->storeInstance($cloned_notification);

		ilUtil::sendSuccess(self::plugin()
			->translate("duplicated_notification", self::LANG_MODULE_NOTIFICATIONS4PLUGIN, [ $notification->getTitle() ]), true);

		$this->redirect(self::CMD_LIST_NOTIFICATIONS);
	}


	/**
	 *
	 */
	protected function deleteNotificationConfirm()/*: void*/ {
		$notification = $this->getNotification();

		$confirmation = $this->getNotificationDeleteConfirmation($notification);

		self::output()->output($confirmation);
	}


	/**
	 *
	 */
	protected function deleteNotification()/*: void*/ {
		$notification = $this->getNotification();

		self::notification()->deleteNotification($notification);

		ilUtil::sendSuccess(self::plugin()
			->translate("deleted_notification", self::LANG_MODULE_NOTIFICATIONS4PLUGIN, [ $notification->getTitle() ]), true);

		$this->redirect(self::CMD_LIST_NOTIFICATIONS);
	}


	/**
	 * @param string $parent_cmd
	 *
	 * @return NotificationsTableGUI
	 */
	protected function getNotificationsTable(string $parent_cmd = self::CMD_LIST_NOTIFICATIONS): NotificationsTableGUI {
		return self::notificationUI()->withPlugin(self::plugin())->withCtrlClass($this)->notificationTable($parent_cmd, function (): array {
			return self::notification()->getArrayForTable($this->getNotifications());
		});
	}


	/**
	 * @param Notification $notification
	 *
	 * @return NotificationFormGUI
	 */
	protected function getNotificationForm(Notification $notification): NotificationFormGUI {
		return self::notificationUI()->withPlugin(self::plugin())->withCtrlClass($this)->notificationForm($notification);
	}


	/**
	 * @param Notification $notification
	 *
	 * @return ilConfirmationGUI
	 */
	protected function getNotificationDeleteConfirmation(Notification $notification): ilConfirmationGUI {
		return self::notificationUI()->withPlugin(self::plugin())->withCtrlClass($this)->notificationDeleteConfirmation($notification);
	}


	/**
	 * @return array
	 */
	protected function getNotifications(): array {
		return self::notification()->getNotifications();
	}


	/**
	 * @return Notification
	 */
	protected function getNotification(): Notification {
		$notification_id = intval(filter_input(INPUT_GET, self::GET_PARAM));

		return self::notification()->getNotificationById($notification_id);
	}


	/**
	 * @param string $cmd
	 */
	protected function redirect(string $cmd)/*: void*/ {
		self::dic()->ctrl()->redirect($this, $cmd);
	}
}
