<?php

namespace Tests\Chromabits\Support;

use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Class LaravelTestCase
 *
 * Setups a micro Laravel application for running tests on
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Tests\Support
 */
class LaravelTestCase extends TestCase
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * Setup testing environment
     */
    protected function setUp()
    {
        parent::setUp();

        $this->createApplication();
    }

    /**
     * Create an barebones Laravel application
     */
    protected function createApplication()
    {
        $this->app = new Application(__DIR__ . '/../../..');

        $this->app->instance('config', new Repository([]));

        $this->app['config']->set('app', [
            'providers' => [
                'Illuminate\Redis\RedisServiceProvider',
                'Illuminate\Filesystem\FilesystemServiceProvider',
                'Illuminate\Foundation\Providers\ConsoleSupportServiceProvider',
            ]
        ]);

        $this->app->registerConfiguredProviders();

        $this->app->boot();
    }

    /**
     * Tear down test case
     */
    protected function tearDown()
    {
        $this->app->flush();
    }
}
