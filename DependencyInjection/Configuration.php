<?php
/**
 * Created by PhpStorm.
 * User: tarask
 * Date: 5/8/18
 * Time: 4:52 PM
 */

namespace TSK\WebFileEditorBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use TSK\WebFileEditorBundle\Form\Type\BaseFileType;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('file_editor');

        $rootNode
            ->children()
                ->arrayNode('file')
                    ->children()
                        ->scalarNode('file_class')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('file_manager')->end()
                        ->scalarNode('upload_dir')->isRequired()->cannotBeEmpty()->end()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('type')->isRequired()->defaultValue(BaseFileType::class)->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}