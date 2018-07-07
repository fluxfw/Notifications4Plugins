<?php
require_once __DIR__ . '/../../vendor/autoload.php';
/**
 * Interface srNotificationSender
 */
interface srNotificationSender {

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


	/**
	 * @param mixed $from
	 */
	public function setFrom($from);


	/**
	 * @param mixed $to
	 */
	public function setTo($to);


	/**
	 * @param mixed $bcc
	 */
	public function setBcc($bcc);


	/**
	 * @param mixed $cc
	 */
	public function setCc($cc);


	/**
	 * Reset internal state of object, e.g. clear all data (from, to, subject, message etc.)
	 *
	 * @return $this
	 */
	public function reset();
}