<?php

namespace App;

use PetProject\GroupKTService;
use PetProject\Message;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class AppController
{
    private $app;
    private $viewsDir;
    private $groupKTService;
    private $config;

    public function __construct(Application $app, $viewsDir, GroupKTService $groupKTService, $config)
    {
        $this->app = $app;
        $this->viewsDir = $viewsDir;
        $this->groupKTService = $groupKTService;
        $this->config = $config;
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

    public function helloSubmit($request)
    {
        $params = array(
            'name' => $request->request->get('name'),
            'email' => $request->request->get('email'),
            'country' => $request->request->get('country')
        );

        if (empty($params['name']) || empty($params['email']) || empty($params['country'])) {
            return $this->renderHello(Message::ALL_FIELDS_SHOULD_BE_FILLED, $params);
        }

        if ($this->login($params['name'], $params['email'], $params['country'])) {
            return $this->app->redirect($this->app["url_generator"]->generate("hello",
                array('message' => Message::LOGIN_SUCCEEDED)));
        }

        return $this->renderHello(Message::WRONG_CREDENTIALS, $params);
    }

    private function login($name, $email, $country)
    {
        if ($name == $this->config['name'] && $email == $this->config['email'] && $country == $this->config['country']) {
            return true;
        }
        return false;
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
