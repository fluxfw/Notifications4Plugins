<?php

require_once __DIR__ . '/../vendor/autoload.php';

use srag\DIC\Notifications4Plugins\DICTrait;
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
	 * @param $cmd
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
	 * Configure screen
	 */
	public function index() {
		$button = ilLinkButton::getInstance();
		$button->setUrl(self::dic()->ctrl()->getLinkTarget($this, self::CMD_ADD));
		$button->setCaption(self::plugin()->translate('add_notification'), false);
		self::dic()->toolbar()->addButtonInstance($button);
		$table = new srNotificationTableGUI($this, self::CMD_INDEX);
		self::output()->output($table);
	}


	public function add() {
		$form = new srNotificationConfigFormGUI($this, new srNotification());
		self::output()->output($form);
	}


	public function edit() {
		$form = new srNotificationConfigFormGUI($this, srNotification::findOrFail((int)$_GET['notification_id']));
		self::output()->output($form);
	}


	public function create() {
		$form = new srNotificationConfigFormGUI($this, new srNotification());
		if ($form->checkInput()) {
			$service = new srNotificationService();
			$service->create($form->getInput('title'), $form->getInput('description'), $form->getInput('name'), $form->getInput('default_language'), $this->getNotificationData($form));
			ilUtil::sendSuccess(self::plugin()->translate('created_notification'), true);
			self::dic()->ctrl()->redirect($this);
		}

		$form->setValuesByPost();
		self::output()->output($form);
	}


	public function update() {
		/** @var srNotification $notification */
		$notification = srNotification::findOrFail((int)$_POST['notification_id']);
		$form = new srNotificationConfigFormGUI($this, $notification);
		if ($form->checkInput()) {
			$service = new srNotificationService($notification);
			$service->update($form->getInput('title'), $form->getInput('description'), $form->getInput('name'), $form->getInput('default_language'), $this->getNotificationData($form, $notification));
			ilUtil::sendSuccess(self::plugin()->translate('updated_notification'), true);
			self::dic()->ctrl()->redirect($this);
		}

		$form->setValuesByPost();
		self::output()->output($form);
	}


	public function confirmDelete() {
		$notification = srNotification::findOrFail((int)$_GET['notification_id']);
		$gui = new ilConfirmationGUI();
		$gui->setHeaderText(self::plugin()->translate('delete_confirm'));
		$gui->setFormAction(self::dic()->ctrl()->getFormAction($this));
		$gui->setCancel(self::plugin()->translate('cancel'), self::CMD_CANCEL);
		$gui->setConfirm(self::plugin()->translate('delete'), self::CMD_DELETE);
		$gui->addItem('notification_id', $notification->getId(), $notification->getTitle());
		self::output()->output($gui);
	}


	public function delete() {
		$notification = srNotification::findOrFail((int)$_POST['notification_id']);
		$notification->delete();
		ilUtil::sendSuccess(self::plugin()->translate('deleted_notification'), true);
		self::dic()->ctrl()->redirect($this);
	}


	/**
	 * @param srNotificationConfigFormGUI $form
	 * @param srNotification              $notification
	 *
	 * @return array
	 */
	protected function getNotificationData(srNotificationConfigFormGUI $form, srNotification $notification = NULL) {
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
