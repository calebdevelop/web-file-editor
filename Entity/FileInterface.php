<?php
/**
 * Created by PhpStorm.
 * User: tarask
 * Date: 5/9/18
 * Time: 4:24 PM
 */

namespace TSK\WebFileEditorBundle\Entity;


interface FileInterface
{
    public function getId();

    public function getName();

    public function setName(string $name);

    public function getPath();

    public function setPath(string $path);

    public function getContentType();

    public function setContentType(string $contentType);

    public function getFile();

    public function setFile($file);

    public function getCreateAt();

    public function setCreateAt(?\DateTimeInterface $createAt);

    public function getUpdateAt();

    public function setUpdateAt(?\DateTimeInterface $updateAt);
}