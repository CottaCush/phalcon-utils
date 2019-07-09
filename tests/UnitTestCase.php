<?php

use Phalcon\Config;
use Phalcon\DiInterface;
use Phalcon\Test\UnitTestCase as PhalconTestCase;
use PHPUnit\Framework\IncompleteTestError;

abstract class UnitTestCase extends PhalconTestCase
{
    /**
     * @var \Phalcon\Config
     */
    protected $_config;

    /**
     * @var bool
     */
    private $_loaded = false;

    public function setUp(DiInterface $di = null, Config $config = null)
    {
        parent::setUp();

        global $di;
        $this->setDI($di);
        \Phalcon\Di::setDefault($this->getDI());
        $this->_loaded = true;
    }

    /**
     * Check if the test case is setup properly
     *
     * @throws IncompleteTestError;
     */
    public function __destruct()
    {
        if (!$this->_loaded) {
            throw new IncompleteTestError('Please run parent::setUp().');
        }
    }

    /**
     * Override the parent's tearDown function to avoid it from resetting the DI
     * if a Test Class does not provide its own tearDown method
     */
    public function tearDown()
    {
    }
}