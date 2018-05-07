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
        $googleClient = $container->getDefinition('google.client');
        $googleClient->addMethodCall('setApplicationName', ['Google Drive API']);
        $googleClient->addMethodCall('setScopes', [GoogleDriveConfig::$allscope]);
        $projectDir = $container->getParameter('kernel.project_dir');
        $googleClient->addMethodCall('setAuthConfig', [$projectDir.'/config/client_secret.json']);
        $googleClient->addMethodCall('setAccessType', ['offline']);
        if($container->hasParameter('tsk.google_client.token')){
            $token = $container->getParameter('tsk.google_client.token');
            $googleClient->addMethodCall('setAccessToken', [$token]);
        }
    }
}