<?php

namespace Absolute\Module\File\Manager;

use Nette\Database\Context;
use Absolute\Module\File\Entity\File;
use Absolute\Core\Manager\BaseManager;

class FileManager extends BaseManager 
{
  public function __construct(Context $database) 
  {
    parent::__construct($database);
  }

  public function _getFile($db) 
  {
    if ($db == false) 
      return false;
    
    $object = new File($db->id, $db->path, $db->filename, $db->name, $db->type, $db->created);   
    return $object;
  } 

  /* INTERNAL/EXTERNAL INTERFACE */

  public function _getById($id) 
  {
    $resultDb = $this->database->table('file')->get($id);
    return $this->_getFile($resultDb);
  }   

  /* EXTERNAL METHOD */

  public function getById($id) 
  {
    return $this->_getById($id);
  }

}

