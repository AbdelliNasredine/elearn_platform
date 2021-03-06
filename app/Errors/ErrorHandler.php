<?php


namespace App\Errors;


class ErrorHandler
{
    protected $container;
    protected $view;

    public function __construct($container)
    {
        $this->container = $container;
        $this->view = $this->container->view;
    }

    public function render($response, $view , $status) {
        return $this->view->render($response->withStatus($status), $view);
    }
}