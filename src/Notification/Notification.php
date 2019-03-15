<?php

namespace srag\Plugins\Notifications4Plugins\Notification;

use ActiveRecord;
use ilNotifications4PluginsPlugin;
use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Plugins\Notifications4Plugins\Sender\Sender;
use srag\Plugins\Notifications4Plugins\Parser\Parser;
use srag\Plugins\Notifications4Plugins\Parser\twigParser;
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
	 * @db_has_field    true
	 * @db_fieldtype    integer
	 * @db_length       8
	 * @db_is_primary   true
	 * @db_sequence     true
	 */
	protected $id = 0;
	/**
	 * @var string
	 *
	 * @db_has_field    true
	 * @db_fieldtype    timestamp
	 */
	protected $created_at;
	/**
	 * @var string
	 *
	 * @db_has_field    true
	 * @db_fieldtype    timestamp
	 */
	protected $updated_at;
	/**
	 * @var string
	 *
	 * @db_has_field    true
	 * @db_fieldtype    text
	 * @db_length       1024
	 */
	protected $title;
	/**
	 * @var string
	 *
	 * @db_has_field    true
	 * @db_fieldtype    text
	 * @db_is_unique    true
	 * @db_length       1024
	 */
	protected $name;
	/**
	 * @var string
	 *
	 * @db_has_field    true
	 * @db_fieldtype    text
	 * @db_length       4000
	 */
	protected $description;
	/**
	 * @var string
	 *
	 * @db_has_field    true
	 * @db_fieldtype    text
	 * @db_length       2
	 */
	protected $default_language;
	/**
	 * @var Parser
	 */
	protected $parser;
	/**
	 * @var array
	 */
	protected $notification_languages;


	/**
	 * @param string $name
	 *
	 * @return Notification
	 */
	public static function getInstanceByName($name) {
		return static::where(array( 'name' => $name ))->first();
	}


	/**
	 *
	 */
	public function create() {
		$this->created_at = date('Y-m-d H:i:s');
		$this->updated_at = date('Y-m-d H:i:s');
		parent::create();
		foreach ($this->getNotificationLanguages() as $notification) {
			$notification->store();
		}
	}


	/**
	 *
	 */
	public function update() {
		$this->updated_at = date('Y-m-d H:i:s');
		parent::update();
		foreach ($this->getNotificationLanguages() as $notification) {
			$notification->store();
		}
	}


	/**
	 *
	 */
	public function delete() {
		parent::delete();
		foreach ($this->getNotificationLanguages() as $notification) {
			$notification->delete();
		}
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
				$this->store();
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
				$this->store();
			}
			$notification->setNotificationId($this->getId());
			$this->notification_languages[$language] = $notification;
		}
		$notification->setText($text);
	}


	/**
	 * @param array  $replacements
	 * @param string $language
	 *
	 * @return string
	 */
	public function parseText(array $replacements = array(), $language = '') {
		return $this->getParser()->parse($this->getText($language), $replacements);
	}


	/**
	 * @param array  $replacements
	 * @param string $language
	 *
	 * @return string
	 */
	public function parseSubject(array $replacements = array(), $language = '') {
		return $this->getParser()->parse($this->getSubject($language), $replacements);
	}


	/**
	 * @param Sender $sender   A concrete srNotificationSender object, e.g. srNotificationMailSender
	 * @param string $language Omit to choose the default language
	 * @param array  $replacements
	 *
	 * @return bool
	 */
	public function send(Sender $sender, array $replacements = array(), $language = '') {
		$sender->setMessage($this->parseText($replacements, $language));
		$sender->setSubject($this->parseSubject($replacements, $language));

		return $sender->send();
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
	protected function getNotificationLanguages() {
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
	 * Get the parser for the placeholders in subject and text, default is twig
	 *
	 * @return Parser
	 */
	protected function getParser() {
		if (!$this->parser) {
			$this->parser = new twigParser();
		}

		return $this->parser;
	}


	/**
	 * Set a parser to parse the placeholders
	 *
	 * @param Parser $parser
	 */
	public function setParser(Parser $parser) {
		$this->parser = $parser;
	}


	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * @return string
	 */
	public function getCreatedAt() {
		return $this->created_at;
	}


	/**
	 * @return string
	 */
	public function getUpdatedAt() {
		return $this->updated_at;
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
