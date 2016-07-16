<?php

namespace PHPLegends\Http;

class DownloadResponse extends Response
{
    /**
     * 
     * @var string
     * */
    protected $filename;

    /**
     * 
     * @var string
     * */
    protected $downloadName;

    /**
     * 
     * @param string $filename
     * @param string|null $downloadName
     * @param int $code
     * @param array|null|ResponseHeadersCollection $headers
     * */
    public function __construct($filename, $downloadName = null, $code = 200, $headers = [])
    {
        if (! is_file($filename)) {

            throw new \UnexpectedValueException("The file '$filename' doesn't not exists.");
        }

        $this->filename = $filename;

        $downloadName === null && $downloadName = basename($filename);

        $this->downloadName = $downloadName;

        parent::__construct(null, $code, $headers);

    }

    /**
     * 
     * @param null $content
     * @throws \BadMethodCallException on $content diferent of null
     * */
    public function setContent($content)
    {
        if ($content !== null) {

            throw new \BadMethodCallException('Content definition is disable in DownloadResponse object');
        }
    }

    /**
     * Returns the content of file
     * 
     * @return string
     * */
    public function getContent()
    {
        return file_get_contents($this->filename);
    }

    /**
     * 
     * Gets tne filename
     * */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Prepare header for response
     * 
     * */
    protected function prepareHeaders()
    {
        $headers = $this->getHeaders();

        $headers->setItems([
            'Content-Description' => 'File Transfer',
            'Content-Type'        => 'application/octet-stream',
            'Content-Type'        => filesize($this->filename),
        ]);

        $headers->setContentDisposition($this->downloadName);

        return $this;

    }

    /**
     * send download :D
     * 
     * @param boolean $force
     * @return void
     * */
    public function send($force = false)
    {
        $this->prepareHeaders();

        $this->sendHeaders($force);

        $this->sendCookies($force);

        readfile($this->filename);
    }

}