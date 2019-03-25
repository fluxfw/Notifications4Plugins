<?php

namespace srag\Plugins\Notifications4Plugins\Parser;

use srag\Notifications4Plugins\Exception\Notifications4PluginsException;

/**
 * Interface Parser
 *
 * @package srag\Plugins\Notifications4Plugins\Parser
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
interface Parser {

	/**
	 * @param string $text
	 * @param array  $placeholders
	 *
	 * @return string
	 *
	 * @throws Notifications4PluginsException
	 */
	public function parse(/*string*/
		$text, array $placeholders = array())/*: string*/
	;
}
