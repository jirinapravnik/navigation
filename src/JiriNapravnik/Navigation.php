<?php

namespace JiriNapravnik;

use JiriNapravnik\Navigation\Node;
use Nette\Application\UI;
use Nette\Utils\Strings;

/**
 * Navigation
 *
 * @author Jan Marek
 * @author Jiří Nápravník (jiri.napravnik@gmail.com)
 * @license MIT
 */
class Navigation extends UI\Control
{

	/**
	 * @var Node 
	 */
	private $homepage;

	/**
	 * @var Node 
	 */
	private $current;

	/**
	 * @var bool 
	 */
	private $useHomepage = FALSE;
	
	/**
	 * @var string
	 */
	private $ulClass = NULL;

	/**
	 * @var string 
	 */
	private $menuTemplate;

	/**
	 * @var string 
	 */
	private $breadcrumbsTemplate;
	
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Set node as current
	 * @param Node $node
	 */
	public function setCurrentNode(Node $node)
	{
		if (isset($this->current)) {
			$this->current->isCurrent = FALSE;
		}
		$node->isCurrent = TRUE;
		$this->current = $node;
	}

	public function setCurrentNodeByUrl($url, $node = NULL)
	{
		if (is_null($node)) {
			$node = $this->getComponent('homepage');
		}

		if ($node->getUrl() === $url) {
			$this->setCurrentNode($node);
			return;
		}

		foreach ($node->getComponents() as $component) {
			$this->setCurrentNodeByUrl($url, $component);
		}
	}

	/**
	 * Add navigation node as a child
	 * @param string $label
	 * @param string $url
	 * @param string $title
	 * @return Node
	 */
	public function addNode($label, $url, $title = NULL)
	{
		return $this->getComponent('homepage')->addNode($label, $url, $title);
	}

	/**
	 * Setup homepage
	 * @param string $label
	 * @param string $url
	 * @param string $title
	 * @return Node
	 */
	public function setupHomepage($label, $url, $title = NULL)
	{
		$homepage = $this['homepage'];
		$homepage->setLabel($label);
		$homepage->setUrl($url);
		$homepage->setTitle($title);
		$this->useHomepage = TRUE;
		return $homepage;
	}

	/**
	 * Homepage factory
	 * @param string $name
	 */
	protected function createComponentHomepage($name)
	{
		new Node($this, $name);
	}

	/**
	 * Render menu
	 * @param bool $renderChildren
	 * @param Node $base
	 * @param bool $renderHomepage
	 */
	public function renderMenu($renderChildren = TRUE, $base = NULL, $renderHomepage = TRUE)
	{
		$template = $this->createTemplate()
			->setFile($this->menuTemplate ? : __DIR__ . '/templates/menu.phtml');
		$template->homepage = $base ? $base : $this['homepage'];
		$template->useHomepage = $this->useHomepage && $renderHomepage;
		$template->ulClass = $this->ulClass;
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

		while ($node instanceof Node) {
			$parent = $node->getParent();
			if (!$this->useHomepage && $parent === $this->getComponent('homepage')) {
				$breadcrumbs = $node;
				break;
			}

			foreach ($parent->getComponents() as $component) {
				if ($component != $node) {
					$parent->removeComponent($component);
				}
			}

			$node = $parent;

			if ($node === $this->getComponent('homepage')) {
				$breadcrumbs = $node;
			}
		}

		$template = $this->createTemplate()
			->setFile($this->breadcrumbsTemplate ? : __DIR__ . '/templates/breadcrumbs.phtml');

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
	 * @return Node
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

	public function setUlClass($ulClass)
	{
		$this->ulClass = $ulClass;
	}

}
