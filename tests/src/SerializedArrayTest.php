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
namespace SerializedArray\Test;

use PHPUnit\Framework\TestCase;
use Reflection\ReflectionTrait;
use SerializedArray\SerializedArray;

/**
 * SerializedArrayTest class
 */
class SerializedArrayTest extends TestCase
{
    use ReflectionTrait;

    /**
     * @var \MeTools\Utility\SerializedArray
     */
    protected $SerializedArray;

    /**
     * File for tests
     * @var string
     */
    protected $file;

    /**
     * Setup the test case, backup the static object values so they can be
     * restored. Specifically backs up the contents of Configure and paths in
     *  App if they have not already been backed up
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->file = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'serialized';
        $this->SerializedArray = new SerializedArray($this->file);
    }

    /**
     * Teardown any static object changes and restore them
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        unset($this->SerializedArray);

        //Deletes the file
        //@codingStandardsIgnoreLine
        @unlink($this->file);
    }

    /**
     * Test for `__construct()` method
     * @test
     */
    public function testConstruct()
    {
        $this->assertEquals($this->file, $this->getProperty($this->SerializedArray, 'file'));
    }

    /**
     * Test for `__construct()` method, using a not writable file
     * @expectedException Exception
     * @expectedExceptionMessage File or directory / not writeable
     * @test
     */
    public function testConstructNoWritableFile()
    {
        new SerializedArray('/noWritableFile');
    }

    /**
     * Test for `__construct()` method, with a file that already exists
     * @test
     */
    public function testConstructFileAlreadyExists()
    {
        file_put_contents($this->file, null);

        $SerializedArray = new SerializedArray($this->file);
        $this->assertEquals($this->file, $this->getProperty($SerializedArray, 'file'));
    }

    /**
     * Test for `append()` method
     * @test
     */
    public function testAppend()
    {
        $this->assertTrue($this->SerializedArray->append('first'));
        $this->assertEquals([
            'first',
        ], $this->SerializedArray->read());

        $this->assertTrue($this->SerializedArray->append('second'));
        $this->assertEquals([
            'first',
            'second',
        ], $this->SerializedArray->read());

        //@codingStandardsIgnoreLine
        @unlink($this->file);

        $this->assertTrue($this->SerializedArray->append(['first', 'second']));
        $this->assertEquals([
            ['first', 'second'],
        ], $this->SerializedArray->read());

        $this->assertTrue($this->SerializedArray->append(['third', 'fourth']));
        $this->assertEquals([
            ['first', 'second'],
            ['third', 'fourth'],
        ], $this->SerializedArray->read());

        $this->assertTrue($this->SerializedArray->append('string'));
        $this->assertEquals([
            ['first', 'second'],
            ['third', 'fourth'],
            'string',
        ], $this->SerializedArray->read());
    }

    /**
     * Test for `first()` method
     * @test
     */
    public function testPrepend()
    {
        $this->assertTrue($this->SerializedArray->prepend('first'));
        $this->assertEquals([
            'first',
        ], $this->SerializedArray->read());

        $this->assertTrue($this->SerializedArray->prepend('second'));
        $this->assertEquals([
            'second',
            'first',
        ], $this->SerializedArray->read());

        //@codingStandardsIgnoreLine
        @unlink($this->file);

        $this->assertTrue($this->SerializedArray->prepend(['first', 'second']));
        $this->assertEquals([
            ['first', 'second'],
        ], $this->SerializedArray->read());

        $this->assertTrue($this->SerializedArray->prepend(['third', 'fourth']));
        $this->assertEquals([
            ['third', 'fourth'],
            ['first', 'second'],
        ], $this->SerializedArray->read());

        $this->assertTrue($this->SerializedArray->prepend('string'));
        $this->assertEquals([
            'string',
            ['third', 'fourth'],
            ['first', 'second'],
        ], $this->SerializedArray->read());
    }

    /**
     * Test for `read()` method
     * @test
     */
    public function testRead()
    {
        //For now file doesn't exist
        $this->assertFileNotExists($this->file);
        $result = $this->SerializedArray->read();
        $this->assertEmpty($result);
        $this->assertTrue(is_array($result));

        //Tries some values
        foreach ([
            ['first', 'second'],
            [],
            [true],
            [false],
            [null],
        ] as $value) {
            $this->assertTrue($this->SerializedArray->write($value));
            $result = $this->SerializedArray->read();
            $this->assertEquals($value, $result);
            $this->assertTrue(is_array($result));
        }

        //Creates an empty file. Now is always empty
        file_put_contents($this->file, null);
        $result = $this->SerializedArray->read();
        $this->assertEmpty($result);
        $this->assertTrue(is_array($result));

        //Creates a file with a string
        file_put_contents($this->file, 'string');
        $result = $this->SerializedArray->read();
        $this->assertEmpty($result);
        $this->assertTrue(is_array($result));
    }

    /**
     * Test for `write()` method
     * @test
     */
    public function testWrite()
    {
        //Tries some values
        foreach ([
            ['first', 'second'],
            [],
            [true],
            [false],
            [null],
        ] as $value) {
            $this->assertTrue($this->SerializedArray->write($value));
            $result = unserialize(file_get_contents($this->file));
            $this->assertEquals($value, $result);
            $this->assertTrue(is_array($result));
        }
    }
}
