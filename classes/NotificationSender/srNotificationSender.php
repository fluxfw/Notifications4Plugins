<?php


/**
 * Interface srNotificationSender
 */
interface srNotificationSender
{

    /**
     * Send the notification
     *
     * @return bool
     */
    public function send();

    /**
     * Set the message to send
     *
     * @param string $message
     */
    public function setMessage($message);

    /**
     * Set the subject for the message
     *
     * @param string $subject
     */
    public function setSubject($subject);

}