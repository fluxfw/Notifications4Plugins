<?php

namespace srag\Notifications4Plugin\Notifications4Plugins\Sender;

use ilMimeMail;
use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Notifications4Plugin\Notifications4Plugins\Exception\Notifications4PluginException;
use srag\Notifications4Plugin\Notifications4Plugins\Utils\Notifications4PluginTrait;

/**
 * Class MailSender
 *
 * Sends the notification to an external E-Mail address using the ILIAS mailer class
 *
 * @package srag\Notifications4Plugin\Notifications4Plugins\Sender
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
class ExternalMailSender implements Sender {

	use DICTrait;
	use Notifications4PluginTrait;
	/**
	 * @var string
	 */
	protected $message = "";
	/**
	 * @var string
	 */
	protected $subject = "";
	/**
	 * @var string
	 */
	protected $from = "";
	/**
	 * @var string|array
	 */
	protected $to;
	/**
	 * @var ilMimeMail
	 */
	protected $mailer;
	/**
	 * @var array
	 */
	protected $attachments = array();
	/**
	 * @var string|array
	 */
	protected $cc = array();
	/**
	 * @var string|array
	 */
	protected $bcc = array();


	/**
	 * MailSender constructor
	 *
	 * @param string       $from E-Mail from address. If omitted, the ILIAS setting "external noreply address" is used
	 * @param string|array $to   E-Mail address or array of addresses
	 */
	public function __construct(string $from = "", $to = "") {
		$this->from = $from;
		$this->to = $to;
		$this->mailer = new ilMimeMail();
	}


	/**
	 * @inheritdoc
	 */
	public function send()/*: void*/ {
		$from = ($this->from) ? $this->from : self::dic()->ilias()->getSetting("mail_external_sender_noreply");
		$this->mailer->From(self::dic()->mailMimeSenderFactory()->userByEmailAddress($from));

		$this->mailer->To($this->to);

		$this->mailer->Cc($this->cc);
		$this->mailer->Bcc($this->bcc);

		$this->mailer->Subject($this->subject);

		$this->mailer->Body($this->message);

		foreach ($this->attachments as $attachment) {
			$this->mailer->Attach($attachment);
		}

		$sent = $this->mailer->Send();

		if (!$sent) {
			throw new Notifications4PluginException("Mailer not returns true");
		}
	}


	/**
	 * Add an attachment
	 *
	 * @param string $file Full path of the file to attach
	 *
	 * @return $this
	 */
	public function addAttachment($file) {
		if (is_file($file)) {
			$this->attachments[] = $file;
		}

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function setSubject($subject) {
		$this->subject = $subject;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function setMessage($message) {
		$this->message = $message;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getFrom() {
		return $this->from;
	}


	/**
	 * @inheritdoc
	 */
	public function setFrom($from) {
		$this->from = $from;

		return $this;
	}


	/**
	 * @return array|string
	 */
	public function getTo() {
		return $this->to;
	}


	/**
	 * @inheritdoc
	 */
	public function setTo($to) {
		$this->to = $to;

		return $this;
	}


	/**
	 * @return array|string
	 */
	public function getCc() {
		return $this->cc;
	}


	/**
	 * @inheritdoc
	 */
	public function setCc($cc) {
		$this->cc = $cc;

		return $this;
	}


	/**
	 * @return array|string
	 */
	public function getBcc() {
		return $this->bcc;
	}


	/**
	 * @inheritdoc
	 */
	public function setBcc($bcc) {
		$this->bcc = $bcc;
	}


	/**
	 * @inheritdoc
	 */
	public function reset() {
		$this->from = "";
		$this->to = "";
		$this->subject = "";
		$this->message = "";
		$this->attachments = array();
		$this->cc = array();
		$this->bcc = array();
		$this->mailer = new ilMimeMail();

		return $this;
	}
}
