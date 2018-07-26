<?php

namespace Absolute\Module\File\Manager;

use Nette\Database\Context;
use Absolute\Core\Manager\BaseManager;

class FileCRUDManager extends BaseManager
{

    public function __construct(\Nette\Database\Context $database)
    {
        parent::__construct($database);
    }

    // OTHER METHODS
    // CONNECT METHODS
    // CUD METHODS

    public function createFromSession($tmpPath, $fileName, $path, $type = "", $name = "", $isRndName = true)
    {
        if (!is_file(WWW_DIR . $tmpPath . $fileName))
        {
            return false;
        }
        if (!is_dir(WWW_DIR . $path))
        {
            mkdir(WWW_DIR . $path, 0777, true);
        }
        if ($isRndName)
        {
            $rndFileName = Nette\Utils\Strings::random(16, '0-9a-z');
        }
        else
        {
            $rndFileName = $fileName;
        }
        while (file_exists(WWW_DIR . $path . $rndFileName))
        {
            $hash = md5(time());
            if ($isRndName)
            {
                $rndFileName = substr($rndFileName . substr($hash, 0, 1), 0, 16);
            }
            else
            {
                $rndFileName = substr($hash, 0, 1) . $rndFileName;
            }
        }
        copy(WWW_DIR . $tmpPath . $fileName, WWW_DIR . $path . $rndFileName);
        unlink(WWW_DIR . $tmpPath . $fileName);
        return $this->database->table('file')->insert(
                        array(
                            'name' => $name,
                            'type' => $type,
                            'filename' => $fileName,
                            'path' => $path . $rndFileName,
                            'created' => new \DateTime(),
                        )
        );
    }

    public function createFromUpload($upload, $fileName, $path, $type = "", $name = "", $isRndName = true)
    {
        if (!$upload instanceof \Nette\Http\FileUpload && $upload->getName())
        {
            return false;
        }
        if (!is_dir(WWW_DIR . $path))
        {
            mkdir(WWW_DIR . $path, 0777, true);
        }
        if ($isRndName)
        {
            $rndFileName = \Nette\Utils\Random::generate(16, '0-9a-z');
        }
        else
        {
            $rndFileName = $fileName;
        }
        while (file_exists(WWW_DIR . $path . $rndFileName))
        {
            $hash = md5(time());
            if ($isRndName)
            {
                $rndFileName = substr($rndFileName . substr($hash, 0, 1), 0, 16);
            }
            else
            {
                $rndFileName = substr($hash, 0, 1) . $rndFileName;
            }
        }
        $upload->move(WWW_DIR . $path . $rndFileName);
        return $this->database->table('file')->insert(
                        array(
                            'name' => $name,
                            'type' => $type,
                            'filename' => $fileName,
                            'path' => $path . $rndFileName,
                            'created' => new \DateTime(),
                        )
        );
    }

    public function delete($id)
    {
        $db = $this->database->table('file')->get($id);
        if (!$db)
        {
            return false;
        }
        if (file_exists(WWW_DIR . $db->path))
        {
            unlink(WWW_DIR . $db->path);
        }
        $this->database->table('note')->where('file_id', $id)->update(array(
            'file_id' => null,
        ));
        $this->database->table('user')->where('file_id', $id)->update(array(
            'file_id' => null,
        ));
        $this->database->table('todo_file')->where('file_id', $id)->delete();
        return $this->database->table('file')->where('id', $id)->delete();
    }

}
