<?php

namespace srag\Plugins\Notifications4Plugins\Parser;

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
	 * @param array  $replacements
	 *
	 * @return string
	 */
	public function parse($text, array $replacements = array());
}
