<?php

use Melicerte\Ngram\Ngram;
use Melicerte\SoccerApi;

require_once '../vendor/autoload.php';

$ngram = new Ngram();

$repo = new SoccerApi\SoccerRepository();

$teams = [];
$season = '';

if (isset($_POST['action']) && $_POST['action'] === 'search') {
    $season = $_POST['season'];
    $teams = $repo->findTeamByName($_POST['team'], $season);
}

?>
<html>
<head></head>
<body>
    <form method="post">
        <input type="hidden" name="action" value="search" />
        <div class="form-item">
            <label>Team</label>
            <input type="text" name="team" />
        </div>
        <div class="form-item">
            <label>Saison</label>
            <input type="text" name="season" />
        </div>
        <input type="submit" value="Search">
    </form>
    <table>
        <thead>
            <tr>
                <th colspan="2">Equipe</th>
                <th>Résultats</th>
                <th>Probas 1-gram</th>
                <th>Probas 2-gram</th>
                <th>Probas 3-gram</th>
                <th>Probas 4-gram</th>
            </tr>
        </thead>
        <?php
        foreach ($teams as $team) {
        ?>
            <tr>
                <td><img src="<?php echo $team->crestUrl; ?>" height="100" /></td>
                <td><?php echo $team->name.' ('.$team->competitionName.') : '.$team->id; ?></td>
                <td>
                    <?php
                    $resultats = $repo->getSequenceFromFixtures($team->name, $team->id, $season);
                    echo $resultats;
                    ?>
                </td>
                <td>
                    <?php
                    $oneGramProbas = $ngram->getProbabilitiesForResult($resultats, '', 1);
                    ?>
                    Victoire : <?php echo number_format($oneGramProbas['V'], 2); ?>%<br />
                    Nul : <?php echo number_format($oneGramProbas['N'], 2); ?>%<br />
                    Défaite : <?php echo number_format($oneGramProbas['D'], 2); ?>%<br />
                </td>
                <td>
                    <?php
                    $twoGramProbas = $ngram->getProbabilitiesForResult($resultats, substr($resultats, -1, 1), 2);
                    ?>
                    Victoire : <?php echo number_format($twoGramProbas['V'], 2); ?>%<br />
                    Nul : <?php echo number_format($twoGramProbas['N'], 2); ?>%<br />
                    Défaite : <?php echo number_format($twoGramProbas['D'], 2); ?>%<br />
                </td>
                <td>
                    <?php
                    $threeGramProbas = $ngram->getProbabilitiesForResult($resultats, substr($resultats, -2, 2), 3);
                    ?>
                    Victoire : <?php echo number_format($threeGramProbas['V'], 2); ?>%<br />
                    Nul : <?php echo number_format($threeGramProbas['N'], 2); ?>%<br />
                    Défaite : <?php echo number_format($threeGramProbas['D'], 2); ?>%<br />
                </td>
                <td>
                    <?php
                    $fourGramProbas = $ngram->getProbabilitiesForResult($resultats, substr($resultats, -3, 3), 4);
                    ?>
                    Victoire : <?php echo number_format($fourGramProbas['V'], 2); ?>%<br />
                    Nul : <?php echo number_format($fourGramProbas['N'], 2); ?>%<br />
                    Défaite : <?php echo number_format($fourGramProbas['D'], 2); ?>%<br />
                </td>
            </tr>
        <?php
        }
        ?>
    </table>
    <?php
    ?>
</body>
</html>
