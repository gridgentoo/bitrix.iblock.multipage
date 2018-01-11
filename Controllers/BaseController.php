<?php

namespace Falur\Bitrix\Components\IblockMultipage\Controllers;

use Falur\Bitrix\Components\IblockMultipage\Services\Elements;
use Falur\Bitrix\Components\IblockMultipage\Services\Sections;
use Falur\Bitrix\Support\Component\BaseController as ComponentBaseController;
use Psr\Container\ContainerInterface;

class BaseController extends ComponentBaseController
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $container['sections'] = new Sections($container);
        $container['elements'] = new Elements($container);
    }
}