<?php

namespace PHPLegends\Http;

use Psr\Http\Message\UploadedFileInterface;

/**
 * This class represents an uploaded http file
 * 
 * @author Wallace de Souza Vizerra <wallacemaxters@gmail.com>
 * 
 * */
class UploadedFile
{

    /**
    * @var string
    */
    protected $file;

    /**
    * @var int
    */
    protected $size = 0;

    /**
     * @var string
     * */
    protected $clientFilename;

    /**
     * @var int
     * */
    protected $error;

    /**
     * 
     * @var string
     **/
    protected $clientMediaType;

    /**
     * 
     * @var boolean
     * */

    protected $moved = false;

    /**
     * List of Message errors
     * @var array
     * */
    protected $errorMessages = [
        //UPLOAD_ERR_OK       => 'There is no error, the file uploaded with success',
        UPLOAD_ERR_INI_SIZE   => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        UPLOAD_ERR_FORM_SIZE  => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        UPLOAD_ERR_PARTIAL    => 'The uploaded file was only partially uploaded',
        UPLOAD_ERR_NO_FILE    => 'No file was uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
        UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the file upload.',
    ];


    /**
     * @param string $file
     * @param string $clienteFilename
     * @param int $size
     * @param $error
     * @param string $type
     * 
     * */
    public function __construct($file, $clientFilename, $size, $error, $type)
    {
        $this->file = $file;

        $this->size = $size;

        $this->error = $error;

        $this->clientFilename = $clientFilename;

        $this->clientMediaType = $type;
    }

    /**
     * Gets the value of file.
     *
     * @return string
     */
    public function getFile()
    {
        return $this->assertNotMoved()->file;
    }

    /**
     * Gets the value of size.
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Gets the value of clientFilename.
     *
     * @return string
     */
    public function getClientFilename()
    {
        return $this->clientFilename;
    }

    /**
     * Gets the value of error.
     *
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Gets the value of clientMediaType.
     *
     * @return mixed
     */
    public function getClientMediaType()
    {
        return $this->clientMediaType;
    }

    /**
     * 
     * 
     * @return \SplFileObject
     * */
    public function getFileObject()
    {
        return new \SplFileObject($this->assertNotMoved()->getFile(), 'r');
    }


    public function moveTo($targetPath)
    {
        $this->assertNotMoved()
             ->assertOk()
             ->assertNotEmptyString($targetPath);

        $isUploaded = move_uploaded_file($this->getFile(), $targetPath);

        if (! $isUploaded) {

            throw new \RuntimeException('Cannot move uploaded file');
        }

        $this->moved = true;
    }

    /**
     * Move file from directory, keeping the cliente file name
     * 
     * @param string $directory
     * */
    public function moveToDirectory($directory)
    {
        $targetPath = rtrim($directory, '/') . '/' . $this->getClientFilename();

        return $this->moveTo($targetPath);
    }

    /**
     * Checks if upload has not errors
     * 
     * @return boolean
     * */
    public function isValid()
    {
        return $this->error === UPLOAD_ERR_OK;
    }

    /**
     * Asserts that file is not moved
     * 
     * @throws \RuntTimeException 
     * @return self
     * */

    public function assertNotMoved()
    {
        if ($this->moved) {
            throw new \RunTimeException('The current uploaded file already been moved');
        }

        return $this;
    }

    /**
     * Assets that uploaded file has not error
     * 
     * @throws \RuntTimeException
     * @return self
     * */

    public function assertOk()
    {
        if (! $this->isValid()) {
            throw new \RunTimeException($this->getErrorMessage());
        }

        return $this;
    }

    /**
     * Asserts that argument is not empty string
     * 
     * @throws \InvalidArgumentException
     * @param string $string
     * */
    protected function assertNotEmptyString($string)
    {
        if (empty($string)) {
            throw new \InvalidArgumentException('String must not be empty');
        }

        return $this;
    }

    /**
     * Gets the value of errorMessages.
     *
     * @return mixed
     */
    public function getErrorMessage()
    {
        if (array_key_exists($this->error, $this->errorMessages)) {

            return $this->errorMessages[$this->error];
        }

        return 'Unknow upload error';
    }
}