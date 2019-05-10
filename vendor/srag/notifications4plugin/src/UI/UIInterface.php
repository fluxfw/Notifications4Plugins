<?php

namespace srag\Notifications4Plugin\Notifications4Plugins\UI;

use ilConfirmationGUI;
use srag\DIC\Notifications4Plugins\Plugin\Pluginable;
use srag\Notifications4Plugin\Notifications4Plugins\Ctrl\CtrlInterface;
use srag\Notifications4Plugin\Notifications4Plugins\Notification\Notification;

/**
 * Interface UIInterface
 *
 * @package srag\Notifications4Plugin\Notifications4Plugins\UI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface UIInterface extends Pluginable {

	/**
	 * @param CtrlInterface $ctrl_class
	 *
	 * @return self
	 */
	public function withCtrlClass(CtrlInterface $ctrl_class): self;


	/**
	 * @param Notification $notification
	 *
	 * @return ilConfirmationGUI
	 */
	public function notificationDeleteConfirmation(Notification $notification): ilConfirmationGUI;


	/**
	 * @param Notification $notification
	 *
	 * @return NotificationFormGUI
	 */
	public function notificationForm(Notification $notification): NotificationFormGUI;


	/**
	 * @param string   $parent_cmd
	 * @param callable $getNotifications
	 *
	 * @return NotificationsTableGUI
	 */
	public function notificationTable(string $parent_cmd, callable $getNotifications): NotificationsTableGUI;


	/**
	 * @param array  $notifications
	 * @param string $post_key
	 * @param array  $placeholder_types
	 *
	 * @return array
	 */
	public function templateSelection(array $notifications, string $post_key, array $placeholder_types): array;
}
