<?php

/**
 * Class srNotificationService
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class srNotificationService
{

    /**
     * @var srNotification
     */
    protected $notification;


    /**
     * @param srNotification $notification
     */
    public function __construct(srNotification $notification = null)
    {
        $this->notification = $notification;
    }


    /**
     * @param string $title
     * @param string $description
     * @param $name
     * @param string $default_language
     * @param array $notifications
     */
    public function create($title, $description, $name, $default_language, array $notifications = array())
    {
        $this->createOrUpdate($title, $description, $name, $default_language, $notifications);
    }


    /**
     * @param $title
     * @param $description
     * @param $name
     * @param $default_language
     * @param array $notifications
     */
    public function update($title, $description, $name, $default_language, array $notifications = array())
    {
        $this->createOrUpdate($title, $description, $name, $default_language, $notifications);
    }


    /**
     * @param string $language
     * @param string $subject
     * @param string $text
     */
    protected function createOrUpdateNotification($language, $subject, $text)
    {
        $notification = $this->notification->getNotificationLanguage($language);
        $notification = (is_null($notification)) ? new srNotificationLanguage() : $notification;
        $notification->setLanguage($language);
        $notification->setSubject($subject);
        $notification->setText($text);
        $notification->setNotificationId($this->notification->getId());
        $notification->save();
    }


    /**
     * @param $title
     * @param $description
     * @param $name
     * @param $default_language
     * @param array $notifications
     */
    protected function createOrUpdate($title, $description, $name, $default_language, array $notifications = array())
    {
        $this->notification = ($this->notification) ? $this->notification : new srNotification();
        $this->notification->setTitle($title);
        $this->notification->setDefaultLanguage($default_language);
        $this->notification->setDescription($description);
        $this->notification->setName($name);
        $this->notification->save();
        foreach ($notifications as $notification) {
            $this->createOrUpdateNotification($notification['language'], $notification['subject'], $notification['text']);
        }
    }

}