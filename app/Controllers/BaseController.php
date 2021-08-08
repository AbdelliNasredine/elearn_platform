<?php
namespace App\Controllers;

use Psr\Container\ContainerInterface;

class BaseController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __get($prop)
    {
        if ($this->container->{$prop}) {
            return $this->container->{$prop};
        }
    }

    public function view($response, $viewPath, $options = [])
    {
        return $this->view->render($response, $viewPath, $options);
    }
}
