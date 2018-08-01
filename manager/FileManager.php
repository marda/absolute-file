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

    public function getFile($db)
    {
        $this->_getFile($db);
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

    private function _getTodoList($todoId)
    {
        $ret = array();
        $resultDb = $this->database->table('file')->where(':todo_file.todo_id', $todoId);
        foreach ($resultDb as $db)
        {
            $object = $this->_getFile($db);
            $ret[] = $object;
        }
        return $ret;
    }

    private function _getTodoItem($todoId, $fileId)
    {
        return $this->_getFile($this->database->table('file')->where(':todo_file.todo_id', $todoId)->where("file_id", $fileId)->fetch());
    }

    public function _fileTodoDelete($todoId, $fileId)
    {
        return $this->database->table('todo_file')->where('todo_id', $todoId)->where('file_id', $fileId)->delete();
    }

    public function _fileTodoCreate($todoId, $fileId)
    {
        return $this->database->table('todo_file')->insert(['todo_id' => $todoId, 'file_id' => $fileId]);
    }

    public function getTodoList($todoId)
    {
        return $this->_getTodoList($todoId);
    }

    public function getTodoItem($todoId, $fileId)
    {
        return $this->_getTodoItem($todoId, $fileId);
    }

    public function fileTodoDelete($todoId, $fileId)
    {
        return $this->_fileTodoDelete($todoId, $fileId);
    }

    public function fileTodoCreate($todoId, $fileId)
    {
        return $this->_fileTodoCreate($todoId, $fileId);
    }

}
