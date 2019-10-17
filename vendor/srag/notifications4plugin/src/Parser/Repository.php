<?php

namespace srag\Notifications4Plugin\Notifications4Plugins\Parser;

use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Notifications4Plugin\Notifications4Plugins\Exception\Notifications4PluginException;
use srag\Notifications4Plugin\Notifications4Plugins\Notification\Notification;
use srag\Notifications4Plugin\Notifications4Plugins\Utils\Notifications4PluginTrait;

/**
 * Class Repository
 *
 * @package srag\Notifications4Plugin\Notifications4Plugins\Parser
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository implements RepositoryInterface {

	use DICTrait;
	use Notifications4PluginTrait;
	/**
	 * @var RepositoryInterface
	 */
	protected static $instance = null;


	/**
	 * @return RepositoryInterface
	 */
	public static function getInstance(): RepositoryInterface {
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * @var array
	 */
	protected $parsers = [
		twigParser::class => twigParser::NAME
	];


	/**
	 * Repository constructor
	 */
	private function __construct() {

	}


	/**
	 * @inheritdoc
	 */
	public function addParser(Parser $parser) {
		$parser_class = get_class($parser);

		$this->parsers[$parser_class] = $parser_class::NAME;
	}


	/**
	 * @inheritdoc
	 */
	public function factory(): FactoryInterface {
		return Factory::getInstance();
	}


	/**
	 * @inheritdoc
	 */
	public function getPossibleParsers(): array {
		return $this->parsers;
	}


	/**
	 * @inheritdoc
	 */
	public function getParserByClass(string $parser_class): Parser {
		if (isset($this->getPossibleParsers()[$parser_class])) {
			return new $parser_class();
		} else {
			throw new Notifications4PluginException("Invalid parser class $parser_class");
		}
	}


	/**
	 * @inheritdoc
	 */
	public function getParserForNotification(Notification $notification): Parser {
		return $this->getParserByClass($notification->getParser());
	}


	/**
	 * @inheritdoc
	 */
	public function parseSubject(Parser $parser, Notification $notification, array $placeholders = [], string $language = ""): string {
		return $parser->parse($notification->getSubject($language), $placeholders);
	}


	/**
	 * @inheritdoc
	 */
	public function parseText(Parser $parser, Notification $notification, array $placeholders = [], string $language = ""): string {
		return $parser->parse($notification->getText($language), $placeholders);
	}
}
