<?php

namespace JiriNapravnik\Menu;

use JiriNapravnik\Navigation\Node;
use Nette\Application\UI;

/**
 * Navigation
 *
 * @author Jan Marek
 * @author Jiří Nápravník (jiri.napravnik@gmail.com)
 * @license MIT
 */
class Menu extends UI\Control
{

	/**
	 * @var Node 
	 */
	private $homepage;

	/**
	 * @var Node 
	 */
	private $current;
	private $useHomepage = FALSE;
	private $renderChildren = FALSE;
	private $menuTemplate;

	public function __construct()
	{
		parent::__construct();

		$this->menuTemplate = __DIR__ . '/menu.latte';
	}

	/**
	 * Set node as current
	 * @param Node $node
	 */
	public function setCurrentNode(Node $node)
	{
		$this->current = $node;

		while ($node instanceof Node) {
			$node->setActive(TRUE);
			$node = $node->getParent();
		}
	}

	public function setCurrentNodeByUrl($url, $node = NULL)
	{
		if ($node === NULL) {
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
		return $homepage;
	}

	/**
	 * Homepage factory
	 * @param string $name
	 */
	protected function createComponentHomepage($name)
	{
		return new Node($this, $name);
	}

	public function render()
	{
		$this->template->useHomepage = $this->useHomepage;
		$this->template->renderChildren = $this->renderChildren;
		$this->template->homepage = $this['homepage'];
		$this->template->children = $this->getComponent('homepage')->getComponents();
		$this->template->setFile($this->menuTemplate);
		$this->template->render();
	}

	public function getItemsForBreadcrumbs()
	{
		if (empty($this->current)) {
			return [];
		}

		$node = $this->current;
		$breadcrumbs = [];

		while ($node instanceof Node) {
			$arr = [
				'name' => $node->getLabel(),
				'url' => $node->getUrl(),
				'title' => $node->getTitle(),
			];
			array_unshift($breadcrumbs, $arr);

			$node = $node->getParent();

			if ($node === $this->getComponent('homepage')) {
				break;
			}
		}
		
		return $breadcrumbs;
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

	public function setUseHomepage($useHomepage)
	{
		$this->useHomepage = $useHomepage;
		return $this;
	}

	public function setRenderChildren($renderChildren)
	{
		$this->renderChildren = $renderChildren;
	}

}
