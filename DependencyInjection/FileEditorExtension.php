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
    private function remapParameters(string $parameter, array $config, ContainerBuilder $container, array $arrayValue = []){
        foreach ($config as $key => $value){
            //print_r($value);
            if(is_array($value) && in_array($key, $arrayValue)){
                $container->setParameter( $parameter.'.'.$key, $value);
            }elseif(is_array($value) && !in_array($key, $arrayValue)){
                $this->remapParameters($parameter.'.'.$key, $value, $container, $arrayValue);
            }else{
                $container->setParameter( $parameter.'.'.$key, $value);
            }
        }
    }

    private function loadFile(array $config, ContainerBuilder $container, YamlFileLoader $loader){
        $this->remapParameters('file_editor.file', $config, $container);
        $loader->load('file.yml');
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        
        if(isset($config['file'])){
            $this->loadFile($config['file'], $container, $loader);
        }

        if (!empty($config['google'])) {
            $this->loadGoogleClient($config['google'], $container, $loader);
        }

        $loader->load('console.yml');
    }

    private function loadGoogleClient(array $config, ContainerBuilder $container, YamlFileLoader $loader){
        $this->remapParameters('file_editor.google', $config, $container, ['token']);
        $loader->load('google.yml');

        $googleClient = $container->getDefinition('google.client');
        $googleClient->addMethodCall('setApplicationName', ['Google Drive API']);
        $googleClient->addMethodCall('setScopes', [GoogleDriveConfig::$allscope]);
        $projectDir = $container->getParameter('kernel.project_dir');
        $googleClient->addMethodCall('setAuthConfig', [$projectDir.'/config/client_secret.json']);
        $googleClient->addMethodCall('setAccessType', ['offline']);

        if($container->hasParameter('file_editor.google.token')){
            $token = $container->getParameter('file_editor.google.token');
            $googleClient->addMethodCall('setAccessToken', [$token]);
        }
    }
}
