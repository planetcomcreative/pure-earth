<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 13/03/19
 * Time: 12:00 PM
 */

namespace NS\PurearthBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigFormThemeCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasParameter('twig.form.resources')) {
            $resources = $container->getParameter('twig.form.resources');
            $tplidx = array_search('NSColorAdminBundle:Form:fields.html.twig', $resources);
            if ($tplidx !== false) {
                unset($resources[$tplidx]);
            }

            array_unshift($resources, 'NSColorAdminBundle:Form:fields.html.twig'); //This needs to be first in the heirarchy
            $container->setParameter('twig.form.resources', $resources);
        }
    }
}
