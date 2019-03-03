<?php


namespace Xapro\Laratools;


use Illuminate\Support\ServiceProvider;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Xapro\Laratools\Console\AddUser;

class LaratoolsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands(
            [AddUser::class]
        );
    }
}
