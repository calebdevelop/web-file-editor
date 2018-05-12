<?php
/**
 * Created by PhpStorm.
 * User: tarask
 * Date: 5/8/18
 * Time: 4:52 PM
 */

namespace TSK\WebFileEditorBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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

        $this->googleConfig($rootNode);

        return $treeBuilder;
    }

    private function googleConfig(ArrayNodeDefinition $node){
        $node
            ->children()
                ->arrayNode('google')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('token')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('access_token')->isRequired()->defaultValue('')->end()
                                ->scalarNode('token_type')->isRequired()->defaultValue('Bearer')->end()
                                ->scalarNode('expires_in')->isRequired()->defaultValue(3600)->end()
                                ->scalarNode('refresh_token')->isRequired()->defaultValue('')->end()
                                ->scalarNode('created')->isRequired()->defaultValue(1525624344)->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}