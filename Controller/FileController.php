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
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    public function viewAction($fileId){

        $file = $this->fileManager->find($fileId);
        $content = file_get_contents($this->fileUploader->getTargetDirectory().'/'.$file->getName());

        $client = $this->get('google.client');
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        }
        $driveService = new \Google_Service_Drive($client);
        $fileMetadata = new \Google_Service_Drive_DriveFile(array(
            'name' => $file->getName(),

        ));

        $uploadedFile = $driveService->files->create($fileMetadata, array(
                'data' => $content,
                //'mimeType' => 'image/jpeg',
                'uploadType' => 'multipart',
                'fields' => 'id',
                )
        );
        printf("File ID: %s\n", $uploadedFile->id);

        $driveService->getClient()->setUseBatch(true);
        try {
            $batch = $driveService->createBatch();

            $userPermission = new \Google_Service_Drive_Permission(array(
                'type' => 'anyone',
                'role' => 'writer',
            ));

            $request = $driveService->permissions->create(
                $uploadedFile->id, $userPermission, array('fields' => 'id'));

            $batch->add($request, 'user');

            //$fileDrive = $driveService->files->get($uploadedFile->id, ['alt' => 'media']);

            /*$batch->add($request, 'user');
            $results = $batch->execute();

            foreach ($results as $result) {
                if ($result instanceof \Google_Service_Exception) {
                    // Handle error
                    printf($result);
                } else {
                    printf("Permission ID: %s\n", $result->id);
                }
            }*/
        } finally {
            $driveService->getClient()->setUseBatch(false);
        }

        return new Response('view file');
    }
}