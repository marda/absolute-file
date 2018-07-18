<?php

namespace Absolute\Module\File\Entity;

use Absolute\Core\Entity\BaseEntity;
use Absolute\Core\Helper\ImageHelper;
use Absolute\Core\Helper\StringHelper;

class File extends BaseEntity 
{

  private $id;
  private $name;
  private $filename;
  private $type;
  private $path;
  private $created;

	public function __construct($id, $path, $filename, $name, $type, $created) 
  {
    $this->id = $id;
		$this->name = $name;
    $this->filename = $filename;
    $this->path = $path;
    $this->created = $created;
    $this->type = $type;
	}

  public function getId() 
  {
    return $this->id;
  }

  public function getName() 
  {
    return $this->name;
  }

  public function getType() 
  {
    return $this->type;
  }

  public function getPath() 
  {
    return $this->path;
  }

  public function getFilename() 
  {
    return $this->filename;
  }

  public function getCreated() 
  {
    return $this->created;
  }

  public function getUrl() 
  {
    return $this->path; // TO FIX
  }

  public function getMimeType() 
  {
    return mime_content_type(WWW_DIR . $this->path);
  }

  public function getFileSize() 
  {
    return @filesize(WWW_DIR . $this->path);
  }

  public function getImageBlob() 
  {
    $mimeType = $this->getMimeType();
    if ($mimeType == "application/pdf")
    {
      return ImageHelper::pdfToImageBlob(WWW_DIR . $this->path);
    }
    if ($mimeType == "image/gif" || $mimeType == "image/png" || $mimeType == "image/jpeg")
    {
      return ImageHelper::imageToImageBlob(WWW_DIR . $this->path);
    }
    return false;
  }

  public function isImage()
  {
    return in_array($this->getMimeType(), array('image/gif', 'image/png', 'image/jpeg'), TRUE);
  }

  public function isAudio()
  {
    return in_array($this->getMimeType(), array('audio/basic', 'auido/L24', 'audio/mid', 'audio/mpeg', 'audio/mp4', 'audio/x-aiff', 'audio/x-mpegurl', 'audio/vnd.rn-realaudio', 'audio/ogg', 'audio/vorbis', 'audio/vnd.wav'), TRUE);
  }

  public function isVideo()
  {
    return in_array($this->getMimeType(), array('video/x-flv', 'video/mp4', 'video/MP2T', 'video/3gpp', 'video/quicktime', 'video/x-msvideo', 'video/x-ms-wmv'), TRUE);
  }

  // SETTERS

  // ADDERS

  // OTHER METHODS  

  public function toJson()
  {
    return array(
      "id" => $this->id,
      "name" => $this->name,
      "filename" => $this->filename,
      "type" => $this->type,
      "path" => $this->path,
      "filesize" => StringHelper::fileSize($this->getFileSize()),
      "created" => $this->created,
    );
  }
}

