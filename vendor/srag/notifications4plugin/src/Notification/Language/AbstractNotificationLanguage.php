<?php

namespace srag\Notifications4Plugin\Notifications4Plugins\Notification\Language;

use ActiveRecord;
use arConnector;
use ilDateTime;
use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Notifications4Plugin\Notifications4Plugins\Utils\Notifications4PluginTrait;

/**
 * Class AbstractNotificationLanguage
 *
 * @package srag\Notifications4Plugin\Notifications4Plugins\Notification\Language
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
abstract class AbstractNotificationLanguage extends ActiveRecord {

	use DICTrait;
	use Notifications4PluginTrait;
	/**
	 * @var string
	 *
	 * @abstract
	 */
	const TABLE_NAME = "";


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
	 * @var int
	 *
	 * @con_has_field    true
	 * @con_fieldtype    integer
	 * @con_length       8
	 * @con_is_notnull   true
	 */
	protected $notification_id;
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_length       2
	 * @con_is_notnull   true
	 */
	protected $language = "";
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    clob
	 * @con_length       256
	 * @con_is_notnull   true
	 */
	protected $subject = "";
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    clob
	 * @con_length       4000
	 * @con_is_notnull   true
	 */
	protected $text = "";
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
	 * AbstractNotificationLanguage constructor
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
			case "notification_id":
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
	 * @return int
	 */
	public function getNotificationId(): int {
		return $this->notification_id;
	}


	/**
	 * @param int $notification_id
	 */
	public function setNotificationId(int $notification_id)/*: void*/ {
		$this->notification_id = $notification_id;
	}


	/**
	 * @return string
	 */
	public function getLanguage(): string {
		return $this->language;
	}


	/**
	 * @param string $language
	 */
	public function setLanguage(string $language)/*: void*/ {
		$this->language = $language;
	}


	/**
	 * @return string
	 */
	public function getSubject(): string {
		return $this->subject;
	}


	/**
	 * @param string $subject
	 */
	public function setSubject(string $subject)/*: void*/ {
		$this->subject = $subject;
	}


	/**
	 * @return string
	 */
	public function getText(): string {
		return $this->text;
	}


	/**
	 * @param string $text
	 */
	public function setText(string $text)/*: void*/ {
		$this->text = $text;
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
}
