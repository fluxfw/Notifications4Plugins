<?php

namespace srag\Plugins\Notifications4Plugins\Notifier;

use ilNotifications4PluginsPlugin;
use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Plugins\Notifications4Plugins\Notification\Notification;
use srag\Plugins\Notifications4Plugins\Sender\Sender;
use srag\Plugins\Notifications4Plugins\Parser\Parser;
use srag\Plugins\Notifications4Plugins\Parser\twigParser;
use srag\Plugins\Notifications4Plugins\Utils\Notifications4PluginsTrait;

/**
 * Class Notifier
 *
 * Wrapper class to send notifications. You can also send notifications directly via the srNotification objects,
 * see srNotification::send() for more informations.
 *
 * @package srag\Plugins\Notifications4Plugins\Notifier
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
class Notifier {

	use DICTrait;
	use Notifications4PluginsTrait;
	const PLUGIN_CLASS_NAME = ilNotifications4PluginsPlugin::class;
	/**
	 * @var Notification
	 */
	protected $notification;
	/**
	 * @var Sender
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
	 * @var Parser
	 */
	protected $parser;


	/**
	 * Notifier constructor
	 *
	 * @param Notification $notification
	 * @param Sender       $sender
	 * @param string       $language     If empty, the default language of the srNotification object is used
	 * @param array        $replacements If empty, placeholders are not replaced
	 * @param Parser       $parser
	 */
	public function __construct(Notification $notification, Sender $sender, $language = '', array $replacements = array(), Parser $parser = null) {
		$this->notification = $notification;
		$this->sender = $sender;
		$this->replacements = $replacements;
		$this->language = $language;
		$this->parser = $parser ? $parser : new twigParser();
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
