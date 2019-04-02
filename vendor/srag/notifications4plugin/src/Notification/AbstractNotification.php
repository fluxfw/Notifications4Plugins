<?php

namespace srag\Notifications4Plugin\Notifications4Plugins\Notification;

use ActiveRecord;
use arConnector;
use ilDateTime;
use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Notifications4Plugin\Notifications4Plugins\Notification\Language\AbstractNotificationLanguage;
use srag\Notifications4Plugin\Notifications4Plugins\Notification\Language\Repository as NotificationLanguageRepository;
use srag\Notifications4Plugin\Notifications4Plugins\Notification\Repository as NotificationRepository;
use srag\Notifications4Plugin\Notifications4Plugins\Parser\twigParser;
use srag\Notifications4Plugin\Notifications4Plugins\Utils\Notifications4PluginTrait;

/**
 * Class AbstractNotification
 *
 * @package srag\Notifications4Plugin\Notifications4Plugins\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
abstract class AbstractNotification extends ActiveRecord {

	use DICTrait;
	use Notifications4PluginTrait;
	/**
	 * @var string
	 *
	 * @abstract
	 */
	const TABLE_NAME = "";
	/**
	 * @var string
	 *
	 * @abstract
	 */
	const LANGUAGE_CLASS_NAME = "";


	/**
	 * @inheritdoc
	 */
	protected static function notification(): NotificationRepository {
		return NotificationRepository::getInstance(static::class, static::LANGUAGE_CLASS_NAME);
	}


	/**
	 * @inheritdoc
	 */
	protected static function notificationLanguage(): NotificationLanguageRepository {
		return NotificationLanguageRepository::getInstance(static::LANGUAGE_CLASS_NAME);
	}


	/**
	 * @return string
	 */
	public function getConnectorContainerName(): string {
		return static::TABLE_NAME;
	}


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public static function returnDbTableName(): string {
		return static::TABLE_NAME;
	}


	/**
	 * @var int
	 *
	 * @con_has_field    true
	 * @con_fieldtype    integer
	 * @con_length       8
	 * @con_is_notnull   true
	 * @con_is_primary   true
	 * @con_sequence     true
	 */
	protected $id;
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_length       1024
	 * @con_is_notnull   true
	 * @con_is_unique    true
	 */
	protected $name = "";
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_length       1024
	 * @con_is_notnull   true
	 */
	protected $title = "";
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_length       4000
	 * @con_is_notnull   true
	 */
	protected $description = "";
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_length       2
	 * @con_is_notnull   true
	 */
	protected $default_language = "";
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_is_notnull   true
	 */
	protected $parser = twigParser::class;
	/**
	 * @var ilDateTime
	 *
	 * @con_has_field    true
	 * @con_fieldtype    timestamp
	 * @con_is_notnull   true
	 */
	protected $created_at;
	/**
	 * @var ilDateTime
	 *
	 * @con_has_field    true
	 * @con_fieldtype    timestamp
	 * @con_is_notnull   true
	 */
	protected $updated_at;
	/**
	 * @var AbstractNotificationLanguage[]
	 */
	protected $languages = [];


	/**
	 * AbstractNotification constructor
	 *
	 * @param int              $primary_key_value
	 * @param arConnector|null $connector
	 */
	public function __construct(/*int*/
		$primary_key_value = 0, /*?*/
		arConnector $connector = null) {
		parent::__construct($primary_key_value, $connector);
	}


	/**
	 *
	 */
	public function afterObjectLoad()/*: void*/ {
		if (!empty($this->id)) {
			$this->languages = self::notificationLanguage()->getLanguagesForNotification($this->id);
		}
	}


	/**
	 * @param string $field_name
	 *
	 * @return mixed|null
	 */
	public function sleep(/*string*/
		$field_name) {
		$field_value = $this->{$field_name};

		switch ($field_name) {
			case "created_at":
			case "updated_at":
				return $field_value->get(IL_CAL_DATETIME);

			default:
				return null;
		}
	}


	/**
	 * @param string $field_name
	 * @param mixed  $field_value
	 *
	 * @return mixed|null
	 */
	public function wakeUp(/*string*/
		$field_name, $field_value) {
		switch ($field_name) {
			case "id":
				return intval($field_value);

			case "created_at":
			case "updated_at":
				return new ilDateTime($field_value, IL_CAL_DATETIME);

			default:
				return null;
		}
	}


	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}


	/**
	 * @param int $id
	 */
	public function setId(int $id)/*: void*/ {
		$this->id = $id;
	}


	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}


	/**
	 * @param string $name
	 */
	public function setName(string $name)/*: void*/ {
		$this->name = $name;
	}


	/**
	 * @return string
	 */
	public function getTitle(): string {
		return $this->title;
	}


	/**
	 * @param string $title
	 */
	public function setTitle(string $title)/*: void*/ {
		$this->title = $title;
	}


	/**
	 * @return string
	 */
	public function getDescription(): string {
		return $this->description;
	}


	/**
	 * @param string $description
	 */
	public function setDescription(string $description)/*: void*/ {
		$this->description = $description;
	}


	/**
	 * @return string
	 */
	public function getDefaultLanguage(): string {
		return $this->default_language;
	}


	/**
	 * @param string $default_language
	 */
	public function setDefaultLanguage(string $default_language)/*: void*/ {
		$this->default_language = $default_language;
	}


	/**
	 * @return string
	 */
	public function getParser(): string {
		return $this->parser;
	}


	/**
	 * @param string $parser
	 */
	public function setParser(string $parser)/*: void*/ {
		$this->parser = $parser;
	}


	/**
	 * @return ilDateTime
	 */
	public function getCreatedAt(): ilDateTime {
		return $this->created_at;
	}


	/**
	 * @param ilDateTime $created_at
	 */
	public function setCreatedAt(ilDateTime $created_at)/*: void*/ {
		$this->created_at = $created_at;
	}


	/**
	 * @return ilDateTime
	 */
	public function getUpdatedAt(): ilDateTime {
		return $this->updated_at;
	}


	/**
	 * @param ilDateTime $updated_at
	 */
	public function setUpdatedAt(ilDateTime $updated_at)/*: void*/ {
		$this->updated_at = $updated_at;
	}


	/**
	 * @return AbstractNotificationLanguage[]
	 */
	public function getLanguages(): array {
		return $this->languages;
	}


	/**
	 * @param AbstractNotificationLanguage[] $languages
	 */
	public function setLanguages(array $languages)/*: void*/ {
		$this->languages = $languages;
	}


	/**
	 * @param AbstractNotificationLanguage $language
	 */
	public function addLanguage(AbstractNotificationLanguage $language)/*: void*/ {
		$this->languages[$language->getLanguage()] = $language;
	}


	/**
	 * @param string $language
	 * @param bool   $allow_create_new
	 *
	 * @return AbstractNotificationLanguage
	 */
	protected function getNotificationLanguage(string $language = "", bool $allow_create_new = false): AbstractNotificationLanguage {
		if (empty($language) || (!isset($this->languages[$language]) && !$allow_create_new)) {
			$language = $this->default_language;
		}

		if (isset($this->languages[$language])) {
			$l = $this->languages[$language];
		} else {
			$l = $this->languages[$language] = self::notificationLanguage()->getLanguageForNotification($this->id, $language);
		}

		return $l;
	}


	/**
	 * @param string $language
	 *
	 * @return string
	 */
	public function getSubject(string $language = ""): string {
		$language = $this->getNotificationLanguage($language);

		return $language->getSubject();
	}


	/**
	 * @param string $subject
	 * @param string $language
	 */
	public function setSubject(string $subject, string $language)/*: void*/ {
		$language = $this->getNotificationLanguage($language, true);

		$language->setSubject($subject);
	}


	/**
	 * @param string $language
	 *
	 * @return string
	 */
	public function getText(string $language = ""): string {
		$language = $this->getNotificationLanguage($language);

		return $language->getText();
	}


	/**
	 * @param string $text
	 * @param string $language
	 */
	public function setText(string $text, string $language)/*: void*/ {
		$language = $this->getNotificationLanguage($language, true);

		$language->setText($text);
	}
}
