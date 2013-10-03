<?php

/**
 * Navigation node
 *
 * @author Jan Marek
 * @author Jiří Nápravník (jiri.napravnik@gmail.com)
 * @license MIT
 */

namespace Enbros\Navigation;

use Nette\ComponentModel\Container;

class NavigationNode extends Container
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
	private $isCurrent = FALSE;

//	public $children = array();
	
	/**
	 * Add navigation node as a child
	 * @staticvar int $counter
	 * @param string $label
	 * @param string $url
	 * @param string $title
	 * @return NavigationNode
	 */
	public function addNode($label, $url, $title = NULL)
	{
		$navigationNode = new self();
		$navigationNode->setLabel($label);
		$navigationNode->setUrl($url);
		$navigationNode->setTitle($title);

		static $counter;
		$this->addComponent($navigationNode, ++$counter);
		
		//$this->children[] = $navigationNode;

		return $navigationNode;
	}

	/**
	 * Set node as current
	 * @param bool $current
	 * @return \Navigation\NavigationNode
	 */
	public function setCurrent($current)
	{
		$this->isCurrent = $current;

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

	public function getIsCurrent()
	{
		return $this->isCurrent;
	}
	
	public function getChildren()
	{
		return $this->children;
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

	public function setIsCurrent($isCurrent)
	{
		$this->isCurrent = $isCurrent;
		return $this;
	}

}
