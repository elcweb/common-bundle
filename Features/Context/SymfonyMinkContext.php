<?php

namespace Elcweb\CommonBundle\Features\Context;

use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\MinkExtension\Context\MinkContext;
use Behat\CommonContexts\MinkRedirectContext;
use Behat\CommonContexts\SymfonyMailerContext;
use Behat\CommonContexts\SymfonyDoctrineContext;
use Behat\Mink\Exception\UnsupportedDriverActionException,
    Behat\Mink\Exception\ExpectationException;
use Behat\MinkBundle\Driver\SymfonyDriver;

//
// Require 3rd-party libraries here:
//
   require_once 'PHPUnit/Autoload.php';
   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Feature context.
 */
abstract class SymfonyMinkContext extends MinkContext implements KernelAwareInterface
{
    protected $kernel;
    protected $parameters;

    /**
     * Initializes context with parameters from behat.yml.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;

        $this->useContext('mink_redirect', new MinkRedirectContext());
        $this->useContext('symfony_extra', new SymfonyMailerContext());
        $this->useContext('symfony_doctrine_context',  new SymfonyDoctrineContext);
    }

    /**
     * Clean database before scenario starts
     *
     * @BeforeScenario
     */
    public function beforeScenario($event)
    {
        // Asks subcontext SymfonyDoctrineContext to rebuild database schema
        $this
            ->getMainContext()
            ->getSubcontext('symfony_doctrine_context')
            ->buildSchema($event);
    }

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


    /**
     * @return Behat\Mink\Element\DocumentElement
     */
    protected function getPage()
    {
        return $this->getSession()->getPage();
    }

    /**
     * Pauses the scenario until the user presses a key. Useful when debugging a scenario.
     *
     * @Then /^break$/
     */
    public function iPutABreakpoint()
    {
        fwrite(STDOUT, "\033[s    \033[93m[Breakpoint] Press \033[1;93m[RETURN]\033[0;93m to continue...\033[0m");
        while (fgets(STDIN, 1024) == '') {}
        fwrite(STDOUT, "\033[u");

        return;
    }

    public function getSymfonyProfile()
    {
        $driver = $this->getSession()->getDriver();
        if (!$driver instanceof SymfonyDriver) {
            throw new UnsupportedDriverActionException(
                'You need to tag the scenario with '.
                '"@mink:symfony". Using the profiler is not '.
                'supported by %s', $driver
            );
        }

        $profile = $driver->getClient()->getProfile();
        if (false === $profile) {
            throw new \RuntimeException(
                'Emails cannot be tested as the profiler is '.
                'disabled.'
            );
        }

        return $profile;
    }
}
