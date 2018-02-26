<?php

namespace model;

abstract class container {
    
    protected $container;
    protected $db;

    function __construct(\Slim\Container $container) {
        $this->container = $container;
        $this->db = $this->container->db;
        $this->logger = $this->container->logger;
    }
}