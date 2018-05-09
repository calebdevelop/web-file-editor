<?php
/**
 * Created by PhpStorm.
 * User: tarask
 * Date: 5/6/18
 * Time: 5:28 PM
 */

namespace TSK\WebFileEditorBundle\Doctrine;

use App\Entity\File;
use Doctrine\Common\Persistence\ObjectManager;
use TSK\WebFileEditorBundle\Entity\FileInterface;
use TSK\WebFileEditorBundle\Entity\FileManager as BaseFileManager;

class FileManager extends BaseFileManager
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var ObjectRepository
     */
    protected $repository;

    public function __construct(ObjectManager $om, $class)
    {
        $this->objectManager = $om;
        $this->repository = $om->getRepository($class);

        $metadata = $om->getClassMetadata($class);
        $this->class = $metadata->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * {@inheritdoc}
     */
    public function updateFile(FileInterface $group, $andFlush = true)
    {
        $this->objectManager->persist($group);
        if ($andFlush) {
            $this->objectManager->flush();
        }
    }
}