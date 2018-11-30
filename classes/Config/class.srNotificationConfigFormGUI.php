<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Plugins\Notifications4Plugins\Utils\Notifications4PluginsTrait;

/**
 * Class srNotificationConfigFormGUI
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class srNotificationConfigFormGUI extends ilPropertyFormGUI {

	use DICTrait;
	use Notifications4PluginsTrait;
	const PLUGIN_CLASS_NAME = ilNotifications4PluginsPlugin::class;
	/**
	 * @var
	 */
	protected $parent_gui;
	/**
	 * @var srNotification
	 */
	protected $notification;


	/**
	 * @param                $parent_gui
	 * @param srNotification $notification
	 */
	public function __construct($parent_gui, srNotification $notification) {
		parent::__construct();

		$this->parent_gui = $parent_gui;
		$this->notification = $notification;
		$this->setFormAction(self::dic()->ctrl()->getFormAction($this->parent_gui));
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
		$method = $this->notification->getId() ? ilNotifications4PluginsConfigGUI::CMD_UPDATE : ilNotifications4PluginsConfigGUI::CMD_CREATE;
		$this->addCommandButton($method, $this->pl->txt(ilNotifications4PluginsConfigGUI::CMD_SAVE));
		$this->addCommandButton('cancel', $this->pl->txt(ilNotifications4PluginsConfigGUI::CMD_CANCEL));
	}
}
