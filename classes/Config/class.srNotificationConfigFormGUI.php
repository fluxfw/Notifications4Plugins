<?php
require_once('./Services/Form/classes/class.ilPropertyFormGUI.php');
require_once('./Services/Form/classes/class.ilMultiSelectInputGUI.php');

/**
 * Class srNotificationConfigFormGUI
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class srNotificationConfigFormGUI extends ilPropertyFormGUI {

	/**
	 * @var
	 */
	protected $parent_gui;
	/**
	 * @var  ilCtrl
	 */
	protected $ctrl;
	/**
	 * @var ilNotifications4PluginsPlugin
	 */
	protected $pl;
	/**
	 * @var ilLanguage
	 */
	protected $lng;
	/**
	 * @var srNotification
	 */
	protected $notification;


	/**
	 * @param                $parent_gui
	 * @param srNotification $notification
	 */
	public function __construct($parent_gui, srNotification $notification) {
		global $ilCtrl, $lng;

		$this->parent_gui = $parent_gui;
		$this->ctrl = $ilCtrl;
		$this->lng = $lng;
		$this->pl = ilNotifications4PluginsPlugin::getInstance();
		$this->notification = $notification;
		$this->setFormAction($this->ctrl->getFormAction($this->parent_gui));
		$this->initForm();
	}


	protected function initForm() {
		$this->setTitle($this->pl->txt('general'));

		if ($id = $this->notification->getId()) {
			$item = new ilNonEditableValueGUI($this->pl->txt('id'));
			$item->setValue($id);
			$this->addItem($item);
		}

		$item = new ilTextInputGUI($this->pl->txt('name'), 'name');
		$item->setRequired(true);
		$item->setValue($this->notification->getName());
		$item->setInfo($this->pl->txt('name_info'));
		$this->addItem($item);

		$item = new ilTextInputGUI($this->pl->txt('title'), 'title');
		$item->setRequired(true);
		$item->setValue($this->notification->getTitle());
		$this->addItem($item);

		$item = new ilTextAreaInputGUI($this->pl->txt('description'), 'description');
		$item->setValue($this->notification->getDescription());
		$this->addItem($item);

		$item = new ilTextInputGUI($this->pl->txt('default_language'), 'default_language');
		$item->setInfo($this->pl->txt('default_language_name'));
		$item->setValue($this->notification->getDefaultLanguage());
		$item->setRequired(true);
		$this->addItem($item);

		$item = new ilHiddenInputGUI('notification_id');
		$item->setValue($this->notification->getId());
		$this->addItem($item);

		foreach ($this->notification->getLanguages() as $language) {
			$this->addInputsForLanguage($language);
		}

		// For a new language
		$this->addInputsForLanguage();

		$this->addCommandButtons();
	}


	/**
	 * @param string $language
	 */
	protected function addInputsForLanguage($language = '') {
		$section = new ilFormSectionHeaderGUI();
		$section->setTitle($language ? strtoupper($language) : $this->pl->txt('add_new_language'));
		$this->addItem($section);

		if (!$language) {
			$item = new ilTextInputGUI($this->pl->txt('language'), 'language');
			$this->addItem($item);
		}

		$item = new ilTextInputGUI($this->pl->txt('subject'), 'subject_' . $language);
		$item->setValue($language ? $this->notification->getSubject($language) : '');
		$this->addItem($item);

		$item = new ilTextAreaInputGUI($this->pl->txt('text'), 'text_' . $language);
		$item->setValue($language ? $this->notification->getText($language) : '');
		$this->addItem($item);
	}


	protected function addCommandButtons() {
		$method = $this->notification->getId() ? 'update' : 'create';
		$this->addCommandButton($method, $this->pl->txt('save'));
		$this->addCommandButton('cancel', $this->pl->txt('cancel'));
	}
}