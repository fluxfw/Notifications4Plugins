<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\ActiveRecordConfig\Notifications4Plugins\ActiveRecordConfigGUI;
use srag\Plugins\Notifications4Plugins\Notification\Notification;
use srag\Plugins\Notifications4Plugins\Notification\NotificationFormGUI;
use srag\Plugins\Notifications4Plugins\Notification\NotificationsTableGUI;
use srag\Plugins\Notifications4Plugins\Utils\Notifications4PluginsTrait;

/**
 * Class ilNotifications4PluginsConfigGUI
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class ilNotifications4PluginsConfigGUI extends ActiveRecordConfigGUI {

	use Notifications4PluginsTrait;
	const PLUGIN_CLASS_NAME = ilNotifications4PluginsPlugin::class;
	const TAB_NOTIFICATIONS = "notifications";
	const LANG_MODULE_NOTIFICATIONS4PLUGIN = "notifications4plugin";
	const CMD_ADD_NOTIFICATION = "addNotification";
	const CMD_CREATE_NOTIFICATION = "createNotification";
	const CMD_DELETE_NOTIFICATION = "deleteNotification";
	const CMD_DELETE_NOTIFICATION_CONFIRM = "deleteNotificationConfirm";
	const CMD_DUPLICATE_NOTIFICATION = "duplicateNotification";
	const CMD_EDIT_NOTIFICATION = "editNotification";
	const CMD_UPDATE_NOTIFICATION = "updateNotification";
	const GET_PARAM = "notification_id";
	/**
	 * @var array
	 */
	protected static $tabs = [ self::TAB_NOTIFICATIONS => NotificationsTableGUI::class ];
	/**
	 * @var array
	 */
	protected static $custom_commands = [
		self::CMD_ADD_NOTIFICATION,
		self::CMD_CREATE_NOTIFICATION,
		self::CMD_DELETE_NOTIFICATION,
		self::CMD_DELETE_NOTIFICATION_CONFIRM,
		self::CMD_DUPLICATE_NOTIFICATION,
		self::CMD_EDIT_NOTIFICATION,
		self::CMD_UPDATE_NOTIFICATION,
	];


	/**
	 * @param Notification $notification
	 *
	 * @return NotificationFormGUI
	 */
	protected function getNotificationForm(Notification $notification): NotificationFormGUI {
		$form = new NotificationFormGUI($this, self::TAB_NOTIFICATIONS, $notification);

		return $form;
	}


	/**
	 *
	 */
	public function addNotification() {
		self::dic()->tabs()->activateTab(self::TAB_NOTIFICATIONS);

		$notification = self::notification()->factory()->newInstance();

		$form = $this->getNotificationForm($notification);

		self::output()->output($form);
	}


	/**
	 *
	 */
	public function createNotification() {
		self::dic()->tabs()->activateTab(self::TAB_NOTIFICATIONS);

		$notification = self::notification()->factory()->newInstance();

		$form = $this->getNotificationForm($notification);

		if (!$form->storeForm()) {
			self::output()->output($form);

			return;
		}

		ilUtil::sendSuccess(self::plugin()->translate("added_notification", self::LANG_MODULE_NOTIFICATIONS4PLUGIN, [
			$form->getObject()->getTitle()
		]), true);

		$this->redirectToTab(self::TAB_NOTIFICATIONS);
	}


	/**
	 *
	 */
	public function editNotification() {
		self::dic()->tabs()->activateTab(self::TAB_NOTIFICATIONS);

		$notification_id = intval(filter_input(INPUT_GET, self::GET_PARAM));
		$notification = self::notification()->getNotificationById($notification_id);

		$form = $this->getNotificationForm($notification);

		self::output()->output($form);
	}


	/**
	 *
	 */
	public function updateNotification() {
		self::dic()->tabs()->activateTab(self::TAB_NOTIFICATIONS);

		$notification_id = intval(filter_input(INPUT_GET, self::GET_PARAM));
		$notification = self::notification()->getNotificationById($notification_id);

		$form = $this->getNotificationForm($notification);

		if (!$form->storeForm()) {
			self::output()->output($form);

			return;
		}

		ilUtil::sendSuccess(self::plugin()->translate("saved_notification", self::LANG_MODULE_NOTIFICATIONS4PLUGIN, [
			$form->getObject()->getTitle()
		]), true);

		$this->redirectToTab(self::TAB_NOTIFICATIONS);
	}


	/**
	 *
	 */
	public function duplicateNotification() {
		$notification_id = intval(filter_input(INPUT_GET, self::GET_PARAM));
		$notification = self::notification()->getNotificationById($notification_id);

		$cloned_notification = self::notification()->duplicateNotification($notification);

		self::notification()->storeInstance($cloned_notification);

		ilUtil::sendSuccess(self::plugin()
			->translate("duplicated_notification", self::LANG_MODULE_NOTIFICATIONS4PLUGIN, [ $notification->getTitle() ]), true);

		$this->redirectToTab(self::TAB_NOTIFICATIONS);
	}


	/**
	 *
	 */
	public function deleteNotificationConfirm() {
		self::dic()->tabs()->activateTab(self::TAB_NOTIFICATIONS);

		$notification_id = intval(filter_input(INPUT_GET, self::GET_PARAM));
		$notification = self::notification()->getNotificationById($notification_id);

		$confirmation = new ilConfirmationGUI();

		self::dic()->ctrl()->setParameter($this, self::GET_PARAM, $notification->getId());
		$confirmation->setFormAction(self::dic()->ctrl()->getFormAction($this));
		self::dic()->ctrl()->setParameter($this, self::GET_PARAM, null);

		$confirmation->setHeaderText(self::plugin()
			->translate("delete_notification_confirm", self::LANG_MODULE_NOTIFICATIONS4PLUGIN, [ $notification->getTitle() ]));

		$confirmation->addItem(self::GET_PARAM, $notification->getId(), $notification->getTitle());

		$confirmation->setConfirm(self::plugin()->translate("delete", self::LANG_MODULE_NOTIFICATIONS4PLUGIN), self::CMD_DELETE_NOTIFICATION);
		$confirmation->setCancel(self::plugin()
			->translate("cancel", self::LANG_MODULE_NOTIFICATIONS4PLUGIN), $this->getCmdForTab(self::TAB_NOTIFICATIONS));

		self::output()->output($confirmation);
	}


	/**
	 *
	 */
	public function deleteNotification() {
		$notification_id = intval(filter_input(INPUT_GET, self::GET_PARAM));
		$notification = self::notification()->getNotificationById($notification_id);

		self::notification()->deleteNotification($notification);

		ilUtil::sendSuccess(self::plugin()
			->translate("deleted_notification", self::LANG_MODULE_NOTIFICATIONS4PLUGIN, [ $notification->getTitle() ]), true);

		$this->redirectToTab(self::TAB_NOTIFICATIONS);
	}
}
