<?php

namespace srag\Notifications4Plugin\Notifications4Plugins\Parser;

use srag\Notifications4Plugin\Notifications4Plugins\Exception\Notifications4PluginException;
use srag\Notifications4Plugin\Notifications4Plugins\Notification\Notification;

/**
 * Interface RepositoryInterface
 *
 * @package srag\Notifications4Plugin\Notifications4Plugins\Parser
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface RepositoryInterface {

	/**
	 * @param Parser $parser
	 */
	public function addParser(Parser $parser);


	/**
	 * @return FactoryInterface
	 */
	public function factory(): FactoryInterface;


	/**
	 * @return Parser[]
	 */
	public function getPossibleParsers(): array;


	/**
	 * @param string $parser_class
	 *
	 * @return Parser
	 *
	 * @throws Notifications4PluginException
	 */
	public function getParserByClass(string $parser_class): Parser;


	/**
	 * @param Notification $notification
	 *
	 * @return Parser
	 *
	 * @throws Notifications4PluginException
	 */
	public function getParserForNotification(Notification $notification): Parser;


	/**
	 * @param Parser       $parser
	 * @param Notification $notification
	 * @param array        $placeholders
	 * @param string       $language
	 *
	 * @return string
	 *
	 * @throws Notifications4PluginException
	 */
	public function parseSubject(Parser $parser, Notification $notification, array $placeholders = [], string $language = ""): string;


	/**
	 * @param Parser       $parser
	 * @param Notification $notification
	 * @param array        $placeholders
	 * @param string       $language
	 *
	 * @return string
	 *
	 * @throws Notifications4PluginException
	 */
	public function parseText(Parser $parser, Notification $notification, array $placeholders = [], string $language = ""): string;
}
