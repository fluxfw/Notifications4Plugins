<?php

namespace srag\Plugins\Notifications4Plugins\Parser;

/**
 * Interface srNotificationParser
 *
 * @package srag\Plugins\Notifications4Plugins\Parser
 */
interface srNotificationParser {

	/**
	 * @param string $text
	 * @param array  $replacements
	 *
	 * @return string
	 */
	public function parse($text, array $replacements = array());
}
