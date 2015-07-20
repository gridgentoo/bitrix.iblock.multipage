<?php

namespace IblockMultypage\Controllers;

use Libs\Iblock\Elements;

class ElementsController extends BaseController
{
	public function indexAction()
	{
		global $APPLICATION;
		
		$get_params = $_GET['PAGEN_1'] ?: $_GET['PAGEN_2'] ?: $_GET['PAGEN_3'] ?: '';
		$cache_id = $APPLICATION->GetCurDir() . $get_params;
		
		$arNavParams = [
			"nPageSize" => $this->bitrix->arParams['PAGINATION'] ?: 10,
			"bDescPageNumbering" => false,
			"bShowAll" => false,
		];
		$arNavigation = \CDBResult::GetNavParams($arNavParams);
		
		if ( $this->bitrix->StartResultCache(false, $cache_id, $arNavigation) )
		{
			$this->bitrix->arResult = Elements::getElements(
				[
					'IBLOCK_ID' => $this->bitrix->arParams['IBLOCK_ID'],
					'ACTIVE' => 'Y',
					'ACTIVE_DATE' => $this->bitrix->arParams['ACTIVE_DATE'] ?: 'N'
				], 
				$this->bitrix->arParams['SORT']['ELEMENTS'], 
				$this->bitrix->arParams['PAGINATION'],
				$this->bitrix->arParams['IMG_CACHE']['ELEMENTS']
			);

			$this->bitrix->IncludeComponentTemplate('elements');
		}
	}
}
