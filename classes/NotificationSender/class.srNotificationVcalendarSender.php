<?php
require_once __DIR__ . '/../../vendor/autoload.php';
/**
 * Class srNotificationVcalendarSender
 *
 * Sends the notification to an external E-Mail with calendar dates
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class srNotificationVcalendarSender implements srNotificationSender {

	CONST METHOD_REQUEST = 'REQUEST';
	CONST METHOD_CANCEL = 'CANCEL';

	/**
	 * @var string
	 */
	protected $message = '';
	/**
	 * @var string
	 */
	protected $subject = '';
	/**
	 * @var string
	 */
	protected $location = '';
	/**
	 * @var ilMail
	 */
	protected $mailer;
	/**
	 * @var string|array
	 */
	protected $to;
	/**
	 * User-ID or login of sender
	 *
	 * @var int|string
	 */
	protected $user_from = 0;
	/**
	 * @var string
	 */
	protected $method = self::METHOD_REQUEST;
	/**
	 * @var string
	 */
	protected $uid = '';
	/**
	 * @var int
	 */
	protected $startTime = 0;
	/**
	 * @var int
	 */
	protected $endTime = 0;
	/**
	 * @var int
	 */
	protected $sequence = 0;
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
	 * srNotificationVcalendarSender constructor.
	 *
	 * @param string|array $to   E-Mail address or array of addresses
	 * @param int|string|ilObjUser $user_from Should be the user-ID from the sender, you can also pass the login
	 * @param string  $method
	 * @param int $startTime Timestamp
	 * @param int $endTime Timestamp
	 * @param int     $sequence
	 *
	 */
	public function __construct($to = '',
								$user_from = 0,
								$method = self::METHOD_REQUEST,
								$uid = '',
								$startTime = 0,
								$endTime = 0,
								$sequence = 0) {

		global $DIC;
		$ilias = $DIC["ilias"];

		$this->to = $to;

		if ($user_from) {
			$this->setUserFrom($user_from);
		}

		$this->method = $method;
		$this->uid = $uid;
		$this->startTime = $startTime;
		$this->endTime = $endTime;
		$this->sequence = $sequence;

	}


	/**
	 * Send the notification
	 *
	 * @return bool
	 */
	public function send() {
		global $DIC;
		$ilias = $DIC["ilias"];

		$this->mailer = new ilMail($this->getUserFrom());


		$mbox           = new ilMailbox($this->getUserFrom());
		$sent_folder_id = $mbox->getSentFolder();

		//Create Email Headers
		$mime_boundary = "----Meeting Booking----".MD5(TIME());

		$this->mailer->sendInternalMail(
			$sent_folder_id, $this->getUserFrom(), '',
			$this->to,'', '',
			'read', 'email', 0,
			$this->subject, $this->getIcalEvent($mime_boundary), $this->getUserFrom(), 0
		);

		$this->mailer = new ilMail($this->getUserFrom());


		$iluser = new ilObjUser($this->getUserFrom());
		$headers = "From: ".$iluser->getEmail()." <".$iluser->getEmail().">\n";
		$headers .= "Reply-To: ".$iluser->getEmail()." <".$iluser->getEmail().">\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
		$headers .= "Content-class: urn:content-classes:calendarmessage\n";

		$mailsent = mail($this->to, $this->subject, $this->getIcalEvent($mime_boundary), $headers);

		return ($mailsent)?(true):(false);
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
	 * Set the location for the message
	 *
	 * @param string location
	 *
	 * @return $this
	 */
	public function setLocation($location) {
		$this->location = $location;

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
	 * @return int|string
	 */
	public function getUserFrom() {
		return $this->user_from;
	}

	/**
	 * @param int|string|ilObjUser $from
	 *
	 * @return $this
	 */
	public function setFrom($from) {
		$this->setUserFrom($from);

		return $this;
	}

	/**
	 * @param int|string|ilObjUser $user_from
	 *
	 * @return $this
	 */
	public function setUserFrom($user_from) {
		if ($user_from instanceof ilObjUser) {
			$user_from = $user_from->getId();
		} else {
			if (is_string($user_from) && !is_numeric($user_from)) {
				// Need user-ID
				$user_from = ilObjUser::_lookupId($user_from);
			}
		}
		$this->user_from = (int)$user_from;

		return $this;
	}

	public function getIcalEvent($mime_boundary)
	{
		$iluser = new ilObjUser($this->getUserFrom());

		//Create Email Body (HTML)
		$message = "--$mime_boundary\r\n";
		$message .= "Content-Type: text/html; charset=UTF-8\n";
		$message .= "Content-Transfer-Encoding: 8bit\n\n";
		$message .= "<html>\n";
		$message .= "<body>\n";
		$message .= nl2br($this->message);
		//$message .= '<p>'.$description.'</p>';
		$message .= "</body>\n";
		$message .= "</html>\n";
		$message .= "--$mime_boundary\r\n";

		$status = "CONFIRMED";
		if($this->method == self::METHOD_CANCEL) {
			$status = "CANCELLED";
		}


		$ical = 'BEGIN:VCALENDAR' . "\r\n" .
			'PRODID:-//ILIAS' . "\r\n" .
			'VERSION:2.0' . "\r\n" .
			'METHOD:' .$this->method. "\r\n" .
			'BEGIN:VEVENT' . "\r\n" .
			'UID: ' .$this->uid. "\r\n" .
			'DESCRIPTION:Reminder' . "\r\n" .
			'DTSTART:'.date("Ymd\THis", $this->startTime). "\r\n" .
			'DTEND:'.date("Ymd\THis", $this->endTime). "\r\n" .
			'DTSTAMP:'.date("Ymd\TGis"). "\r\n" .
			'LAST-MODIFIED:' . date("Ymd\TGis") . "\r\n" .
			'ORGANIZER;CN="'.$this->from.'":MAILTO:'.$iluser->getEmail(). "\r\n" .
			'ATTENDEE;CN="'.$this->to.'";ROLE=REQ-PARTICIPANT;RSVP=TRUE:MAILTO:'.$this->to. "\r\n" .
			'SUMMARY:' . $this->subject . "\r\n" .
			'LOCATION:' . $this->location . "\r\n" .
			'SEQUENCE:'. $this->sequence . "\r\n" .
			'PRIORITY:5'. "\r\n" .
			'STATUS:'.$status. "\r\n" .
			'TRANSP:OPAQUE'. "\r\n" .
			'CLASS:PUBLIC'. "\r\n" .
			'BEGIN:VALARM' . "\r\n" .
			'TRIGGER:-PT15M' . "\r\n" .
			'ACTION:DISPLAY' . "\r\n" .
			'END:VALARM' . "\r\n" .
			'END:VEVENT'. "\r\n" .
			'END:VCALENDAR'. "\r\n";
		$message .= 'Content-Type: text/calendar;name="meeting.ics";method='.$this->method."\n";
		$message .= "Content-Transfer-Encoding: 8bit\n\n";
		$message .= $ical;

		return $message;
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
		$this->uid = '';
		$this->method = self::METHOD_REQUEST;
		$this->sequence = 0;
		$this->startTime = 0;
		$this->endTime = 0;
		$this->attachments = array();
		$this->cc = array();
		$this->bcc = array();
		$this->mailer = new ilMimeMail();
	}
}