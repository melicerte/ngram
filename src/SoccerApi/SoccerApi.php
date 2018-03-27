<?php
namespace Melicerte\SoccerApi;

use GuzzleHttp;

class SoccerApi
{
    private $baseUrl;
    private $apiKey;
    private $client;

    public function __construct(string $baseUrl, string $apiKey){
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
        $this->client = new GuzzleHttp\Client();
    }

    public function getCompetitions($season=null) {
        if (is_null($season)) {
            $season = date('Y');
        }

        $parameters = array_merge($this->getQueryDefaultParameters(), [
            'query' => ['season' => $season]
        ]);

        try {
            $res = $this->client->request('GET', $this->baseUrl . '/competitions/', $parameters);

            return json_decode($res->getBody()->getContents());
        } catch (RequestException $e) {
            echo GuzzleHttp\Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo GuzzleHttp\Psr7\str($e->getResponse());
            }
        } catch (\RuntimeException $e) {
            echo GuzzleHttp\Psr7\str($e->getRequest());
        }
    }

    public function getTeams($competitionId) {

        $parameters = array_merge($this->getQueryDefaultParameters(), []);

        try {
            $res = $this->client->request('GET', $this->baseUrl . '/competitions/'.$competitionId.'/teams', $parameters);

            return json_decode($res->getBody()->getContents());
        } catch (RequestException $e) {
            echo GuzzleHttp\Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo GuzzleHttp\Psr7\str($e->getResponse());
            }
        } catch (\RuntimeException $e) {
            echo GuzzleHttp\Psr7\str($e->getRequest());
        }
    }

    public function getTeamFixtures($teamId, $season) {

        $parameters = array_merge($this->getQueryDefaultParameters(), [
            'query' => [
                'season' => $season
            ]
        ]);

        try {
            $res = $this->client->request('GET', $this->baseUrl . '/teams/'.$teamId.'/fixtures/', $parameters);

            return json_decode($res->getBody()->getContents());
        } catch (RequestException $e) {
            echo GuzzleHttp\Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo GuzzleHttp\Psr7\str($e->getResponse());
            }
        } catch (\RuntimeException $e) {
            echo GuzzleHttp\Psr7\str($e->getRequest());
        }
    }

    /**
     * @return array
     */
    private function getQueryDefaultParameters(): array {
        return [
            'headers' => [
                'X-Auth-Token' => $this->apiKey
            ]
        ];
    }
}