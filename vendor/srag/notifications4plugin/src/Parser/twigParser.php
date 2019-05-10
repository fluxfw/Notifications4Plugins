<?php

namespace srag\Notifications4Plugin\Notifications4Plugins\Parser;

use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Notifications4Plugin\Notifications4Plugins\Utils\Notifications4PluginTrait;
use Twig_Environment;
use Twig_Error;
use Twig_Loader_String;

/**
 * Class twigParser
 *
 * @package srag\Notifications4Plugin\Notifications4Plugins\Parser
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
class twigParser implements Parser {

	use DICTrait;
	use Notifications4PluginTrait;
	const NAME = "twig";
	const DOC_LINK = "https://twig.symfony.com/doc/1.x/templates.html";
	/**
	 * @var array
	 */
	protected $options = [
		"autoescape" => false // Do not auto escape variables by default when using {{ myVar }}
	];


	/**
	 * twigParser constructor
	 *
	 * @param array $options
	 */
	public function __construct(array $options = []) {
		$this->options = array_merge($this->options, $options);
	}


	/**
	 * @inheritdoc
	 *
	 * @throws Twig_Error
	 */
	public function parse(string $text, array $placeholders = []): string {
		$loader = new Twig_Loader_String();

		$twig = new Twig_Environment($loader, $this->options);

		return $twig->render($text, $placeholders);
	}
}
