<?php

namespace Falur\Bitrix\Components\IblockMultipage\Controllers;

use Bitrix\Iblock\InheritedProperty\SectionValues;
use Falur\Bitrix\Components\IblockMultipage\Services\Sections as SectionsService;
use Falur\Bitrix\Components\IblockMultipage\Services\Elements as ElementsService;
use CDBResult;
use Falur\Bitrix\Support\Component\BaseController;

/**
 * Class CategoryController
 * @package Falur\Bitrix\Components\IblockMultipage\Controllers
 * @property \IblockMultipageComponent $component
 * @property SectionsService $sections
 * @property ElementsService $elements
 */
class CategoryController extends BaseController
{
    /**
     * @param $request
     * @param $response
     * @param $args
     * @throws \Slim\Exception\NotFoundException
     */
    public function index($request, $response, $args)
    {
        $additionalFilter = $this->component->param('FILTER', []);
        $paginationCount = $this->component->param('PAGINATION_COUNT');
        $nav = CDBResult::NavStringForCache($paginationCount);
        $cacheId = application()->GetCurDir() . $nav . implode('', $additionalFilter);

        if ($this->component->startResultCache(false, $cacheId)) {
            // Section
            $section = $this->sections->one($args['category']);
            $this->notFoundExceptionIf(!$section);

            // Sections
            $sections = $this->sections->childs($section['ID']);

            // MetaInfo
            $sectionPath = \CIBlockSection::GetNavChain($section['IBLOCK_ID'], $section['IBLOCK_SECTION_ID']);
            $propValues = (new SectionValues($section['IBLOCK_ID'], $section['ID']))->getValues();

            // Items
            if (!isset($additionalFilter['SECTION_ID'])) {
                $additionalFilter['SECTION_ID'] = $section['ID'];
            }
            [$items, $pagination] = $this->elements->withPagination($additionalFilter);

            $this->component->SetResultCacheKeys(['SECTION', 'IPROPERTY_VALUES', 'SECTION_PATH']);

            $this->component->view('category', [
                'ITEMS'            => $items,
                'SECTION'          => $section,
                'SECTIONS'         => $sections,
                'PAGINATION'       => $pagination,
                'SECTION_PATH'     => $sectionPath,
                'IPROPERTY_VALUES' => $propValues,
            ]);
        }

        $this->setMeta();
        $this->setBreadcrumbs();
    }

    protected function setMeta()
    {
        $iprops = $this->component->arResult['IPROPERTY_VALUES'];
        $section = $this->component->arResult['SECTION'];

        $this->component->setTitle($iprops['SECTION_PAGE_TITLE'] ?: $section['NAME']);
        $this->component->setMeta('title', $iprops['SECTION_META_TITLE']);
        $this->component->setMeta('keywords', $iprops['SECTION_META_KEYWORDS']);
        $this->component->setMeta('description', $iprops['SECTION_META_DESCRIPTION']);
    }

    protected function setBreadcrumbs()
    {
        $sectionPath = $this->component->arResult['SECTION_PATH'];
        $section = $this->component->arResult['SECTION'];

        foreach ($sectionPath as $sect) {
            $this->component->addBreadcrumbs($sect['NAME'], $sect['SECTION_PAGE_URL']);
        }

        $this->component->addBreadcrumbs($section['NAME'], $section['SECTION_PAGE_URL']);
    }
}
