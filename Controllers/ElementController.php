<?php

namespace Falur\Bitrix\Components\IblockMultipage\Controllers;

use Bitrix\Iblock\InheritedProperty\ElementValues;
use Falur\Bitrix\Components\IblockMultipage\Services\Sections as SectionsService;
use Falur\Bitrix\Components\IblockMultipage\Services\Elements as ElementsService;
use Falur\Bitrix\Support\Component\BaseController;

/**
 * Class ElementController
 * @package Falur\Bitrix\Components\IblockMultipage\Controllers
 * @property \IblockMultipageComponent $component
 * @property SectionsService $sections
 * @property ElementsService $elements
 */
class ElementController extends BaseController
{
    /**
     * @param $request
     * @param $response
     * @param $args
     * @throws \Slim\Exception\NotFoundException
     */
    public function index($request, $response, $args)
	{
		if ($this->bitrix->StartResultCache(false, application()->GetCurDir())) {
		    $item = $this->elements->one($args['element']);
			$this->notFoundExceptionIf(!$item);
			
			$iprops = (new ElementValues($item['IBLOCK_ID'], $item['ID']))->getValues();
			$sectionPath = \CIBlockSection::GetNavChain($item['IBLOCK_ID'], $item['IBLOCK_SECTION_ID']);
			
			$this->bitrix->SetResultCacheKeys(['ID', 'NAME', 'DETAIL_PAGE_URL', 'IPROPERTY_VALUES', 'SECTION_PATH']);
            
			$this->component->view('element', $item + [
			    'IPROPERTY_VALUES' => $iprops,
			    'SECTION_PATH' => $sectionPath,
            ]);
		}
		
		$this->setMeta();
        $this->setBreadcrumbs();
	}

	protected function setMeta()
	{
        $iprops = $this->component->arResult['IPROPERTY_VALUES'];
        $item = $this->component->arResult;

        $this->component->setTitle($iprops['ELEMENT_PAGE_TITLE'] ?: $item['NAME']);
        $this->component->setMeta('title', $iprops['ELEMENT_META_TITLE']);
        $this->component->setMeta('keywords', $iprops['ELEMENT_META_KEYWORDS']);
        $this->component->setMeta('description', $iprops['ELEMENT_META_DESCRIPTION']);
	}

    protected function setBreadcrumbs()
    {
        $sectionPath = $this->component->arResult['SECTION_PATH'];
        $item = $this->component->arResult;

        foreach ($sectionPath as $sect) {
            $this->component->addBreadcrumbs($sect['NAME'], $sect['SECTION_PAGE_URL']);
        }

        $this->component->addBreadcrumbs($item['NAME'], $item['SECTION_PAGE_URL']);
    }
}
