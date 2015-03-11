<?php

require_once('class.srNotificationLanguage.php');
require_once(dirname(dirname(__FILE__)) . '/Parser/class.srNotificationTwigParser.php');

/**
 * Class srNotification
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
     * @var srNotificationParser
     */
    protected $parser;


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
     * Set a text for the given language
     *
     * @param string $subject
     * @param string $language
     */
    public function setSubject($subject, $language)
    {
        $notifications = $this->getNotificationLanguages();
        if (isset($notifications[$language])) {
            $notification = $notifications[$language];
        } else {
            $notification = new srNotificationLanguage();
            $notification->setLanguage($language);
            if (!$this->getId()) {
                $this->save();
            }
            $notification->setNotificationId($this->getId());
        }
        $notification->setSubject($subject);
        $notification->save();
    }



    /**
     * Set a text for the given language
     *
     * @param string $text
     * @param string $language
     */
    public function setText($text, $language)
    {
        $notifications = $this->getNotificationLanguages();
        if (isset($notifications[$language])) {
            $notification = $notifications[$language];
        } else {
            $notification = new srNotificationLanguage();
            $notification->setLanguage($language);
            if (!$this->getId()) {
                $this->save();
            }
            $notification->setNotificationId($this->getId());
        }
        $notification->setText($text);
        $notification->save();
    }


    /**
     * @param array $replacements
     * @param string $language
     *
     * @return string
     */
    public function parseText(array $replacements = array(), $language = '')
    {
        return $this->getParser()->parse($this->getText($language), $replacements);
    }


    /**
     * @param array $replacements
     * @param string $language
     *
     * @return string
     */
    public function parseSubject(array $replacements = array(), $language = '')
    {
        return $this->getParser()->parse($this->getSubject($language), $replacements);
    }


    /**
     * @param srNotificationSender $sender A concrete srNotificationSender object, e.g. srNotificationMailSender
     * @param string $language Omit to choose the default language
     * @param array $replacements
     * @return bool
     */
    public function send(srNotificationSender $sender, array $replacements = array(), $language = '')
    {
        $sender->setMessage($this->parseText($replacements, $language));
        $sender->setSubject($this->parseSubject($replacements, $language));

        return $sender->send();
    }


    /**
     * @param string $language
     * @return srNotificationLanguage
     */
    public function getNotificationLanguage($language = '')
    {
        $language = ($language && in_array($language, $this->getLanguages())) ? $language : $this->getDefaultLanguage();
        $notifications = $this->getNotificationLanguages();

        return (isset($notifications[$language])) ? $notifications[$language] : null;
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
        $notifications = srNotificationLanguage::where(array('notification_id' => $this->getId()))->get();
        /** @var srNotificationLanguage $notification */
        $return = array();
        foreach ($notifications as $notification) {
            $return[$notification->getLanguage()] = $notification;
        }

        return $return;
    }


    /**
     * Get the parser for the placeholders in subject and text, default is twig
     *
     * @return srNotificationParser
     */
    protected function getParser()
    {
        if (!$this->parser) {
            $this->parser = new srNotificationTwigParser();
        }

        return $this->parser;
    }


    /**
     * Set a parser to parse the placeholders
     *
     * @param srNotificationParser $parser
     */
    public function setParser(srNotificationParser $parser)
    {
        $this->parser = $parser;
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
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
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