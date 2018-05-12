<?php
/**
 * Created by PhpStorm.
 * User: tarask
 * Date: 5/7/18
 * Time: 5:37 AM
 */

namespace TSK\WebFileEditorBundle\Form\Factory;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormFactory implements FactoryInterface
{
    private $type;

    private $class;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory, $type, $class){
        $this->formFactory = $formFactory;
        $this->type = $type;
        $this->class = $class;
    }

    /**
     * @return FormInterface
     */
    public function createForm() : FormInterface
    {
        $formBuilder = $this->formFactory->createBuilder($this->type);
        return $formBuilder->getForm();
    }
}