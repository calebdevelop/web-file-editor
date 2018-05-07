<?php
/**
 * Created by PhpStorm.
 * User: tarask
 * Date: 5/6/18
 * Time: 6:05 AM
 */

namespace TSK\WebFileEditorBundle;


use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use TSK\WebFileEditorBundle\DependencyInjection\Compiler\FileEditorPass;
use TSK\WebFileEditorBundle\DependencyInjection\FileEditorExtension;

class TSKWebFileEditorBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new FileEditorPass());

        $this->addRegisterMappingsPass($container);
    }

    public function getContainerExtension()
    {
        return new FileEditorExtension();
    }

    /**
     * @param ContainerBuilder $container
     */
    private function addRegisterMappingsPass(ContainerBuilder $container)
    {
        if(class_exists('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass')){
            $container->addCompilerPass(DoctrineOrmMappingsPass::createAnnotationMappingDriver(
                ['TSK\WebFileEditorBundle\Entity'],
                [ realpath(__DIR__.'/Entity')],
                [],
                false,
                ['TSKWebFileEditorBundle' => 'TSK\WebFileEditorBundle\Entity']
            ));
        }

    }
}