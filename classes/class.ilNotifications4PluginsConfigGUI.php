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
		$form = new NotificationFormGUI($this, self::notification()->newInstance());

		self::output()->output($form);
	}


	/**
	 *
	 */
	public function edit() {
		$form = new NotificationFormGUI($this, self::notification()->getNotificationById($_GET['notification_id']));

		self::output()->output($form);
	}


	/**
	 *
	 */
	public function create() {
		$form = new NotificationFormGUI($this, self::notification()->newInstance());

		if ($form->checkInput()) {
			$this->storeNotification($form->getInput('title'), $form->getInput('description'), $form->getInput('name'), $form->getInput('default_language'), $this->getNotificationData($form));

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
		$notification = self::notification()->getNotificationById($_POST['notification_id']);

		$form = new NotificationFormGUI($this, $notification);

		if ($form->checkInput()) {
			$this->storeNotification($form->getInput('title'), $form->getInput('description'), $form->getInput('name'), $form->getInput('default_language'), $this->getNotificationData($form, $notification), $notification);

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
		$notification = self::notification()->getNotificationById($_GET['notification_id']);

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
		$notification = self::notification()->getNotificationById($_POST['notification_id']);

		self::notification()->deleteNotification($notification);

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


	/**
	 * @param string            $title
	 * @param string            $description
	 * @param string            $name
	 * @param string            $default_language
	 * @param array             $texts
	 * @param Notification|null $notification
	 */
	protected function storeNotification($title, $description, $name, $default_language, array $texts = array(), Notification $notification = null) {
		if ($notification === null) {
			$notification = self::notification()->newInstance();
		}

		$notification->setTitle($title);

		$notification->setDefaultLanguage($default_language);

		$notification->setDescription($description);

		$notification->setName($name);

		self::notification()->storeInstance($notification);

		foreach ($texts as $text) {
			$notification->setText($text['text'], $text['language']);
			$notification->setSubject($text['subject'], $text['language']);
		}

		self::notification()->storeInstance($notification);
	}
}
