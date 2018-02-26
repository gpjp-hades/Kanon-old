<?php

namespace controller;

class update extends \model\controller {

    function __invoke($request, $response, $args) {

        if ($request->isGet()) {
            
        }

        $response = $this->sendResponse($request, $response, "index.phtml");
        return $response;
    }

}