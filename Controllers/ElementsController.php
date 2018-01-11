<?php

namespace Falur\Bitrix\Components\IblockMultipage\Controllers;

use CDBResult;
use Falur\Bitrix\Components\IblockMultipage\Services\Sections as SectionsService;
use Falur\Bitrix\Components\IblockMultipage\Services\Elements as ElementsService;
use Falur\Bitrix\Support\Component\BaseController;

/**
 * Class ElementsController
 * @package Falur\Bitrix\Components\IblockMultipage\Controllers
 * @property \IblockMultipageComponent $component
 * @property SectionsService $sections
 * @property ElementsService $elements
 */
class ElementsController extends BaseController
{
    public function index()
    {
        $additionalFilter = $this->component->param('FILTER', []);
        $paginationCount = $this->component->param('PAGINATION_COUNT');
        $nav = CDBResult::NavStringForCache($paginationCount);
        $cacheId = application()->GetCurDir() . $nav . implode('', $additionalFilter);

        if ($this->component->startResultCache(false, $cacheId)) {
            [$items, $pagination] = $this->elements->withPagination($additionalFilter);

            $this->component->view('elements', [
                'ITEMS'            => $items,
                'PAGINATION'       => $pagination,
            ]);
        }
    }
}
