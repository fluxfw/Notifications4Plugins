<?php

namespace srag\Plugins\Notifications4Plugins\Sender;

/**
 * Interface Sender
 *
 * @package srag\Plugins\Notifications4Plugins\Sender
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
interface Sender {

	/**
	 * Send the notification
	 *
	 * @return bool
	 */
	public function send();


	/**
	 * Set the subject for the message
	 *
	 * @param string $subject
	 *
	 * @return $this
	 */
	public function setSubject($subject);


	/**
	 * Set the message to send
	 *
	 * @param string $message
	 *
	 * @return $this
	 */
	public function setMessage($message);


	/**
	 * @param string $from
	 *
	 * @return $this
	 */
	public function setFrom($from);


	/**
	 * @param array|string $to
	 *
	 * @return $this
	 */
	public function setTo($to);


	/**
	 * @param array|string $cc
	 *
	 * @return $this
	 */
	public function setCc($cc);


	/**
	 * @param array|string $bcc
	 *
	 * @return $this
	 */
	public function setBcc($bcc);


	/**
	 * Reset internal state of object, e.g. clear all data (from, to, subject, message etc.)
	 *
	 * @return $this
	 */
	public function reset();
}
