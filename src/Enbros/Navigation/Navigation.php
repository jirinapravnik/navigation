<?php

/**
 * Navigation
 *
 * @author Jan Marek
 * @author Jiří Nápravník (jiri.napravnik@gmail.com)
 * @license MIT
 */

namespace Enbros\Navigation;

use Nette\Application\UI\Control;

class Navigation extends Control
{

	/**
	 * @var NavigationNode 
	 */
	private $homepage;

	/**
	 * @var NavigationNode 
	 */
	private $current;

	/**
	 * @var bool 
	 */
	private $useHomepage = FALSE;

	/**
	 * @var string 
	 */
	private $menuTemplate;

	/**
	 * @var string 
	 */
	private $breadcrumbsTemplate;

	/**
	 * Set node as current
	 * @param NavigationNode $node
	 */
	public function setCurrentNode(NavigationNode $node)
	{
		if (isset($this->current)) {
			$this->current->isCurrent = FALSE;
		}
		$node->isCurrent = TRUE;
		$this->current = $node;
	}

	/**
	 * Add navigation node as a child
	 * @param string $label
	 * @param string $url
	 * @param string $title
	 * @return NavigationNode
	 */
	public function addNode($label, $url, $title = NULL)
	{
		return $this->getComponent('homepage')->addNode($label, $this->getCorrectUrl($url), $title);
	}

	/**
	 * Setup homepage
	 * @param string $label
	 * @param string $url
	 * @param string $title
	 * @return NavigationNode
	 */
	public function setupHomepage($label, $url, $title = NULL)
	{
		$homepage = $this->getComponent('homepage');
		$homepage->setLabel($label);
		$homepage->setUrl($this->getCorrectUrl($url));
		$homepage->setTitle($title);
		$this->useHomepage = TRUE;
		return $homepage;
	}

	protected function getCorrectUrl($url)
	{
		try {
			$link = $this->presenter->link($url);
		} catch (\Nette\Application\UI\InvalidLinkException $ex) {
			$link = FALSE;
		}

		if ($link === FALSE || \Nette\Utils\Strings::startsWith($link, "error") === TRUE) {
			return $url;
		} else {
			return $link;
		}
	}

	/**
	 * Homepage factory
	 * @param string $name
	 */
	protected function createComponentHomepage($name)
	{
		new NavigationNode($this, $name);
	}

	/**
	 * Render menu
	 * @param bool $renderChildren
	 * @param NavigationNode $base
	 * @param bool $renderHomepage
	 */
	public function renderMenu($renderChildren = TRUE, $base = NULL, $renderHomepage = TRUE)
	{
		$template = $this->createTemplate()
			->setFile($this->menuTemplate ? : __DIR__ . '/templates/menu.phtml');
		$template->homepage = $base ? $base : $this->getComponent('homepage');
		$template->useHomepage = $this->useHomepage && $renderHomepage;
		$template->renderChildren = $renderChildren;
		$template->children = $this->getComponent('homepage')->getComponents();
		$template->render();
	}

	/**
	 * Render full menu
	 */
	public function render()
	{
		$this->renderMenu();
	}

	/**
	 * Render main menu
	 */
	public function renderMainMenu()
	{
		$this->renderMenu(FALSE);
	}

	/**
	 * Render breadcrumbs
	 */
	public function renderBreadcrumbs()
	{
		if (empty($this->current)) {
			return;
		}

		$node = $this->current;
		$breadcrumbs = null;
		
		while ($node instanceof NavigationNode) {
			$parent = $node->getParent();
			if (!$this->useHomepage && $parent === $this->getComponent('homepage')) {
				$breadcrumbs = $node;
				break;
			}
			
			foreach($parent->getComponents() as $component){
				if($component != $node){
					$parent->removeComponent($component);
				}
			}
			
			$node = $parent;
			
			if($node === $this->getComponent('homepage')){
				$breadcrumbs = $node;
			}
		}
		
		$template = $this->createTemplate()
			->setFile($this->breadcrumbsTemplate ? : __DIR__ . '/templates/breadcrumbs.phtml');
//		\Nette\Diagnostics\Debugger::dump($breadcrumbs);
		
		
		$template->useHomepage = $this->useHomepage;
		$template->breadcrumbs = array($breadcrumbs);
		$template->render();
	}

	/**
	 * @param string $breadcrumbsTemplate
	 */
	public function setBreadcrumbsTemplate($breadcrumbsTemplate)
	{
		$this->breadcrumbsTemplate = $breadcrumbsTemplate;
	}

	/**
	 * @param string $menuTemplate
	 */
	public function setMenuTemplate($menuTemplate)
	{
		$this->menuTemplate = $menuTemplate;
	}

	/**
	 * @return NavigationNode
	 */
	public function getCurrentNode()
	{
		return $this->current;
	}

	public function setUseHomepage($useHomepage)
	{
		$this->useHomepage = $useHomepage;
		return $this;
	}

}
