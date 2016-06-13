<?php

namespace PHPLegends\Http;

/**
 * Represents "all" uploaded files in request
 * 
 * @author Wallace de Souza Vizerra <wallacemaxters@gmail.com>
 * 
 * */
class FilesCollection extends ParameterCollection
{
    /**
     * 
     * @var array
     * */
    protected static $keys = [
        'error',
        'name',
        'size',
        'tmp_name',
        'type',
    ];

    /**
     * 
     * @param UploadedFile $uploadedFile
     * 
     * */
    public function add($uploadedFile) 
    {  
        $this->assertType($uploadedFile);

        return parent::add($uploadedFile);
    }

    /**
     * Create From Array
     * 
     * @param array $uploadedFiles
     * @return FilesCollection
     * */
    protected static function normalizePHPArray(array $uploadedFiles)
    {
        $files = [];

        foreach ($uploadedFiles as $key => $value) {

            if ($value instanceof UploadedFile) {

                $files[$key] = $value;

            } elseif (is_array($value) && UploadedFile::isValidKeys($value) && is_array($value['tmp_name'])) {

                $files[$key] = static::normalizePHPArray(
                    static::resolveIrregularData($value)
                );
                
            } elseif (is_array($value) && UploadedFile::isValidKeys($value)) {

                $files[$key] = UploadedFile::createFromArray($value);
                
            } elseif (is_array($value)) {
                
                $files[$key]  = static::normalizePHPArray($value);

            } else {

                throw new \InvalidArgumentException('Invalid uploaded files value');
            }
        }

        return $files;
    }

    public static function createFromArray(array $uploadedFiles)
    {
        return new static(static::normalizePHPArray($uploadedFiles));
    }

    /**
     * Resolve Irregular Data for file upload
     * 
     * @param array $values
     * @return array
     * */
    protected static function resolveIrregularData(array $values)
    {
        $fixed = [];

        foreach (array_keys($values['tmp_name']) as $key) {

            foreach (static::$keys as $k) {

                $fixed[$key][$k] = $values[$k][$key];
                
            }
        }

        return $fixed;
    }

    /**
     * 
     * @param mixed $item
     * @return void
     * @throws \InvalidArgumentException
     * */
    protected function assertType($item)
    {
        if (! $item instanceof UploadedFile) {        

            throw new \InvalidArgumentException(
                'The argument must be instance of UploadedFile'
            );
        }
    }

    public function getIterator()
    {
        return new \RecursiveArrayIterator($this->all());
    }

}