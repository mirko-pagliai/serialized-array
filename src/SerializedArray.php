<?php
/**
 * This file is part of SerializedArray
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) Mirko Pagliai
 * @link        https://github.com/mirko-pagliai/serialized-array
 * @license     https://opensource.org/licenses/mit-license.php MIT License
 */
namespace SerializedArray;

use Exception;

/**
 * This class allows you to read and write arrays using text files
 */
class SerializedArray
{
    /**
     * File path
     * @var string
     */
    protected $file;

    /**
     * Construct
     * @param string $file Text file you want to read/write
     * @return void
     * @throws Exception
     * @uses $file
     */
    public function __construct($file)
    {
        if (file_exists($file)) {
            $target = $file;
        } else {
            $target = dirname($file);
        }

        if (!is_writable($target)) {
            throw new Exception(sprintf('File or directory %s not writeable', $target), E_USER_ERROR);
        }

        $this->file = $file;
    }

    /**
     * Appends data to existing data
     * @param mixed $data Data you want to append
     * @return bool
     * @uses read()
     * @uses write()
     */
    public function append($data)
    {
        $existing = $this->read();
        $existing[] = $data;

        return $this->write($existing);
    }

    /**
     * Prepends data to existing data
     * @param mixed $data Data you want to prepend
     * @return bool
     * @uses read()
     * @uses write()
     */
    public function prepend($data)
    {
        $existing = $this->read();
        array_unshift($existing, $data);

        return $this->write($existing);
    }

    /**
     * Reads the content of the file.
     *
     * If there are no data or if the file does not exist, still returns an
     *  empty array.
     * @return array
     * @uses $file
     */
    public function read()
    {
        if (!is_readable($this->file)) {
            return [];
        }

        $data = file_get_contents($this->file);

        if (empty($data)) {
            return [];
        }

        //Tries to unserialize
        $data = safe_unserialize($data);

        if (!$data) {
            return [];
        }

        return $data;
    }

    /**
     * Writes data.
     *
     * Note that this method will write the array to the entire files, deleting
     *  any data already present. If you want to add data to existing data, use
     *  instead `append()` or `prepend()` methods
     * @param array $data Data you want to write
     * @return bool
     * @uses $file
     */
    public function write(array $data)
    {
        return (bool)file_put_contents($this->file, serialize($data));
    }
}
