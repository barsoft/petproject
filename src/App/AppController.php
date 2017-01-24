<?php

namespace App;

use PetProject\GroupKTService;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class AppController
{
    private $app;
    private $viewsDir;
    private $groupKTService;

    public function __construct(Application $app, $viewsDir, GroupKTService $groupKTService)
    {
        $this->app = $app;
        $this->viewsDir = $viewsDir;
        $this->groupKTService = $groupKTService;
    }

    public function hello(Request $request)
    {
        $message = $request->get('message');
        return $this->renderHello($message);
    }


    private function renderHello($msg = null, $params = array())
    {
        return $this->renderTemplate(
            'index.php',
            array_merge(
                array(
                    'message' => $msg,
                    'groupKTService' => $this->groupKTService
                ),
                $params
            )
        );
    }

    private function renderTemplate($template, array $params)
    {
        extract($params);
        if ($this->viewsDir) {
            include("{$this->viewsDir}/{$template}");

        }
        return '';
    }
}
