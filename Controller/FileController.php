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

class FileController extends Controller
{
    private $eventDispatcher;

    private $fileManager;

    public function __construct(EventDispatcherInterface $eventDispatcher, FileManagerInterface $fileManager)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->fileManager = $fileManager;
    }

    public function listAction(){
        return new Response('hello');
    }
}