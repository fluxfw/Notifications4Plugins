<?php

require_once('class.srNotificationLanguage.php');
require_once(dirname(dirname(__FILE__)) . '/Parser/class.srNotificationTwigParser.php');

/**
 * Class srNotifier
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class srNotification extends ActiveRecord
{

    const TABLE_NAME = 'sr_notification';

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
     * @db_length       64
     */
    protected $title;

    /**
     * @var string
     *
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_is_unique    true
     * @db_length       32
     */
    protected $name;

    /**
     * @var string
     *
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       1204
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
     * @param $name
     * @return srNotification
     */
    public static function getInstanceByName($name)
    {
        return static::where(array('name' => $name))->first();
    }


    public function create()
    {
        $this->created_at = date('Y-m-d H:m:s');
        $this->updated_at = date('Y-m-d H:m:s');
        parent::create();
    }


    public function update()
    {
        $this->updated_at = date('Y-m-d H:m:s');
        parent::update();
    }


    public function delete()
    {
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
     * @return string
     */
    public function getSubject($language = '')
    {
        $notification = $this->getNotificationLanguage($language);

        return ($notification) ? $notification->getSubject() : '';
    }


    /**
     * Get the text of the notification
     * If no language code is provided, the text of the default language is returned
     *
     * @param string $language
     * @return string
     */
    public function getText($language = '')
    {
        $notification = $this->getNotificationLanguage($language);

        return ($notification) ? $notification->getText() : '';
    }


    /**
     * @param array $replacements
     * @param string $language
     * @param srNotificationParser $parser
     * @return string
     */
    public function parseText(array $replacements = array(), $language = '', srNotificationParser $parser = null)
    {
        $parser = ($parser) ? $parser : new srNotificationTwigParser();

        return $parser->parse($this->getText($language), $replacements);
    }


    /**
     * @param array $replacements
     * @param string $language
     * @param srNotificationParser $parser
     * @return string
     */
    public function parseSubject(array $replacements = array(), $language = '', srNotificationParser $parser = null)
    {
        $parser = ($parser) ? $parser : new srNotificationTwigParser();

        return $parser->parse($this->getSubject($language), $replacements);
    }


    /**
     * @param string $language
     * @return srNotificationLanguage
     */
    public function getNotificationLanguage($language = '')
    {
        $language = ($language && in_array($language, $this->getLanguages())) ? $language : $this->getDefaultLanguage();
        $notifications = array_filter($this->getNotificationLanguages(), function ($notification) use ($language) {
            return $notification->getLanguage() == $language;
        });

        return count($notifications) ? array_pop($notifications) : null;
    }


    /**
     * @return array
     */
    public function getLanguages()
    {
        $return = array();
        foreach ($this->getNotificationLanguages() as $notification) {
            $return[] = $notification->getLanguage();
        }

        return $return;
    }


    /**
     * @return srNotificationLanguage[]
     */
    protected function getNotificationLanguages()
    {
        static $notifications = array();

        if (isset($notifications[$this->getId()])) {
            return $notifications[$this->getId()];
        }

        $notifications[$this->getId()] = srNotificationLanguage::where(array('notification_id' => $this->getId()))->get();

        return $notifications[$this->getId()];
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }


    /**
     * @param string $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }


    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }


    /**
     * @param string $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }


    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }


    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }


    /**
     * @return string
     */
    public function getDefaultLanguage()
    {
        return $this->default_language;
    }


    /**
     * @param string $default_language
     */
    public function setDefaultLanguage($default_language)
    {
        $this->default_language = $default_language;
    }


    /**
     * Return the Name of your Database Table
     *
     * @return string
     */
    public static function returnDbTableName()
    {
        return self::TABLE_NAME;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}