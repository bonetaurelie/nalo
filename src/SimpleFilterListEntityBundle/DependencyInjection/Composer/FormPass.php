<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 10/08/16
 * Time: 16:40
 */

namespace SimpleFilterListEntityBundle\DependencyInjection\Composer;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FormPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        $resources = $container->getParameter('twig.form.resources');
        foreach (array('div', 'jquery', 'stylesheet') as $template) {
            $resources[] = 'SCDatetimepickerBundle:Form:' . $template . '_layout.html.twig';
        }
        $container->setParameter('twig.form.resources', $resources);
    }
}