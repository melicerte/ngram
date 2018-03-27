<?php

use Melicerte\LangDetector\LangDetector;

require_once '../vendor/autoload.php';

// Create corpus
$lang = new LangDetector();
$dir = "../";
$german = strip_tags(file_get_contents($dir . "german.data"));
$lang->addDocument($german, 'german');
$english = strip_tags(file_get_contents($dir . "english.data"));
$lang->addDocument($english, 'english');
$spanish = strip_tags(file_get_contents($dir . "spanish.data"));
$lang->addDocument($spanish, 'spanish');
$italian = strip_tags(file_get_contents($dir . "italian.data"));
$lang->addDocument($italian, 'italian');
$french = strip_tags(file_get_contents($dir . "french.data"));
$lang->addDocument($french, 'french', isset($_GET['display']));

$italian = '
Nel mezzo del cammin di nostra vita
 mi ritrovai per una selva oscura
 ché la diritta via era smarrita.
';

$spanish = '
Por qué los inmensos aviones
No se pasean com sus hijos?
Cuál es el pájaro amarillo
Que llena el nido de limones?
Por qué no enseñan a sacar
Miel del sol a los helicópteros?
';

$french = '
Bonjour je m\'appelle Etienne et j\'écris une phrase en français. Cet algorithme saura-t-il détecter la langue ? 
';

$english = 'This english text is written by a french guy, so maybe it\'s not the best way to test language detection';

?>

<div>
    <?php
    if (isset($_POST['text'])) {
        echo 'Texte : '.$_POST['text'].'<br />';
        echo 'Langue détectée : ' . $lang->detect($_POST['text']);
    }
    ?>
</div>

<form method="post">
    <textarea name="text"></textarea>
    <input type="submit" value="Detect language">
</form>



<table>
    <thead>
    <tr>
        <th>Texte</th>
        <th>Langue détectée</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            <?php echo $italian; ?>
        </td>
        <td>
            <?php echo $lang->detect($italian); ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo $spanish; ?>
        </td>
        <td>
            <?php echo $lang->detect($spanish); ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo $french; ?>
        </td>
        <td>
            <?php echo $lang->detect($french); ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo $english; ?>
        </td>
        <td>
            <?php echo $lang->detect($english); ?>
        </td>
    </tr>
    </tbody>
</table>