<?php

namespace srag\Plugins\Notifications4Plugins\Config;

use ilFormSectionHeaderGUI;
use ilHiddenInputGUI;
use ilNonEditableValueGUI;
use ilNotifications4PluginsConfigGUI;
use ilNotifications4PluginsPlugin;
use ilPropertyFormGUI;
use ilTextAreaInputGUI;
use ilTextInputGUI;
use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Plugins\Notifications4Plugins\Notification\srNotification;
use srag\Plugins\Notifications4Plugins\Utils\Notifications4PluginsTrait;

/**
 * Class srNotificationConfigFormGUI
 *
 * @package srag\Plugins\Notifications4Plugins\Config
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
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
		$this->setTitle(self::plugin()->translate('general'));

		if ($id = $this->notification->getId()) {
			$item = new ilNonEditableValueGUI(self::plugin()->translate('id'));
			$item->setValue($id);
			$this->addItem($item);
		}

		$item = new ilTextInputGUI(self::plugin()->translate('name'), 'name');
		$item->setRequired(true);
		$item->setValue($this->notification->getName());
		$item->setInfo(self::plugin()->translate('name_info'));
		$this->addItem($item);

		$item = new ilTextInputGUI(self::plugin()->translate('title'), 'title');
		$item->setRequired(true);
		$item->setValue($this->notification->getTitle());
		$this->addItem($item);

		$item = new ilTextAreaInputGUI(self::plugin()->translate('description'), 'description');
		$item->setValue($this->notification->getDescription());
		$this->addItem($item);

		$item = new ilTextInputGUI(self::plugin()->translate('default_language'), 'default_language');
		$item->setInfo(self::plugin()->translate('default_language_name'));
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
		$section->setTitle($language ? strtoupper($language) : self::plugin()->translate('add_new_language'));
		$this->addItem($section);

		if (!$language) {
			$item = new ilTextInputGUI(self::plugin()->translate('language'), 'language');
			$this->addItem($item);
		}

		$item = new ilTextInputGUI(self::plugin()->translate('subject'), 'subject_' . $language);
		$item->setValue($language ? $this->notification->getSubject($language) : '');
		$this->addItem($item);

		$item = new ilTextAreaInputGUI(self::plugin()->translate('text'), 'text_' . $language);
		$item->setRows(10);
		$item->setValue($language ? $this->notification->getText($language) : '');
		$item->setInfo('https://twig.symfony.com/doc/1.x/templates.html');
		$this->addItem($item);
	}


	protected function addCommandButtons() {
		$method = $this->notification->getId() ? ilNotifications4PluginsConfigGUI::CMD_UPDATE : ilNotifications4PluginsConfigGUI::CMD_CREATE;
		$this->addCommandButton($method, self::plugin()->translate(ilNotifications4PluginsConfigGUI::CMD_SAVE));
		$this->addCommandButton('cancel', self::plugin()->translate(ilNotifications4PluginsConfigGUI::CMD_CANCEL));
	}
}
