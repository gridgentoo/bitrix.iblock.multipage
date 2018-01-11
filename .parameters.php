<?php
check_prolog();

$params = new \Falur\Bitrix\Support\Component\Parameters;
$factory = new \Falur\Bitrix\Support\Component\Parameters\Factory;

$sort = [
    'ID'          => trans('T_IBLOCK_DESC_FID'),
    'NAME'        => trans('T_IBLOCK_DESC_FNAME'),
    'ACTIVE_FROM' => trans('T_IBLOCK_DESC_FACT'),
    'SORT'        => trans('T_IBLOCK_DESC_FSORT'),
    'TIMESTAMP_X' => trans('T_IBLOCK_DESC_FTSAMP'),
];

$order = [
    'ASC'  => trans('T_IBLOCK_DESC_ASC'),
    'DESC' => trans('T_IBLOCK_DESC_DESC'),
];

$params->addParameter(
    $factory->iblockType('IBLOCK_TYPE')
        ->setName('Тип инфоблока')
        ->setRefresh(true)
);

$params->addParameter(
    $factory->iblockId('IBLOCK_ID')
        ->setName('Инфоблок')
        ->setRefresh(true)
        ->setDefault('')
        ->setFilter([
            'SITE_ID' => SITE_ID,
            'TYPE'    => $arCurrentValues['IBLOCK_TYPE'] ?? '',
        ])
);

$params->addParameter(
    $factory->booleanParameter('ACTIVE_DATE')
        ->setName('Выводить только активные элементы')
        ->setDefault(false)
);

$params->addParameter(
    $factory->select('ELEMENTS_SORT_BY_1')
        ->setName('Сортировка элементов 1')
        ->setDefault('NAME')
        ->setValues($sort)
);

$params->addParameter(
    $factory->select('ELEMENTS_SORT_ORDER_1')
        ->setName('Направление сортировки элементов 1')
        ->setDefault('NAME')
        ->setValues($order)
);

$params->addParameter(
    $factory->select('ELEMENTS_SORT_BY_2')
        ->setName('Сортировка элементов 2')
        ->setDefault('NAME')
        ->setValues($sort)
);

$params->addParameter(
    $factory->select('ELEMENTS_SORT_ORDER_2')
        ->setName('Направление сортировки элементов 2')
        ->setDefault('NAME')
        ->setValues($order)
);

$params->addParameter(
    $factory->select('SORT_CATEGORIES_BY')
        ->setName('Сортировка категорий')
        ->setDefault('NAME')
        ->setValues($sort)
);

$params->addParameter(
    $factory->select('SORT_CATEGORIES_ORDER')
        ->setName('Направление сортировки категорий')
        ->setDefault('NAME')
        ->setValues($order)
);

$params->addParameter(
    $factory->select('FILTER')
        ->setName('Дополнительный фильтр')
        ->setValues([])

);

$params->addParameter(
    $factory->stringParameter('PAGINATION_COUNT')
        ->setName('Количество показываемых элементов')
        ->setDefault('10')
);

$params->addParameter(
    $factory->stringParameter('PAGINATION_TEMPLATE')
        ->setName('Шаблон постраничной навигации')
        ->setDefault('')
);

$params->addParameter(
    $factory->stringParameter('PAGINATION_NAME')
        ->setName('Заголовок постраничной навигации')
        ->setDefault('Страницы')
);

$params->addParameter(
    $factory->stringParameter('ADD_CACHE_STRING')
        ->setName('Строка добавляемая в кеш')
        ->setDefault('')
);

$arComponentParameters = $params->toArray();
