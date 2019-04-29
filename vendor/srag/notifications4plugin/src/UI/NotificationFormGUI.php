<?php

namespace srag\Notifications4Plugin\Notifications4Plugins\UI;

use ilFormSectionHeaderGUI;
use ilNonEditableValueGUI;
use ilSelectInputGUI;
use ilTextAreaInputGUI;
use ilTextInputGUI;
use srag\CustomInputGUIs\Notifications4Plugins\PropertyFormGUI\ObjectPropertyFormGUI;
use srag\DIC\Notifications4Plugins\Plugin\PluginInterface;
use srag\Notifications4Plugin\Notifications4Plugins\Ctrl\AbstractCtrl;
use srag\Notifications4Plugin\Notifications4Plugins\Notification\AbstractNotification;
use srag\Notifications4Plugin\Notifications4Plugins\Parser\twigParser;
use srag\Notifications4Plugin\Notifications4Plugins\Utils\Notifications4PluginTrait;

/**
 * Class NotificationFormGUI
 *
 * @package srag\Notifications4Plugin\Notifications4Plugins\UI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
class NotificationFormGUI extends ObjectPropertyFormGUI {

	use Notifications4PluginTrait;
	const LANG_MODULE = AbstractCtrl::LANG_MODULE_NOTIFICATIONS4PLUGIN;
	/**
	 * @var PluginInterface
	 */
	protected $plugin;
	/**
	 * @var AbstractNotification
	 */
	protected $object;


	/**
	 * NotificationFormGUI constructor
	 *
	 * @param PluginInterface      $plugin
	 * @param AbstractCtrl         $parent
	 * @param AbstractNotification $object
	 */
	public function __construct(PluginInterface $plugin, AbstractCtrl $parent, AbstractNotification $object) {
		$this->plugin = $plugin;

		parent::__construct($parent, $object, false);
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
			self::dic()->ctrl()->setParameter($this->parent, AbstractCtrl::GET_PARAM, $this->object->getId());
		}

		parent::initAction();

		self::dic()->ctrl()->setParameter($this->parent, AbstractCtrl::GET_PARAM, null);
	}


	/**
	 * @inheritdoc
	 */
	protected function initCommands()/*: void*/ {
		if (!empty($this->object->getId())) {
			$this->addCommandButton(AbstractCtrl::CMD_UPDATE_NOTIFICATION, $this->txt("save"));
		} else {
			$this->addCommandButton(AbstractCtrl::CMD_CREATE_NOTIFICATION, $this->txt("add"));
		}

		$this->addCommandButton(AbstractCtrl::CMD_LIST_NOTIFICATIONS, $this->txt("cancel"));
	}


	/**
	 * @inheritdoc
	 */
	protected function initId()/*: void*/ {

	}


	/**
	 * @inheritdoc
	 */
	protected function initFields()/*: void*/ {
		$this->fields = (!empty($this->object->getId()) ? [
				"id" => [
					self::PROPERTY_CLASS => ilNonEditableValueGUI::class,
					self::PROPERTY_REQUIRED => true
				]
			] : []) + [
				"name" => [
					self::PROPERTY_CLASS => (empty($this->object->getId()) ? ilTextInputGUI::class : ilNonEditableValueGUI::class),
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
				"parser" => [
					self::PROPERTY_CLASS => ilSelectInputGUI::class,
					self::PROPERTY_REQUIRED => true,
					self::PROPERTY_OPTIONS => self::parser()->getPossibleParsers(),
					"setInfo" => twigParser::NAME . ": " . self::output()->getHTML(self::dic()->ui()->factory()->link()
							->standard(twigParser::DOC_LINK, twigParser::DOC_LINK)->withOpenInNewViewport(true))
				]
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
	protected function storeValue(/*string*/
		$key, $value)/*: void*/ {
		switch (true) {
			case ($key === "id"):
			case (strpos($key, "language") === 0):
				break;

			case ($key === "name"):
				if (empty($this->object->getId())) {
					parent::storeValue($key, $value);
				}
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
					$this->object->setText(strval($value), strval($language));
				}
				break;

			case (strpos($key, "subject") === 0):
				$language = substr($key, strlen("subject") + 1);

				$this->object->setSubject(strval($value), strval($language));
				break;

			case (strpos($key, "text") === 0):
				$language = substr($key, strlen("text") + 1);

				$this->object->setText(strval($value), strval($language));

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
					"setTitle" => $this->txt("text")
				]
			];
	}


	/**
	 * @inheritdoc
	 */
	public function txt(/*string*/
		$key,/*?string*/
		$default = null): string {
		if ($default !== null) {
			return $this->plugin->translate($key, self::LANG_MODULE, [], true, "", $default);
		} else {
			return $this->plugin->translate($key, self::LANG_MODULE);
		}
	}
}
