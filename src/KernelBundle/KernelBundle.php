<?php

namespace KernelBundle;

use KernelBundle\DependencyInjection\RegisterBookingStateWorkflowCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Main bundle entry point
 * @author Guillaume MOREL <github.com/gmorel>
 */
class KernelBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterBookingStateWorkflowCompilerPass());
    }
}
