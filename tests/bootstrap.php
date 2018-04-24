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
require_once 'vendor/autoload.php';

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

if (class_exists('PHPUnit_Runner_Version')) {
    class_alias('PHPUnit_Framework_TestResult', 'PHPUnit\Framework\TestResult');
    class_alias('PHPUnit_Framework_Error', 'PHPUnit\Framework\Error\Error');
    class_alias('PHPUnit_Framework_ExpectationFailedException', 'PHPUnit\Framework\ExpectationFailedException');
}
