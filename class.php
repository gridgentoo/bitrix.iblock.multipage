<?php

use Falur\Bitrix\Support\Component\MultiPageComponent;

class IblockMultipageComponent extends MultiPageComponent
{
    protected $namespace = '\\Falur\\Bitrix\\Components\\IblockMultipage\\Controllers\\';

    protected function requiredParams(): array
    {
        return [
            'IBLOCK_TYPE',
            'IBLOCK_ID',
        ];
    }

    protected function params(): array
    {
        return [
            'CACHE_TYPE' => 'A',
            'CACHE_TIME' => 3600,
            'ELEMENTS_SORT_BY_1' => 'SORT',
            'ELEMENTS_SORT_ORDER_1' => 'ASC',
            'ELEMENTS_SORT_BY_2' => 'NAME',
            'ELEMENTS_SORT_ORDER_2' => 'ASC',
            'SORT_CATEGORIES_BY' => 'SORT',
            'SORT_CATEGORIES_ORDER' => 'ASC',
            'ACTIVE_DATE' => 'N',
            'PAGINATION_COUNT' => 10,
            'PAGINATION_TEMPLATE' => '',
            'PAGINATION_NAME' => 'Страницы',
            'ADD_CACHE_STRING' => ''
        ];
    }

    protected function modules(): array
    {
        return ['iblock'];
    }

    protected function registerRoutes()
    {
        if ($this->param('CATEGORIES') == 'Y') {
            $this->slim->any('/', $this->action('CategoriesController', 'index'));
            $this->slim->any('/{category}', $this->action('CategoryController', 'index'));
            $this->slim->any('/{category}/{element}', $this->action('ElementController', 'index'));
        } else {
            $this->slim->any('/', $this->action('CategoryController', 'index'));
            $this->slim->any('/{element}', $this->action('ElementController', 'index'));
        }
    }

    protected function action(string $controller, string $action)
    {
        return  $this->namespace . $controller . ':' . $action;
    }
}
