<?php

namespace model;

abstract class controller extends container {
    use sendResponse;

    abstract function __invoke($request, $response, $args);
    
}