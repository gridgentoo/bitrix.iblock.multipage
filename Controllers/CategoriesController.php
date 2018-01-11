<?php

namespace Falur\Bitrix\Components\IblockMultipage\Controllers;

use Falur\Bitrix\Components\IblockMultipage\Services\Sections as SectionsService;
use Falur\Bitrix\Components\IblockMultipage\Services\Elements as ElementsService;
use Falur\Bitrix\Support\Component\BaseController;

/**
 * Class CategoriesController
 * @package Falur\Bitrix\Components\IblockMultipage\Controllers
 * @property \IblockMultipageComponent $component
 * @property SectionsService $sections
 * @property ElementsService $elements
 */
class CategoriesController extends BaseController
{
	public function index()
	{
        if ($this->component->startResultCache(false, application()->GetCurDir())) {
            $sections = $this->sections->allFirstLevel();
            $this->component->view('categories', ['SECTIONS' => $sections]);
        }
	}
}
