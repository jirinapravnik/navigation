<?php

namespace JiriNapravnik\Breadcrumbs;

use JiriNapravnik\Navigation\Node;
use Nette\Application\UI;

/**
 * Navigation
 *
 * @author Jan Marek
 * @author Jiří Nápravník (jiri.napravnik@gmail.com)
 * @license MIT
 */
class Breadcrumbs extends UI\Control
{
	private $breadcrumbs;
	private $breadcrumbsTemplate;

	public function __construct()
	{
		parent::__construct();

		$this->breadcrumbsTemplate = __DIR__ . '/breadcrumbs.latte';
	}

	public function getSize(){
		return count($this->breadcrumbs);
	}
	
	/**
	 * Add navigation node as a child
	 * @param string $name
	 * @param string $url
	 * @param string $title
	 * @return Node
	 */
	public function addNode($name, $url, $title = NULL)
	{
		$this->breadcrumbs[] = [
			'name' => $name,
			'url' => $url,
			'title' => $title,
		];
	}

	/**
	 * Setup homepage
	 * @param string $name
	 * @param string $url
	 * @param string $title
	 * @return Node
	 */
	public function setupHomepage($name, $url, $title = NULL)
	{
		$this->addNode($name, $url, $title);
	}

	public function render()
	{		
		$this->template->breadcrumbs = $this->breadcrumbs;
		$this->template->setFile($this->breadcrumbsTemplate);
		$this->template->render();
	}
}
