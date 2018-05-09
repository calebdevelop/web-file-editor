<?php
/**
 * Created by PhpStorm.
 * User: tarask
 * Date: 5/6/18
 * Time: 1:47 PM
 */

namespace TSK\WebFileEditorBundle\Entity;


abstract class FileManager implements FileManagerInterface
{
    /**
     * {@inheritdoc}
     */
    public function createFile($name = '')
    {
        $class = $this->getClass();

        return new $class($name);
    }
}