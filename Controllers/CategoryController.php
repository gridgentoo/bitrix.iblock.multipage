<?php

namespace IblockMultypage\Controllers;

use Libs\Iblock\Sections;
use Libs\Iblock\Elements;

class CategoryController extends BaseController
{
	protected $pagination;
	
	public function indexAction()
	{		
		global $APPLICATION;
		
		$get_params = $_GET['PAGEN_1'] ?: $_GET['PAGEN_2'] ?: $_GET['PAGEN_3'] ?: '';
		$cache_id = $APPLICATION->GetCurDir() . $get_params;
		
		$arNavParams = [
			"nPageSize" => $this->bitrix->arParams['PAGINATION'],
			"bDescPageNumbering" => false,
			"bShowAll" => false,
		];
		$arNavigation = \CDBResult::GetNavParams($arNavParams);
		
		if ( $this->bitrix->StartResultCache(false, $cache_id, $arNavigation) )
		{
			$this->bitrix->arResult['SECTION'] = $this->getSection();

			if (empty($this->bitrix->arResult['SECTION']))
				return $this->error404();

			$this->bitrix->arResult['ITEMS'] = $this->getElements();
			$this->bitrix->arResult['SECTIONS'] = $this->getSections();
			$this->bitrix->arResult['PAGINATION'] = $this->getPagination();
			
			$this->bitrix->arResult['SECTION_PATH'] = 
				Sections::getPath(
					$this->bitrix->arParams['IBLOCK_ID'], 
					$this->bitrix->arResult['SECTION']['IBLOCK_SECTION_ID']
				);

			$this->bitrix->arResult['IPROPERTY_VALUES'] = 
				(new \Bitrix\Iblock\InheritedProperty\SectionValues(
						$this->bitrix->arResult['SECTION']['IBLOCK_ID'], 
						$this->bitrix->arResult['SECTION']['ID']
				))->getValues();
			
			$this->bitrix->SetResultCacheKeys(['SECTION', 'IPROPERTY_VALUES', 'SECTION_PATH']);
			$this->bitrix->IncludeComponentTemplate('category');
		}	
			
		$this->setMetaInfo();
	}
	
	public function getPagination()
	{
		return $this->pagination;
	}
	
	/**
	 * Получить информацию по текущей категории
	 * 
	 * @return array
	 */
	protected function getSection()
	{
		$section_code = $this->slim->router->getCurrentRoute()->getParam('category');
		
		return Sections::getSection(
			[
				'IBLOCK_ID' => $this->bitrix->arParams['IBLOCK_ID'],
				'ACTIVE' => 'Y',
				'CODE' => $section_code
			],
			$this->bitrix->arParams['IMG_CACHE']['CATEGORIES']
		);
	}
	
	/**
	 * Получить дочерние категории
	 * 
	 * @return array
	 */
	protected function getSections()
	{
		$sections = Sections::getSections(
			[
				'IBLOCK_ID' => $this->bitrix->arParams['IBLOCK_ID'],
				'ACTIVE' => 'Y',
				'GLOBAL_ACTIVE' => 'Y',
				'CNT_ACTIVE' => 'Y',
				'SECTION_ID' => $this->bitrix->arResult['SECTION']['ID']
			],
			$this->bitrix->arParams['SORT']['CATEGORIES'],
			$this->bitrix->arParams['IMG_CACHE']['CATEGORIES']
		);
		
		return $sections['SECTIONS'];
	}
	
	/**
	 * Получить элементы категории
	 * 
	 * @return array
	 */
	protected function getElements()
	{
		$items = Elements::getElements(
			[
				'IBLOCK_ID' => $this->bitrix->arParams['IBLOCK_ID'],
				'ACTIVE' => 'Y',
				'ACTIVE_DATE' => $this->bitrix->arParams['ACTIVE_DATE'] ?: 'N',
				'SECTION_ID' => $this->bitrix->arResult['SECTION']['ID']
			], 
			$this->bitrix->arParams['SORT']['ELEMENTS'], 
			$this->bitrix->arParams['PAGINATION'],
			$this->bitrix->arParams['IMG_CACHE']['ELEMENTS']
		);
		
		$this->pagination = $items['PAGINATION'];
		
		return $items['ITEMS'];
	}
	
	/**
	 * Устанавливает всю мета информацию включая хлебные крошки
	 * 
	 * @global CMain $APPLICATION
	 */
	protected function setMetaInfo()
	{
		global $APPLICATION;
		
		$iprops = $this->bitrix->arResult['IPROPERTY_VALUES'];

		// Установим TITLE
		if (!empty($iprops['SECTION_PAGE_TITLE']))
			$APPLICATION->SetTitle($iprops['SECTION_PAGE_TITLE']);
		else
			$APPLICATION->SetTitle($this->bitrix->arResult['SECTION']['NAME']);

		if (is_array($iprops['SECTION_META_TITLE']))
			$APPLICATION->SetPageProperty('title', implode(' ', $iprops['SECTION_META_TITLE']));
		elseif (!empty($iprops['SECTION_META_TITLE']))
			$APPLICATION->SetPageProperty('title', $iprops['SECTION_META_TITLE']);

		// Установим Keywords 
		if (is_array($iprops['SECTION_META_KEYWORDS']))
			$APPLICATION->SetPageProperty('keywords', implode(' ', $iprops['SECTION_META_KEYWORDS']));
		elseif (!empty($iprops['SECTION_META_KEYWORDS']))
			$APPLICATION->SetPageProperty('keywords', $iprops['SECTION_META_KEYWORDS']);

		// Установим Description
		if (is_array($iprops['SECTION_META_DESCRIPTION']))
			$APPLICATION->SetPageProperty('description', implode(' ', $iprops['SECTION_META_DESCRIPTION']));
		elseif (!empty($iprops['SECTION_META_DESCRIPTION']))
			$APPLICATION->SetPageProperty('description', $iprops['SECTION_META_DESCRIPTION']);

		// Установим хлебные крошки
		$section_path = $this->bitrix->arResult['SECTION_PATH'];

		foreach ($section_path as $section)
		{
			$APPLICATION->AddChainItem(
				$section['NAME'], 
				$section['SECTION_PAGE_URL']
			);
		}
		
		$APPLICATION->AddChainItem(
			$this->bitrix->arResult['SECTION']['NAME'], 
			$this->bitrix->arResult['SECTION']['SECTION_PAGE_URL']
		);
	}
}
