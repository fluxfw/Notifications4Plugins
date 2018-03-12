<?php

require_once('./Services/Component/classes/class.ilPluginConfigGUI.php');
require_once(dirname(__FILE__) . '/Config/class.srNotificationConfigFormGUI.php');
require_once(dirname(__FILE__) . '/Config/class.srNotificationTableGUI.php');
require_once(dirname(__FILE__) . '/Notification/class.srNotificationService.php');
require_once('./Services/UIComponent/Button/classes/class.ilLinkButton.php');
require_once('./Services/Utilities/classes/class.ilConfirmationGUI.php');

/**
 * Class ilNotifications4PluginsConfigGUI
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class ilNotifications4PluginsConfigGUI extends ilPluginConfigGUI {

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
	 * @var ilNotifications4PluginsPlugin
	 */
	protected $pl;
	/**
	 * @var ilCtrl
	 */
	protected $ctrl;
	/**
	 * @var ilTemplate
	 */
	protected $tpl;
	/**
	 * @var ilToolbarGUI
	 */
	protected $toolbar;


	public function __construct() {
		global $DIC;

		$this->pl = ilNotifications4PluginsPlugin::getInstance();
		$this->ctrl = $DIC->ctrl();
		$this->tpl = $DIC->ui()->mainTemplate();
		$this->toolbar = $DIC->toolbar();
	}


	/**
	 * @param $cmd
	 */
	public function performCommand($cmd) {
		$cmd = $this->ctrl->getCmd(self::CMD_INDEX);

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
		$button->setUrl($this->ctrl->getLinkTarget($this, self::CMD_ADD));
		$button->setCaption($this->pl->txt('add_notification'), false);
		$this->toolbar->addButtonInstance($button);
		$table = new srNotificationTableGUI($this, self::CMD_INDEX);
		$this->tpl->setContent($table->getHTML());
	}


	public function add() {
		$form = new srNotificationConfigFormGUI($this, new srNotification());
		$this->tpl->setContent($form->getHTML());
	}


	public function edit() {
		$form = new srNotificationConfigFormGUI($this, srNotification::findOrFail((int)$_GET['notification_id']));
		$this->tpl->setContent($form->getHTML());
	}


	public function create() {
		$form = new srNotificationConfigFormGUI($this, new srNotification());
		if ($form->checkInput()) {
			$service = new srNotificationService();
			$service->create($form->getInput('title'), $form->getInput('description'), $form->getInput('name'), $form->getInput('default_language'), $this->getNotificationData($form));
			ilUtil::sendSuccess($this->pl->txt('created_notification'), true);
			$this->ctrl->redirect($this);
		}

		$form->setValuesByPost();
		$this->tpl->setContent($form->getHTML());
	}


	public function update() {
		/** @var srNotification $notification */
		$notification = srNotification::findOrFail((int)$_POST['notification_id']);
		$form = new srNotificationConfigFormGUI($this, $notification);
		if ($form->checkInput()) {
			$service = new srNotificationService($notification);
			$service->update($form->getInput('title'), $form->getInput('description'), $form->getInput('name'), $form->getInput('default_language'), $this->getNotificationData($form, $notification));
			ilUtil::sendSuccess($this->pl->txt('updated_notification'), true);
			$this->ctrl->redirect($this);
		}

		$form->setValuesByPost();
		$this->tpl->setContent($form->getHTML());
	}


	public function confirmDelete() {
		$notification = srNotification::findOrFail((int)$_GET['notification_id']);
		$gui = new ilConfirmationGUI();
		$gui->setHeaderText($this->pl->txt('delete_confirm'));
		$gui->setFormAction($this->ctrl->getFormAction($this));
		$gui->setCancel($this->pl->txt('cancel'), self::CMD_CANCEL);
		$gui->setConfirm($this->pl->txt('delete'), self::CMD_DELETE);
		$gui->addItem('notification_id', $notification->getId(), $notification->getTitle());
		$this->tpl->setContent($gui->getHTML());
	}


	public function delete() {
		$notification = srNotification::findOrFail((int)$_POST['notification_id']);
		$notification->delete();
		ilUtil::sendSuccess($this->pl->txt('deleted_notification'), true);
		$this->ctrl->redirect($this);
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