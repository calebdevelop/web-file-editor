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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use TSK\WebFileEditorBundle\Entity\FileManagerInterface;
use TSK\WebFileEditorBundle\Form\Factory\FactoryInterface;
use TSK\WebFileEditorBundle\Service\FileUploader;

class FileController extends Controller
{
    private $eventDispatcher;

    private $formFactory;

    private $fileManager;

    private $fileUploader;

    public function __construct(EventDispatcherInterface $eventDispatcher, FactoryInterface $formFactory, FileManagerInterface $fileManager, FileUploader $fileUploader)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->formFactory = $formFactory;
        $this->fileManager = $fileManager;
        $this->fileUploader = $fileUploader;
    }

    public function listAction(){
        return $this->render('@TSKWebFileEditor/list_file.html.twig', array(

        ));
    }

    public function addAction(Request $request){
        $entity = $this->fileManager->createFile();

        $form = $this->formFactory->createForm();

        $form->setdata($entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $entity->getFile();

            $fileUploaded = $this->fileUploader->upload($file);

            $entity->setName($fileUploaded->getBasename());
            $entity->setContentType($file->getClientMimeType());
            $entity->setCreateAt(new \DateTime());
            $entity->setPath($this->fileUploader->getTargetDirectory());
            $entity->setFile($fileUploaded->getBasename());

            $this->fileManager->updateFile($entity, true);

        }

        return $this->render('@TSKWebFileEditor/add_file.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}