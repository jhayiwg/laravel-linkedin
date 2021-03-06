<?php

/**
 * Linkedin API for Laravel Framework
 *
 * @author    Mauri de Souza Nunes <mauri870@gmail.com>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

use PHPUnit_Framework_TestCase as PhpUnit;

class LinkedinClassTest extends PhpUnit
{
    const APP_ID = '123456789';
    const APP_SECRET = '987654321';

    /**
     * @var \Artesaos\LinkedIn\LinkedInLaravel
     */
    private $linkedin;

    public function setUp()
    {
        $this->linkedin = new \Artesaos\LinkedIn\LinkedInLaravel(self::APP_ID, self::APP_SECRET);
    }

    /**
     * Test constructor method
     */
    public function testConstructor()
    {
        $this->assertEquals($this->linkedin->getAppId(), '123456789',
            'Expect the App ID to be set.');
        $this->assertEquals($this->linkedin->getAppSecret(), '987654321',
            'Expect the API secret to be set.');
    }
}