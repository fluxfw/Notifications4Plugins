<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Plugins\Notifications4Plugins\Utils\Notifications4PluginsTrait;

/**
 * Class srNotificationLanguage
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class srNotificationLanguage extends ActiveRecord {

	use DICTrait;
	use Notifications4PluginsTrait;
	const TABLE_NAME = 'sr_notification_lang';
	const PLUGIN_CLASS_NAME = ilNotifications4PluginsPlugin::class;


	/**
	 * @return string
	 */
	public function getConnectorContainerName() {
		return self::TABLE_NAME;
	}


	/**
	 * @return string
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
	 * @var int
	 *
	 * @db_has_field    true
	 * @db_fieldtype    integer
	 * @db_length       8
	 */
	protected $notification_id;
	/**
	 * @var string
	 *
	 * @db_has_field    true
	 * @db_fieldtype    text
	 * @db_length       2
	 */
	protected $language;
	/**
	 * @var string
	 *
	 * @db_has_field    true
	 * @db_fieldtype    clob
	 * @db_length       4000
	 */
	protected $text;
	/**
	 * @var string
	 *
	 * @db_has_field    true
	 * @db_fieldtype    clob
	 * @db_length       256
	 */
	protected $subject;
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


	public function create() {
		$this->created_at = date('Y-m-d H:i:s');
		$this->updated_at = date('Y-m-d H:i:s');
		parent::create();
	}


	public function update() {
		$this->updated_at = date('Y-m-d H:i:s');
		parent::update();
	}


	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * @return int
	 */
	public function getNotificationId() {
		return $this->notification_id;
	}


	/**
	 * @param int $notification_id
	 */
	public function setNotificationId($notification_id) {
		$this->notification_id = $notification_id;
	}


	/**
	 * @return string
	 */
	public function getLanguage() {
		return $this->language;
	}


	/**
	 * @param string $language
	 */
	public function setLanguage($language) {
		$this->language = $language;
	}


	/**
	 * @return string
	 */
	public function getText() {
		return $this->text;
	}


	/**
	 * @param string $text
	 */
	public function setText($text) {
		$this->text = $text;
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
	public function getSubject() {
		return $this->subject;
	}


	/**
	 * @param string $subject
	 */
	public function setSubject($subject) {
		$this->subject = $subject;
	}
}
