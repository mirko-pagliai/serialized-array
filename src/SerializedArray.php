<?php
/**
 * This file is part of SerializedArray.
 *
 * SerializedArray is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * SerializedArray is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with SerializedArray.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author      Mirko Pagliai <mirko.pagliai@gmail.com>
 * @copyright   Copyright (c) 2016, Mirko Pagliai for Nova Atlantis Ltd
 * @license     http://www.gnu.org/licenses/agpl.txt AGPL License
 * @link        http://git.novatlantis.it Nova Atlantis Ltd
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
        //@codingStandardsIgnoreLine
        $data = @unserialize($data);

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
