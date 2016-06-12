<?php

namespace PHPLegends\Http;

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
    protected $filename;

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
    protected static $errorMessages = [
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
    public function __construct($filename, $clientFilename, $size, $error, $type)
    {
        $this->filename = $filename;

        $this->size = (int) $size;

        $this->error = $error;

        $this->clientFilename = $clientFilename;

        $this->clientMediaType = $type;
    }

    /**
     * Gets the value of file.
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->assertNotMoved()->filename;
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
        return new \SplFileObject($this->assertNotMoved()->getFilename(), 'r');
    }


    public function moveTo($targetPath)
    {
        $this->assertNotMoved()
             ->assertOk()
             ->assertNotEmptyString($targetPath);

        $isUploaded = move_uploaded_file($this->getFilename(), $targetPath);

        if (! $isUploaded) {

            throw new \RuntimeException('Cannot move uploaded file');
        }

        $this->moved = true;
    }

    /**
     * Move file from directory, keeping the client file name
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

    protected function assertNotMoved()
    {
        if ($this->isMoved()) {
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

    protected function assertOk()
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
        if (array_key_exists($this->error, static::$errorMessages)) {

            return static::$errorMessages[$this->error];
        }

        return 'Unknow upload error';
    }

    /**
     * 
     * @return boolean
     * */
    public function isMoved()
    {
        return $this->moved;
    }

    /**
     * Create a UploadedFile from PHP $_FILES array
     * 
     * @param array $value
     * @return self
     * */
    public static function createFromArray(array $value)
    {
        if (! static::isValidKeys($value))
        {
            throw new \UnexpectedValueException('Invalid upload data');
        }

        return new static(
            $value['tmp_name'], 
            $value['name'], 
            $value['size'],
            $value['error'],
            $value['type']
        );
    }

    /**
     * Check if PHP upload keys is valid
     * @param array $file
     * @return boolean
     * */
    public static function isValidKeys(array $file)
    {
        return isset($file['error'], $file['name'], $file['size'], $file['tmp_name'], $file['type']);
    }
}