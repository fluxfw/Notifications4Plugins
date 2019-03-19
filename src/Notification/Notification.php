<?php

namespace srag\Plugins\Notifications4Plugins\Notification;

use ActiveRecord;
use arConnector;
use ilNotifications4PluginsPlugin;
use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Plugins\Notifications4Plugins\Utils\Notifications4PluginsTrait;

/**
 * Class Notification
 *
 * @package srag\Plugins\Notifications4Plugins\Notification
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
class Notification extends ActiveRecord {

	use DICTrait;
	use Notifications4PluginsTrait;
	const TABLE_NAME = 'sr_notification';
	const PLUGIN_CLASS_NAME = ilNotifications4PluginsPlugin::class;


	/**
	 * @return string
	 */
	public function getConnectorContainerName() {
		return self::TABLE_NAME;
	}


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public static function returnDbTableName() {
		return self::TABLE_NAME;
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
	 * @con_fieldtype    timestamp
	 */
	protected $created_at;
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    timestamp
	 */
	protected $updated_at;
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_length       1024
	 */
	protected $title = "";
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_is_unique    true
	 * @con_length       1024
	 */
	protected $name = "";
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_length       4000
	 */
	protected $description = "";
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_length       2
	 */
	protected $default_language = "";
	/**
	 * @var array
	 */
	protected $notification_languages = array();


	/**
	 * Notification constructor
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
	 * Get the subject of the notification
	 * If no language code is provided, the subject of the default language is returned
	 *
	 * @param string $language
	 *
	 * @return string
	 */
	public function getSubject($language = '') {
		$notification = $this->getNotificationLanguage($language);

		return ($notification) ? $notification->getSubject() : '';
	}


	/**
	 * Get the text of the notification
	 * If no language code is provided, the text of the default language is returned
	 *
	 * @param string $language
	 *
	 * @return string
	 */
	public function getText($language = '') {
		$notification = $this->getNotificationLanguage($language);

		return ($notification) ? $notification->getText() : '';
	}


	/**
	 * Set a text for the given language
	 *
	 * @param string $subject
	 * @param string $language
	 */
	public function setSubject($subject, $language) {
		$notifications = $this->getNotificationLanguages();
		if (isset($notifications[$language])) {
			$notification = $notifications[$language];
		} else {
			$notification = new NotificationLanguage();
			$notification->setLanguage($language);
			if (!$this->getId()) {
				self::notification()->storeInstance($this);
			}
			$notification->setNotificationId($this->getId());
			$this->notification_languages[$language] = $notification;
		}
		$notification->setSubject($subject);
	}


	/**
	 * Set a text for the given language
	 *
	 * @param string $text
	 * @param string $language
	 */
	public function setText($text, $language) {
		$notifications = $this->getNotificationLanguages();
		if (isset($notifications[$language])) {
			$notification = $notifications[$language];
		} else {
			$notification = new NotificationLanguage();
			$notification->setLanguage($language);
			if (!$this->getId()) {
				self::notification()->storeInstance($this);
			}
			$notification->setNotificationId($this->getId());
			$this->notification_languages[$language] = $notification;
		}
		$notification->setText($text);
	}


	/**
	 * @param string $language
	 *
	 * @return NotificationLanguage
	 */
	public function getNotificationLanguage($language = '') {
		$language = ($language && in_array($language, $this->getLanguages())) ? $language : $this->getDefaultLanguage();
		$notifications = $this->getNotificationLanguages();

		return (isset($notifications[$language])) ? $notifications[$language] : null;
	}


	/**
	 * @return array
	 */
	public function getLanguages() {
		$return = array();
		foreach ($this->getNotificationLanguages() as $notification) {
			$return[] = $notification->getLanguage();
		}

		return $return;
	}


	/**
	 * @return NotificationLanguage[]
	 */
	public function getNotificationLanguages() {
		if ($this->notification_languages === null) {
			$notifications = NotificationLanguage::where(array( 'notification_id' => $this->getId() ))->get();
			/** @var NotificationLanguage $notification */
			$this->notification_languages = array();
			foreach ($notifications as $notification) {
				$this->notification_languages[$notification->getLanguage()] = $notification;
			}
		}

		return $this->notification_languages;
	}


	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * @param int $id
	 */
	public function setId($id) {
		$this->id = $id;
	}


	/**
	 * @return string
	 */
	public function getCreatedAt() {
		return $this->created_at;
	}


	/**
	 * @param string $created_at
	 */
	public function setCreatedAt($created_at) {
		$this->created_at = $created_at;
	}


	/**
	 * @return string
	 */
	public function getUpdatedAt() {
		return $this->updated_at;
	}


	/**
	 * @param string $updated_at
	 */
	public function setUpdatedAt($updated_at) {
		$this->updated_at = $updated_at;
	}


	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}


	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}


	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}


	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}


	/**
	 * @return string
	 */
	public function getDefaultLanguage() {
		return $this->default_language;
	}


	/**
	 * @param string $default_language
	 */
	public function setDefaultLanguage($default_language) {
		$this->default_language = $default_language;
	}


	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}
}
