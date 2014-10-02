<?php

namespace JiriNapravnik\Navigation;

use Nette\ComponentModel\Container;

/**
 * Navigation node
 *
 * @author Jan Marek
 * @author Jiří Nápravník (jiri.napravnik@gmail.com)
 * @license MIT
 */
class Node extends Container
{

	/**
	 * @var string 
	 */
	private $label;

	/**
	 * @var string 
	 */
	private $url;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var bool 
	 */
	private $active = FALSE;
	private $visibleInMenu = TRUE;
	private $visibleInBreadcrumbs = TRUE;

	/**
	 * Add navigation node as a child
	 * @staticvar int $counter
	 * @param string $label
	 * @param string $url
	 * @param string $title
	 * @return Node
	 */
	public function addNode($label, $url, $title = NULL)
	{
		$node = new self();
		$node->setLabel($label);
		$node->setUrl($url);
		$node->setTitle($title);

		static $counter;
		$this->addComponent($node, ++$counter);

		return $node;
	}

	/**
	 * Set node as current
	 * @param bool $current
	 * @return Node
	 */
	public function setCurrent($current)
	{
		$this->current = $current;

		if ($current) {
			$this->lookup('Navigation\Navigation')->setCurrentNode($this);
		}

		return $this;
	}

	public function getLabel()
	{
		return $this->label;
	}

	public function getUrl()
	{
		return $this->url;
	}

	public function getTitle()
	{
		return $this->title;
	}
	
	public function isActive(){
		return $this->active;
	}

	public function getChildren()
	{
		return $this->children;
	}

	public function isVisibleInMenu()
	{
		return $this->visibleInMenu;
	}

	public function isVisibleInBreadcrumbs()
	{
		return $this->visibleInBreadcrumbs;
	}

	public function setLabel($label)
	{
		$this->label = $label;
		return $this;
	}

	public function setUrl($url)
	{
		$this->url = $url;
		return $this;
	}

	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}

	public function setActive($active){
		$this->active = $active;
	}

	public function setVisibleInMenu($visibleInMenu)
	{
		$this->visibleInMenu = $visibleInMenu;
		return $this;
	}

	public function setVisibleInBreadcrumbs($visibleInBreadcrumbs)
	{
		$this->visibleInBreadcrumbs = $visibleInBreadcrumbs;
		return $this;
	}

}
