<?php

namespace Utils;

class Logger
{
    private $dir;
    private $currentTime;

    public function __construct($dir, $currentTime)
    {
        $this->dir = $dir;
        $this->currentTime = $currentTime;
    }

    public function httpCommunication(array $data)
    {
        $this->appendCsvFile('http.csv', $data);
    }

    public function appendCsvFile($file, array $data)
    {
        $dataWithDate = array_merge(
            array(
                date('c', $this->currentTime),
            ),
            $data
        );
        $csvLine = implode(',', $dataWithDate);
        file_put_contents("{$this->dir}/{$file}", "{$csvLine}\n", FILE_APPEND);
    }
}