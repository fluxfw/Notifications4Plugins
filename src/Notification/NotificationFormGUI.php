<?php

namespace srag\Plugins\Notifications4Plugins\Notification;

use ilFormSectionHeaderGUI;
use ilNonEditableValueGUI;
use ilNotifications4PluginsConfigGUI;
use ilNotifications4PluginsPlugin;
use ilTextAreaInputGUI;
use ilTextInputGUI;
use srag\ActiveRecordConfig\Notifications4Plugins\ActiveRecordObjectFormGUI;
use srag\Plugins\Notifications4Plugins\Utils\Notifications4PluginsTrait;

/**
 * Class NotificationFormGUI
 *
 * @package srag\Plugins\Notifications4Plugins\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
class NotificationFormGUI extends ActiveRecordObjectFormGUI {

	use Notifications4PluginsTrait;
	const PLUGIN_CLASS_NAME = ilNotifications4PluginsPlugin::class;
	const LANG_MODULE = ilNotifications4PluginsConfigGUI::LANG_MODULE_NOTIFICATIONS4PLUGIN;
	/**
	 * @var Notification
	 */
	protected $object;


	/**
	 * NotificationFormGUI constructor
	 *
	 * @param ilNotifications4PluginsConfigGUI $parent
	 * @param string                           $tab_id
	 * @param Notification                     $object
	 */
	public function __construct(ilNotifications4PluginsConfigGUI $parent, string $tab_id, Notification $object) {
		parent::__construct($parent, $tab_id, $object, false);
	}


	/**
	 * @inheritdoc
	 */
	protected function getValue(/*string*/
		$key)/*: void*/ {
		switch (true) {
			case ($key === "language"):
			case ($key === "subject"):
			case ($key === "text"):
				return null;

			case (strpos($key, "language_") === 0):
				$language = substr($key, strlen("language") + 1);

				return $language;

			case (strpos($key, "subject_") === 0):
				$language = substr($key, strlen("subject") + 1);

				return $this->object->getSubject($language);

			case (strpos($key, "text_") === 0):
				$language = substr($key, strlen("text") + 1);

				return $this->object->getText($language);

			default:
				return parent::getValue($key);
		}
	}


	/**
	 * @inheritdoc
	 */
	protected function initAction()/*: void*/ {
		if (!empty($this->object->getId())) {
			self::dic()->ctrl()->setParameter($this->parent, ilNotifications4PluginsConfigGUI::GET_PARAM, $this->object->getId());
		}

		parent::initAction();

		self::dic()->ctrl()->setParameter($this->parent, ilNotifications4PluginsConfigGUI::GET_PARAM, null);
	}


	/**
	 * @inheritdoc
	 */
	protected function initCommands()/*: void*/ {
		if (!empty($this->object->getId())) {
			$this->addCommandButton(ilNotifications4PluginsConfigGUI::CMD_UPDATE_NOTIFICATION, $this->txt("save"));
		} else {
			$this->addCommandButton(ilNotifications4PluginsConfigGUI::CMD_CREATE_NOTIFICATION, $this->txt("add"));
		}

		$this->addCommandButton($this->parent->getCmdForTab(ilNotifications4PluginsConfigGUI::TAB_NOTIFICATIONS), $this->txt("cancel"));
	}


	/**
	 * @inheritdoc
	 */
	protected function initFields()/*: void*/ {
		$this->fields = (!empty($this->object->getId()) ? [
				"id" => [
					self::PROPERTY_CLASS => ilNonEditableValueGUI::class
				]
			] : []) + [
				"name" => [
					self::PROPERTY_CLASS => ilTextInputGUI::class,
					self::PROPERTY_REQUIRED => true
				],
				"title" => [
					self::PROPERTY_CLASS => ilTextInputGUI::class,
					self::PROPERTY_REQUIRED => true
				],
				"description" => [
					self::PROPERTY_CLASS => ilTextAreaInputGUI::class,
					self::PROPERTY_REQUIRED => false
				],
				"defaultlanguage" => [
					self::PROPERTY_CLASS => ilTextInputGUI::class,
					self::PROPERTY_REQUIRED => false,
					"setTitle" => $this->txt("default_language"),
					"setInfo" => $this->txt("default_language_info")
				],
			];

		foreach ($this->object->getLanguages() as $language) {
			$this->addInputsForLanguage($language->getLanguage());
		}

		// For a new language
		$this->addInputsForLanguage();
	}


	/**
	 * @inheritdoc
	 */
	protected function initTitle()/*: void*/ {
		$this->setTitle($this->txt(!empty($this->object->getId()) ? "edit_notification" : "add_notification"));
	}


	/**
	 * @inheritdoc
	 */
	public function storeForm(): bool {
		if (!parent::storeForm()) {
			return false;
		}

		self::notification()->storeInstance($this->object);

		return true;
	}


	/**
	 * @inheritdoc
	 */
	protected function storeValue(/*string*/
		$key, $value)/*: void*/ {
		switch (true) {
			case ($key === "id"):
			case (strpos($key, "language") === 0):
				break;

			case ($key === "subject"):
				$language = $this->getInput("language");

				if (!empty($language)) {
					$this->object->setSubject(strval($value), strval($language));
				}
				break;

			case ($key === "text"):
				$language = $this->getInput("language");

				if (!empty($language)) {
					//$this->object->setText(strval($value), strval($language));
					$this->object->setText(strval(filter_input(INPUT_POST, "text_" . $language)), strval($language));
				}
				break;

			case (strpos($key, "subject") === 0):
				$language = substr($key, strlen("subject") + 1);

				$this->object->setSubject(strval($value), strval($language));
				break;

			case (strpos($key, "text") === 0):
				$language = substr($key, strlen("text") + 1);

				//$this->object->setText(strval($value), strval($language));
				$this->object->setText(strval(filter_input(INPUT_POST, "text_" . $language)), strval($language));
				break;

			default:
				parent::storeValue($key, $value);
		}
	}


	/**
	 * @param string $language
	 */
	protected function addInputsForLanguage(string $language = "")/*: void*/ {
		$this->fields = $this->fields + [
				"header" . (!empty($language) ? "_" . $language : "") => [
					self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class,
					"setTitle" => $language ? strtoupper($language) : $this->txt("add_new_language")
				],
				"language" . (!empty($language) ? "_" . $language : "") => [
					self::PROPERTY_CLASS => (!empty($language) ? ilNonEditableValueGUI::class : ilTextInputGUI::class),
					"setTitle" => $this->txt("language")
				],
				"subject" . (!empty($language) ? "_" . $language : "") => [
					self::PROPERTY_CLASS => ilTextInputGUI::class,
					"setTitle" => $this->txt("subject")
				],
				"text" . (!empty($language) ? "_" . $language : "") => [
					self::PROPERTY_CLASS => ilTextAreaInputGUI::class,
					"setRows" => 10,
					"setTitle" => $this->txt("text"),
					"setInfo" => "https://twig.symfony.com/doc/1.x/templates.html"
				]
			];
	}
}
