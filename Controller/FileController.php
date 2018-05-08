<?php
/**
 * Created by PhpStorm.
 * User: tarask
 * Date: 5/6/18
 * Time: 6:32 PM
 */

namespace TSK\WebFileEditorBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use TSK\WebFileEditorBundle\Entity\FileManagerInterface;
use TSK\WebFileEditorBundle\Form\Factory\FactoryInterface;

class FileController extends Controller
{
    private $eventDispatcher;

    private $formFactory;

    private $fileManager;

    public function __construct(EventDispatcherInterface $eventDispatcher, FactoryInterface $formFactory, FileManagerInterface $fileManager)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->formFactory = $formFactory;
        $this->fileManager = $fileManager;
    }

    public function listAction(){

        return $this->render('@TSKWebFileEditor/list_file.html.twig', array(

        ));
    }

    public function addAction(){

        return $this->render('@TSKWebFileEditor/add_file.html.twig', array(

        ));
    }
}