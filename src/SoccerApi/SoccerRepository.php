<?php

namespace Melicerte\SoccerApi;

use Symfony\Component\Dotenv\Dotenv;

class SoccerRepository
{
    private $api;
    private $fixtures;

    public function __construct()
    {
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/../../.env');
        $this->api = new SoccerApi($_ENV['API_URL'], $_ENV['API_KEY']);
    }

    public function findTeamByName($name, $season): array
    {
        $teamsFound = [];
        $competitions = $this->api->getCompetitions($season);

        foreach ($competitions as $competition) {
            $teams = $this->api->getTeams($competition->id);
            foreach ($teams->teams as $team) {
                if (preg_match('/' . strtolower($name) . '/Usi', strtolower($team->name))) {
                    $team->competitionName = $competition->caption;
                    $team->id = $this->getTeamId($team);
                    $teamsFound[] = $team;
                }
            }
        }

        return $teamsFound;
    }

    public function getSequenceFromFixtures($teamName, $teamId, $season) {
        $sequence = '';
        $fixtures = $this->loadFixtures($teamId, $season);

        foreach ($fixtures->fixtures as $fixture) {

            if ($fixture->status === 'FINISHED') {
                if ($fixture->homeTeamName === $teamName) {

                    if ($fixture->result->goalsHomeTeam > $fixture->result->goalsAwayTeam) {
                        $sequence .= 'V';
                    }

                    if ($fixture->result->goalsHomeTeam === $fixture->result->goalsAwayTeam) {
                        $sequence .= 'N';
                    }

                    if ($fixture->result->goalsHomeTeam < $fixture->result->goalsAwayTeam) {
                        $sequence .= 'D';
                    }
                } else {
                    if ($fixture->result->goalsHomeTeam > $fixture->result->goalsAwayTeam) {
                        $sequence .= 'D';
                    }

                    if ($fixture->result->goalsHomeTeam === $fixture->result->goalsAwayTeam) {
                        $sequence .= 'N';
                    }

                    if ($fixture->result->goalsHomeTeam < $fixture->result->goalsAwayTeam) {
                        $sequence .= 'V';
                    }
                }
            }
        }

        return $sequence;
    }

    public function loadFixtures($teamId, $season)
    {
        if (!isset($this->fixtures[$teamId])) {
            $this->fixtures[$teamId] = $this->api->getTeamFixtures($teamId, $season);
        }

        return $this->fixtures[$teamId];
    }

    public function getTeamId($team) {
        return preg_replace('/^.*\/(\d+)$/Usi', '$1', $team->_links->self->href);
    }
}