<?php

/**
 * Created by PhpStorm.
 * User: tarask
 * Date: 5/6/18
 * Time: 9:17 AM
 */
namespace TSK\WebFileEditorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use TSK\WebFileEditorBundle\Util\GoogleDriveConfig;

class FileEditorPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     */
    public function process(ContainerBuilder $container)
    {

    }
}