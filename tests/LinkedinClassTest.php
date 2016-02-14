<?php

/**
 * Linkedin API for Laravel Framework
 *
 * @author    Mauri de Souza Nunes <mauri870@gmail.com>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

namespace Artesaos\LinkedIn\Tests;

use Artesaos\LinkedIn\Facades\LinkedIn;
use Artesaos\LinkedIn\LinkedInLaravel;

class LinkedinClassTest extends TestCase
{
    const APP_ID = '123456789';
    const APP_SECRET = '987654321';

    /**
     * @var \Artesaos\LinkedIn\LinkedInLaravel
     */
    private $linkedin;

    public function setUp()
    {
        parent::setUp();
        $this->linkedin = new LinkedInLaravel(self::APP_ID, self::APP_SECRET);
    }
    public function testConstructor()
    {
        $this->assertEquals(get_parent_class($this->linkedin), \Happyr\LinkedIn\LinkedIn::class);
    }
    public function testCheckIfIsAuthenticated()
    {
        $this->assertEquals(false, LinkedIn::isAuthenticated());
    }
}