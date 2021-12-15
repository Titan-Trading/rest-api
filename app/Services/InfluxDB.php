<?php
namespace App\Services;

use InfluxDB2\Client;
use InfluxDB2\Model\WritePrecision;
use InfluxDB2\Point;

class InfluxDB
{
    protected $client;
    protected $queryApi;
    protected $writeApi;

    public function __construct()
    {
        $this->client = new Client([
            "url" => "http://" . env('INFLUXDB_HOST') . ":" . env('INFLUXDB_PORT'),
            "token" => env('INFLUXDB_TOKEN'),
            "bucket" => env('INFLUXDB_BUCKET'),
            "org" => env('INFLUXDB_ORG')
        ]);

        $this->queryApi = $this->client->createQueryApi();
        $this->writeApi = $this->client->createWriteApi();
    }

    public function query($queryString)
    {
        return $this->queryApi->query($queryString);
    }

    public function write($data)
    {
        return $this->writeApi->write($data);
    }
}