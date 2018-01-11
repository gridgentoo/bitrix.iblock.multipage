<?php

namespace Falur\Bitrix\Components\IblockMultipage\Services;

use Psr\Container\ContainerInterface;

class Elements
{
    protected $container;
    /**
     * @var \IblockMultipageComponent
     */
    protected $component;
    protected $elementsRepo;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->elementsRepo = $container->get('iblockElements');
        $this->component = $container->get('component');
    }

    public function withPagination($additionalFilter)
    {
        $items = $this->elementsRepo
            ->filter(array_merge([
                'IBLOCK_ID'         => $this->component->param('IBLOCK_ID'),
                'ACTIVE'            => 'Y',
                'ACTIVE_DATE'       => $this->component->param('ACTIVE_DATE', 'Y'),
            ], $additionalFilter))
            ->sort([
                $this->component->param('ELEMENTS_SORT_BY_1') => $this->component->param('ELEMENTS_SORT_ORDER_1'),
                $this->component->param('ELEMENTS_SORT_BY_2') => $this->component->param('ELEMENTS_SORT_ORDER_2'),
            ])
            ->paginate(
                $this->component->param('PAGINATION_COUNT')
            );

        $pagination = $this->elementsRepo->getRes()->GetPageNavStringEx(
            $obj = null,
            $this->component->param('PAGINATION_NAME'),
            $this->component->param('PAGINATION_TEMPLATE')
        );

        return [
            $items,
            $pagination
        ];
    }

    public function one($code)
    {
        return $this->elementsRepo->filter([
            'IBLOCK_ID' => $this->component->param('IBLOCK_ID'),
            'ACTIVE' => 'Y',
            'CODE' => $code
        ])->first();
    }
}
