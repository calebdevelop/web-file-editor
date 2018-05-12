<?php
/**
 * Created by PhpStorm.
 * User: tarask
 * Date: 5/7/18
 * Time: 5:40 AM
 */

namespace TSK\WebFileEditorBundle\Form\Factory;


interface FactoryInterface
{
    /**
     * @return FormInterface
     */
    public function createForm();
}