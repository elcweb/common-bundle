<?php

namespace Elcweb\CommonBundle\Features\Context;

use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareInterface;

use Behat\Behat\Context\BehatContext;

/**
 * Behat Kernel Aware context.
 */
abstract class BehatKernelAwareContext extends BehatContext implements KernelAwareInterface
{
    protected $kernel;

    /**
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
}
