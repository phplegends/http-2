<?php

namespace PHPLegends\Http;


class FilesCollection extends ParameterCollection
{
    protected static $keys = [
        'error',
        'name',
        'size',
        'tmp_name',
        'type',
    ];

    public function add($item) 
    {  
        $this->assertType($item);

        return parent::add($item);
    }

    public function set($key, $value)
    {
        return parent::set($key, $value);
    }

    /**
     * Create From Array
     * 
     * @param array $uploadedFiles
     * @return FilesCollection
     * */
    public static function createFromArray(array $uploadedFiles)
    {
        $files = new static;

        foreach ($uploadedFiles as $key => $value) {

            if ($value instanceof UploadedFile) {

                $files[$key] = $value;

            } elseif (is_array($value) && UploadedFile::isValidKeys($value) && is_array($value['tmp_name'])) {

                $files[$key] = static::createFromArray(
                    static::resolveIrregularData($value)
                );
                
            } elseif (is_array($value) && UploadedFile::isValidKeys($value)) {

                $files[$key] = UploadedFile::createFromArray($value);
                
            } elseif (is_array($value)) {
                
                $files[$key]  = static::createFromArray($value);

            } else {

                throw new \InvalidArgumentException('Invalid uploaded files value');
            }
        }

        return $files;
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