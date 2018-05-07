<?php
/**
 * Created by PhpStorm.
 * User: tarask
 * Date: 5/6/18
 * Time: 7:38 AM
 */

namespace TSK\WebFileEditorBundle\DependencyInjection;


use FOS\UserBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class FileEditorExtension extends Extension
{
    /**
     * @var array
     */
    private static $doctrineDrivers = array(
        'orm' => array(
            'registry' => 'doctrine',
            'tag' => 'doctrine.event_subscriber',
        ),
        'mongodb' => array(
            'registry' => 'doctrine_mongodb',
            'tag' => 'doctrine_mongodb.odm.event_subscriber',
        ),
        'couchdb' => array(
            'registry' => 'doctrine_couchdb',
            'tag' => 'doctrine_couchdb.event_subscriber',
            'listener_class' => 'FOS\UserBundle\Doctrine\CouchDB\UserListener',
        ),
    );

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('console.yml');
        $loader->load('doctrine.yml');

        //$container->setAlias('tsk_file_editor.doctrine_registry', new Alias(self::$doctrineDrivers['orm']['doctrine'], false));

        if (isset(self::$doctrineDrivers['orm']['doctrine'])) {
            //$definition = $container->getDefinition('tsk_file_editor.object_manager');
            //$definition->setFactory(array(new Reference('tsk_file_editor.doctrine_registry'), 'getManager'));
        }

        $this->loadFile($container, $loader);
    }

    private function loadFile(ContainerBuilder $container, YamlFileLoader $loader){
        $loader->load('file.yml');
    }
}