<?php

declare(strict_types=1);

/*
 * This file is part of kodekeep/laravel-subscribers.
 *
 * (c) KodeKeep <hello@kodekeep.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KodeKeep\Subscribers\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\WithFaker;
use KodeKeep\Subscribers\Providers\SubscribersServiceProvider;
use KodeKeep\Subscribers\Tests\Unit\ClassThatHasSubscribers;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations(['--database' => 'testbench']);

        $this->loadMigrationsFrom(realpath(__DIR__.'/../database/migrations/'));

        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function getPackageProviders($app): array
    {
        return [SubscribersServiceProvider::class];
    }

    protected function user(): Model
    {
        return ClassThatHasSubscribers::create([
            'name'     => $this->faker->name,
            'email'    => $this->faker->safeEmail,
            'password' => $this->faker->password,
        ]);
    }
}
