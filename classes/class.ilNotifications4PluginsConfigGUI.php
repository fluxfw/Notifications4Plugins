<?php

require_once __DIR__ . '/../vendor/autoload.php';

use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Plugins\Notifications4Plugins\Notification\Notification;
use srag\Plugins\Notifications4Plugins\Notification\NotificationFormGUI;
use srag\Plugins\Notifications4Plugins\Notification\NotificationService;
use srag\Plugins\Notifications4Plugins\Notification\NotificationsTableGUI;
use srag\Plugins\Notifications4Plugins\Utils\Notifications4PluginsTrait;

/**
 * Class ilNotifications4PluginsConfigGUI
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class ilNotifications4PluginsConfigGUI extends ilPluginConfigGUI {

	use DICTrait;
	use Notifications4PluginsTrait;
	const PLUGIN_CLASS_NAME = ilNotifications4PluginsPlugin::class;
	const CMD_ADD = 'add';
	const CMD_CANCEL = 'cancel';
	const CMD_CONFIGURE = 'configure';
	const CMD_CONFIRM_DELETE = 'confirmDelete';
	const CMD_CREATE = 'create';
	const CMD_DELETE = 'delete';
	const CMD_EDIT = 'edit';
	const CMD_INDEX = 'index';
	const CMD_SAVE = 'save';
	const CMD_UPDATE = 'update';


	/**
	 * ilNotifications4PluginsConfigGUI constructor
	 */
	public function __construct() {

	}


	/**
	 * @param string $cmd
	 */
	public function performCommand($cmd) {
		$cmd = self::dic()->ctrl()->getCmd(self::CMD_INDEX);

		switch ($cmd) {
			case self::CMD_INDEX:
			case self::CMD_CANCEL:
			case self::CMD_CONFIGURE:
				$this->index();
				break;
			case self::CMD_SAVE:
			case self::CMD_EDIT:
			case self::CMD_CREATE:
			case self::CMD_UPDATE:
			case self::CMD_DELETE:
			case self::CMD_CONFIRM_DELETE:
			case self::CMD_ADD:
				$this->$cmd();
				break;
		}
	}


	/**
	 *
	 */
	public function index() {
		$button = ilLinkButton::getInstance();
		$button->setUrl(self::dic()->ctrl()->getLinkTarget($this, self::CMD_ADD));
		$button->setCaption(self::plugin()->translate('add_notification'), false);
		self::dic()->toolbar()->addButtonInstance($button);
		$table = new NotificationsTableGUI($this, self::CMD_INDEX);
		self::output()->output($table);
	}


	/**
	 *
	 */
	public function add() {
		$form = new NotificationFormGUI($this, new Notification());
		self::output()->output($form);
	}


	/**
	 *
	 */
	public function edit() {
		$form = new NotificationFormGUI($this, Notification::findOrFail((int)$_GET['notification_id']));
		self::output()->output($form);
	}


	/**
	 *
	 */
	public function create() {
		$form = new NotificationFormGUI($this, new Notification());
		if ($form->checkInput()) {
			$service = new NotificationService();
			$service->create($form->getInput('title'), $form->getInput('description'), $form->getInput('name'), $form->getInput('default_language'), $this->getNotificationData($form));
			ilUtil::sendSuccess(self::plugin()->translate('created_notification'), true);
			self::dic()->ctrl()->redirect($this);
		}

		$form->setValuesByPost();
		self::output()->output($form);
	}


	/**
	 *
	 */
	public function update() {
		/** @var Notification $notification */
		$notification = Notification::findOrFail((int)$_POST['notification_id']);
		$form = new NotificationFormGUI($this, $notification);
		if ($form->checkInput()) {
			$service = new NotificationService($notification);
			$service->update($form->getInput('title'), $form->getInput('description'), $form->getInput('name'), $form->getInput('default_language'), $this->getNotificationData($form, $notification));
			ilUtil::sendSuccess(self::plugin()->translate('updated_notification'), true);
			self::dic()->ctrl()->redirect($this);
		}

		$form->setValuesByPost();
		self::output()->output($form);
	}


	/**
	 *
	 */
	public function confirmDelete() {
		$notification = Notification::findOrFail((int)$_GET['notification_id']);
		$gui = new ilConfirmationGUI();
		$gui->setHeaderText(self::plugin()->translate('delete_confirm'));
		$gui->setFormAction(self::dic()->ctrl()->getFormAction($this));
		$gui->setCancel(self::plugin()->translate('cancel'), self::CMD_CANCEL);
		$gui->setConfirm(self::plugin()->translate('delete'), self::CMD_DELETE);
		$gui->addItem('notification_id', $notification->getId(), $notification->getTitle());
		self::output()->output($gui);
	}


	/**
	 *
	 */
	public function delete() {
		$notification = Notification::findOrFail((int)$_POST['notification_id']);
		$notification->delete();
		ilUtil::sendSuccess(self::plugin()->translate('deleted_notification'), true);
		self::dic()->ctrl()->redirect($this);
	}


	/**
	 * @param NotificationFormGUI $form
	 * @param Notification        $notification
	 *
	 * @return array
	 */
	protected function getNotificationData(NotificationFormGUI $form, Notification $notification = null) {
		$data = array();
		// New language added
		if ($form->getInput('language')) {
			$new = array();
			$new['subject'] = $form->getInput('subject_');
			$new['text'] = $form->getInput('text_');
			$new['language'] = $form->getInput('language');
			$data[] = $new;
		}

		// Update existing
		if ($notification) {
			foreach ($notification->getLanguages() as $language) {
				$update = array();
				$update['subject'] = $form->getInput('subject_' . $language);
				$update['text'] = $form->getInput('text_' . $language);
				$update['language'] = $language;
				$data[] = $update;
			}
		}

		return $data;
	}
}
