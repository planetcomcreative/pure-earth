<?php

namespace NS\PurearthBundle;

use NS\PurearthBundle\DependencyInjection\Compiler\TwigFormThemeCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class NSPurearthBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new TwigFormThemeCompilerPass());
    }
}
