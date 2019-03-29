<?php

namespace srag\Notifications4Plugin\Notifications4Plugins\Exception;

use ilException;

/**
 * Class Notifications4PluginException
 *
 * @package srag\Notifications4Plugin\Notifications4Plugins\Exception
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Notifications4PluginException extends ilException {

	/**
	 * Notifications4PluginException constructor
	 *
	 * @param string $message
	 * @param int    $code
	 */
	public function __construct(string $message, int $code = 0) {
		parent::__construct($message, $code);
	}
}
