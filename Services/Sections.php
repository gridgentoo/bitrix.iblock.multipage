<?php

namespace Falur\Bitrix\Components\IblockMultipage\Services;

use Psr\Container\ContainerInterface;

class Sections
{
    protected $container;
    /**
     * @var \IblockMultipageComponent
     */
    protected $component;
    protected $sectionsRepo;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->sectionsRepo = $container->get('iblockSections');
        $this->component = $container->get('component');
    }

    public function one($code)
    {
        return $this->sectionsRepo->filter([
            'IBLOCK_ID' => $this->component->param('IBLOCK_ID'),
            'ACTIVE'    => 'Y',
            'CODE'      => $code,
        ])->first();
    }

    public function childs($parentId)
    {
        return $this->sectionsRepo->filter([
            'IBLOCK_ID'     => $this->component->param('IBLOCK_ID'),
            'ACTIVE'        => 'Y',
            'GLOBAL_ACTIVE' => 'Y',
            'CNT_ACTIVE'    => 'Y',
            'SECTION_ID'    => $parentId,
        ])->sort([
            $this->component->param('CATEGORIES_SORT_BY') => $this->component->param('CATEGORIES_SORT_ORDER'),
        ])->all();
    }

    public function allFirstLevel()
    {
        return $this->sectionsRepo->filter([
            'IBLOCK_ID'     => $this->component->param('IBLOCK_ID'),
            'ACTIVE'        => 'Y',
            'GLOBAL_ACTIVE' => 'Y',
            'CNT_ACTIVE'    => 'Y',
            'DEPTH_LEVEL'   => 1
        ])->sort([
            $this->component->param('SORT_CATEGORIES_BY') => $this->component->param('SORT_CATEGORIES_ORDER')
        ])->all();
    }
}