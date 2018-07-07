<?php
require_once __DIR__ . '/../vendor/autoload.php';
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
     * @param string $name
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
     * @param string $name
     * @param $default_language
     * @param array $notifications
     */
    public function update($title, $description, $name, $default_language, array $notifications = array())
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
    protected function createOrUpdate($title, $description, $name, $default_language, array $notifications = array())
    {
        $this->notification = ($this->notification) ? $this->notification : new srNotification();
        $this->notification->setTitle($title);
        $this->notification->setDefaultLanguage($default_language);
        $this->notification->setDescription($description);
        $this->notification->setName($name);
        $this->notification->save();
        foreach ($notifications as $notification) {
            $this->notification->setText($notification['text'], $notification['language']);
            $this->notification->setSubject($notification['subject'], $notification['language']);
        }
        $this->notification->save();
    }

}