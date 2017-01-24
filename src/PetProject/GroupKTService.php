<?php

namespace PetProject;

use Utils\LoggedClient;

class GroupKTService
{
    private $loggedClient;

    public function __construct(LoggedClient $loggedClient)
    {
        $this->loggedClient = $loggedClient;
        $this->urls = array(
            'countries' => function () {
                return 'http://services.groupkt.com/country/search?text';
            }
        );
    }

    public function getCountries()
    {
        $result = $this->getJson("countries")->RestResponse->result;
        return $result;
    }

    private function getJson($url, array $args = array())
    {
        $url = call_user_func_array($this->urls[$url], $args);
        return $this->loggedClient->getJson($url);
    }
}

