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
require_once 'vendor/autoload.php';

if (class_exists('PHPUnit_Runner_Version')) {
    class_alias('PHPUnit_Framework_TestResult', 'PHPUnit\Framework\TestResult');
    class_alias('PHPUnit_Framework_Error', 'PHPUnit\Framework\Error\Error');
    class_alias('PHPUnit_Framework_ExpectationFailedException', 'PHPUnit\Framework\ExpectationFailedException');
}
