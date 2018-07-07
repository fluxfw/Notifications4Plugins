<?php
require_once __DIR__ . '/../vendor/autoload.php';
/**
 * Class srNotificationMailSender
 *
 * Sends the notification to an external E-Mail address using the ILIAS mailer class
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class srNotificationMailSender implements srNotificationSender {

	/**
	 * @var string
	 */
	protected $message = '';
	/**
	 * @var string
	 */
	protected $subject = '';
	/**
	 * @var string|array
	 */
	protected $to;
	/**
	 * @var string
	 */
	protected $from = '';
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
	 * @param string|array $to   E-Mail address or array of addresses
	 * @param string       $from E-Mail from address. If omitted, the ILIAS setting 'external noreply address' is used
	 */
	public function __construct($to = '', $from = '') {
		$this->to = $to;
		$this->from = $from;
		$this->mailer = new ilMimeMail();
	}


	/**
	 * Send the notification
	 *
	 * @return bool
	 */
	public function send() {
		global $DIC;
		$ilias = $DIC["ilias"];

		$this->mailer->To($this->to);
		$from = ($this->from) ? $this->from : $ilias->getSetting('mail_external_sender_noreply');
		if (ILIAS_VERSION_NUMERIC >= "5.3") {
			/** @var ilMailMimeSenderFactory $senderFactory */
			$senderFactory = $DIC["mail.mime.sender.factory"];

			$this->mailer->From($senderFactory->userByEmailAddress($from));
		} else {
			$this->mailer->From($from);
		}
		$this->mailer->Cc($this->cc);
		$this->mailer->Bcc($this->bcc);
		$this->mailer->Subject($this->subject);
		$this->mailer->Body($this->message);
		foreach ($this->attachments as $attachment) {
			$this->mailer->Attach($attachment);
		}

		return $this->mailer->Send();
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
	 * Set the message to send
	 *
	 * @param string $message
	 *
	 * @return $this
	 */
	public function setMessage($message) {
		$this->message = $message;

		return $this;
	}


	/**
	 * Set the subject for the message
	 *
	 * @param string $subject
	 *
	 * @return $this
	 */
	public function setSubject($subject) {
		$this->subject = $subject;

		return $this;
	}


	/**
	 * @return array|string
	 */
	public function getCc() {
		return $this->cc;
	}


	/**
	 * @param array|string $cc
	 *
	 * @return $this
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
	 * @param array|string $bcc
	 */
	public function setBcc($bcc) {
		$this->bcc = $bcc;
	}


	/**
	 * @return array|string
	 */
	public function getTo() {
		return $this->to;
	}


	/**
	 * @param array|string $to
	 *
	 * @return $this
	 */
	public function setTo($to) {
		$this->to = $to;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getFrom() {
		return $this->from;
	}


	/**
	 * @param string $from
	 *
	 * @return $this
	 */
	public function setFrom($from) {
		$this->from = $from;

		return $this;
	}


	/**
	 * Reset internal state of object, e.g. clear all data (from, to, subject, message etc.)
	 *
	 * @return $this
	 */
	public function reset() {
		$this->from = '';
		$this->to = '';
		$this->subject = '';
		$this->message = '';
		$this->attachments = array();
		$this->cc = array();
		$this->bcc = array();
		$this->mailer = new ilMimeMail();
	}
}