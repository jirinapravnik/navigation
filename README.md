#Navigation
==========

Control pro Nette Framework usnadňující tvorbu menu a drobečkové navigace

Autor: Jan Marek
Autor: Jiří Nápravník
Licence: MIT

##Výhody proti původnímu řešení
* možno k jednotlivým Nodům přidat title atribut
* drobečková navigace podporuje mikrodata
* možno používat i "zkrácený  zápis url" místo:

```php
$nav->navAdd('Úvod', $this->link('Homepage:'), 'Jít na úvodní stránku');
```

lze použít:
```php
$nav->navAdd('Úvod', 'Homepage:', 'Jít na úvodní stránku');
```

##Instalace
Nejlépe přes Composer

```json
{
	"require": {
        	"jirinapravnik/navigation": "@dev"
    	}
}
```

##Použití
###Továrnička v presenteru:

```php
	protected function createComponentNavigation($name) {
		$nav = new Navigation($this, $name);
		$nav->setupHomepage('Úvod', $this->link('Homepage:'), 'Jít na úvodní stránku');
		$sec = $nav->add('Sekce', $this->link('Category:', array('id' => 1)));
		$article = $sec->add('Článek', $this->link('Article:', array('id' => 1)));
		$nav->setCurrentNode($article);
	}
```


###Menu v šabloně:
```
{control navigation}
```


###Drobečková navigace v šabloně:
```
{control navigation:breadcrumbs}
```
