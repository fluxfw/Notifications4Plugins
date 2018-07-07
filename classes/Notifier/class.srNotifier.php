<?php
require_once __DIR__ . '/../vendor/autoload.php';
/**
 * Class srNotifier
 *
 * Wrapper class to send notifications. You can also send notifications directly via the srNotification objects,
 * see srNotification::send() for more informations.
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class srNotifier {

	/**
	 * @var srNotification
	 */
	protected $notification;
	/**
	 * @var srNotificationSender
	 */
	protected $sender;
	/**
	 * @var array
	 */
	protected $replacements = array();
	/**
	 * @var string
	 */
	protected $language = '';
	/**
	 * @var srNotificationParser
	 */
	protected $parser;


	/**
	 * @param srNotification       $notification
	 * @param srNotificationSender $sender
	 * @param string               $language     If empty, the default language of the srNotification object is used
	 * @param array                $replacements If empty, placeholders are not replaced
	 * @param srNotificationParser $parser
	 */
	public function __construct(srNotification $notification, srNotificationSender $sender, $language = '', array $replacements = array(), srNotificationParser $parser = NULL) {
		$this->notification = $notification;
		$this->sender = $sender;
		$this->replacements = $replacements;
		$this->language = $language;
		$this->parser = $parser ? $parser : new srNotificationTwigParser();
	}


	/**
	 * Start the notification
	 *
	 * @return bool
	 */
	public function notify() {
		// Parse the text and subject
		$text = $this->parser->parse($this->notification->getText($this->language), $this->replacements);
		$subject = $this->parser->parse($this->notification->getSubject($this->language), $this->replacements);

		// Send out the notification over the given sender object
		$this->sender->setMessage($text);
		$this->sender->setSubject($subject);

		//        var_dump($text);
		//        var_dump($subject);
		//        die();

		return $this->sender->send();
	}
}