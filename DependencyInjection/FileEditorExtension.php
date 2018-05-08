<?php
/**
 * Created by PhpStorm.
 * User: tarask
 * Date: 5/6/18
 * Time: 7:38 AM
 */

namespace TSK\WebFileEditorBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use TSK\WebFileEditorBundle\Util\GoogleDriveConfig;

class FileEditorExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('console.yml');
        $loader->load('doctrine.yml');

        $loader->load('file.yml');


        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        //tsk_file_editor.file_manager
        $fileMagagerDefinition = $container->getDefinition('tsk_file_editor.file_manager');
        $fileMagagerDefinition->replaceArgument(1, $config['file']['file_class']);



        if (!empty($config['google'])) {
            $this->loadGoogleClient($config['google'], $container);
        }





    }

    private function loadGoogleClient(array $config, ContainerBuilder $container){
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