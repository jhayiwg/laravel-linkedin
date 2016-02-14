<?php
/**
 * @author    Mauri de Souza Nunes <mauri870@gmail.com>
 * @copyright Copyright (c) 2015, Mauri de Souza Nunes <github.com/mauri870>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

namespace Artesaos\LinkedIn\Tests;

use Artesaos\LinkedIn\Facades\LinkedIn;
use Artesaos\LinkedIn\LinkedinServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [LinkedinServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'LinkedIn' => LinkedIn::class
        ];
    }
}