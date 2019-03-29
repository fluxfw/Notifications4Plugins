<?php

namespace srag\Notifications4Plugin\Notifications4Plugins\UI;

use ilConfirmationGUI;
use ilSelectInputGUI;
use srag\CustomInputGUIs\Notifications4Plugins\PropertyFormGUI\PropertyFormGUI;
use srag\DIC\Notifications4Plugins\DICTrait;
use srag\DIC\Notifications4Plugins\Plugin\Pluginable;
use srag\DIC\Notifications4Plugins\Plugin\PluginInterface;
use srag\Notifications4Plugin\Notifications4Plugins\Ctrl\AbstractCtrl;
use srag\Notifications4Plugin\Notifications4Plugins\Notification\AbstractNotification;
use srag\Notifications4Plugin\Notifications4Plugins\Utils\Notifications4PluginTrait;

/**
 * Class UI
 *
 * @package srag\Notifications4Plugin\Notifications4Plugins\UI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class UI implements Pluginable {

	use DICTrait;
	use Notifications4PluginTrait;
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
	 * @var AbstractCtrl
	 */
	protected $ctrl_class;
	/**
	 * @var PluginInterface|null
	 */
	protected $plugin = null;


	/**
	 * UI constructor
	 */
	private function __construct() {

	}


	/**
	 * @param AbstractCtrl $ctrl_class
	 *
	 * @return self
	 */
	public function withCtrlClass(AbstractCtrl $ctrl_class): self {
		$this->ctrl_class = $ctrl_class;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function getPlugin(): PluginInterface {
		return $this->plugin;
	}


	/**
	 * @inheritdoc
	 */
	public function withPlugin(PluginInterface $plugin): self {
		$this->plugin = $plugin;

		return $this;
	}


	/**
	 * @param AbstractCtrl         $parent
	 * @param AbstractNotification $notification
	 *
	 * @return ilConfirmationGUI
	 */
	public function notificationDeleteConfirmation(AbstractCtrl $parent, AbstractNotification $notification) {
		$confirmation = new ilConfirmationGUI();

		self::dic()->ctrl()->setParameter($parent, AbstractCtrl::GET_PARAM, $notification->getId());
		$confirmation->setFormAction(self::dic()->ctrl()->getFormAction($parent));
		self::dic()->ctrl()->setParameter($parent, AbstractCtrl::GET_PARAM, null);

		$confirmation->setHeaderText($this->getPlugin()
			->translate("delete_notification_confirm", AbstractCtrl::LANG_MODULE_NOTIFICATIONS4PLUGIN, [ $notification->getTitle() ]));

		$confirmation->addItem(AbstractCtrl::GET_PARAM, $notification->getId(), $notification->getTitle());

		$confirmation->setConfirm($this->getPlugin()
			->translate("delete", AbstractCtrl::LANG_MODULE_NOTIFICATIONS4PLUGIN), AbstractCtrl::CMD_DELETE_NOTIFICATION);
		$confirmation->setCancel($this->getPlugin()
			->translate("cancel", AbstractCtrl::LANG_MODULE_NOTIFICATIONS4PLUGIN), AbstractCtrl::CMD_LIST_NOTIFICATIONS);

		return $confirmation;
	}


	/**
	 * @param AbstractCtrl         $parent
	 * @param AbstractNotification $notification
	 *
	 * @return NotificationFormGUI
	 */
	public function notificationForm(AbstractCtrl $parent, AbstractNotification $notification): NotificationFormGUI {
		$form = new NotificationFormGUI($this->getPlugin(), $parent, $notification);

		return $form;
	}


	/**
	 * @param AbstractCtrl $parent
	 * @param string       $parent_cmd
	 * @param callable     $getNotifications
	 *
	 * @return NotificationsTableGUI
	 */
	public function notificationTable(AbstractCtrl $parent, string $parent_cmd, callable $getNotifications): NotificationsTableGUI {
		$table = new NotificationsTableGUI($this->getPlugin(), $parent, $parent_cmd, $getNotifications);

		return $table;
	}


	/**
	 * @param array  $notifications
	 * @param string $post_key
	 * @param array  $placeholder_types
	 *
	 * @return array
	 */
	public function templateSelection(array $notifications, string $post_key, array $placeholder_types): array {
		return [
			$post_key => [
				PropertyFormGUI::PROPERTY_CLASS => ilSelectInputGUI::class,
				PropertyFormGUI::PROPERTY_REQUIRED => true,
				PropertyFormGUI::PROPERTY_OPTIONS => [ "" => "" ] + $notifications,
				"setTitle" => $this->getPlugin()
					->translate("template_selection", AbstractCtrl::LANG_MODULE_NOTIFICATIONS4PLUGIN, [ AbstractCtrl::NAME ]),
				"setInfo" => self::output()->getHTML([
					$this->getPlugin()->translate("template_selection_info", AbstractCtrl::LANG_MODULE_NOTIFICATIONS4PLUGIN, [ AbstractCtrl::NAME ]),
					"<br><br>",
					self::dic()->ui()->factory()->listing()->descriptive($placeholder_types)
				])
			]
		];
	}
}
