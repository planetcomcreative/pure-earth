<?php

use Symfony\Component\HttpFoundation\Request;

/**
 * @var Composer\Autoload\ClassLoader
 */

/*
 * I'm lying if I say I fully understand what this accomplishes, but it stops wordpress and symfony from trying to handle the same requests
 * Stuff breaks if you don't do this.
 */
function run()
{
    $loader = require __DIR__ . '/../app/autoload.php';
    include_once __DIR__ . '/../var/bootstrap.php.cache';

    $kernel = new AppKernel('prod', false);
    $kernel->loadClassCache();
    //$kernel = new AppCache($kernel);

    // When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
    //Request::enableHttpMethodParameterOverride();
    $request = Request::createFromGlobals();
    $response = $kernel->handle($request);
    $response->send();
    $kernel->terminate($request, $response);
}

run();