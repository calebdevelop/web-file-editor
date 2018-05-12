<?php
/**
 * Created by PhpStorm.
 * User: tarask
 * Date: 5/9/18
 * Time: 2:31 PM
 */

namespace TSK\WebFileEditorBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\DateTime;
use TSK\WebFileEditorBundle\Entity\FileInterface;

class FileUploader
{

    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        return $file->move($this->getTargetDirectory(), $fileName);

    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}