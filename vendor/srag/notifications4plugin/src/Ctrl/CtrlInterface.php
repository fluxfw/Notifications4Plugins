<?php

namespace srag\Notifications4Plugin\Notifications4Plugins\Ctrl;

/**
 * Interface CtrlInterface
 *
 * @package srag\Notifications4Plugin\Notifications4Plugins\Ctrl
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
interface CtrlInterface {

	const TAB_NOTIFICATIONS = "notifications";
	const TAB_NOTIFICATION = "notification";
	const LANG_MODULE_NOTIFICATIONS4PLUGIN = "notifications4plugin";
	const NAME = "Notifications4Plugin";
	const CMD_ADD_NOTIFICATION = "addNotification";
	const CMD_APPLY_FILTER = "applyFilter";
	const CMD_CREATE_NOTIFICATION = "createNotification";
	const CMD_DELETE_NOTIFICATION = "deleteNotification";
	const CMD_DELETE_NOTIFICATION_CONFIRM = "deleteNotificationConfirm";
	const CMD_DUPLICATE_NOTIFICATION = "duplicateNotification";
	const CMD_EDIT_NOTIFICATION = "editNotification";
	const CMD_LIST_NOTIFICATIONS = "listNotifications";
	const CMD_RESET_FILTER = "resetFilter";
	const CMD_UPDATE_NOTIFICATION = "updateNotification";
	const GET_PARAM = "notification_id";


	/**
	 *
	 */
	public function executeCommand()/*: void*/
	;
}
