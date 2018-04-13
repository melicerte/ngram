<?php

namespace Melicerte\WordPredictor;

/**
 * Class WordPredictor
 */
class WordPredictor
{
    private $index = array();

    public function addLine($line)
    {
        $words = $this->getWords($line);
        $trigrams = $this->getNgrams($words);

        foreach ($trigrams as $trigram) {
            if (!isset($this->index[$trigram])) {
                $this->index[$trigram] = 0;
            }
            $this->index[$trigram]++;
        }
    }

    public function addDocument($document, $display=false)
    {
        $lines = explode("\n", $document);

        foreach($lines as $line) {
            $this->addLine($line);
        }

        if ($display) {
            var_dump($this->index);
            echo '</pre>';
        }
    }

    public function detect($document)
    {
        $words = $this->getWords($document);
        $trigrams = array();
        foreach ($words as $word) {
            foreach ($this->getNgrams($word) as $trigram) {
                if (!isset($trigrams[$trigram])) {
                    $trigrams[$trigram] = 0;
                }
                $trigrams[$trigram]++;
            }
        }
        $total = array_sum($trigrams);

        $scores = array();
        foreach ($trigrams as $trigram => $count) {
            if (!isset($this->index[$trigram])) {
                continue;
            }
            foreach ($this->index[$trigram] as $language => $lCount) {
                if (!isset($scores[$language])) {
                    $scores[$language] = 0;
                }
                $score = ($lCount / $this->languages[$language])
                    * ($count / $total);
                $scores[$language] += $score;
            }
        }
        arsort($scores);

        return key($scores);
    }

    private function getWords($document)
    {
        $document = strtolower($document);
        preg_match_all('/\p{L}+/ui', $document, $matches);

        return $matches[0];
    }

    private function getNgrams($match, $n = 3)
    {
        $ngrams = [];

        $len = \count($match);
        for ($i = 0; $i < $len; $i++) {
            if ($i > ($n - 2)) {
                $ng = '';
                for ($j = $n - 1; $j >= 0; $j--) {
                    if ($ng !== '') {
                        $ng .= ' ';
                    }
                    $ng .= $match[$i - $j];
                }
                $ngrams[] = $ng;
            }
        }

        return $ngrams;
    }

    public function getPredictions($search, $level=1): array
    {
        $predictions = [];

        $words = $this->getWords($search);
        $nbWords = \count($words);
        $wordsSearch = [];

        for ($i = $nbWords - 1;$i >= $nbWords - 2;$i--) {
            $wordsSearch[] = $words[$i];
        }

        $wordsSearch = array_reverse($wordsSearch);
        $searchRe = implode(' ', $wordsSearch);

        foreach($this->index as $prediction => $score) {
            if (preg_match('/^'.$searchRe.'/Usi', $prediction)) {
                $predictions[str_replace($searchRe, '', $prediction)] = $score;

                $futuresPredictions = $this->getPredictions($prediction, $level+1);
                if (\count($futuresPredictions) > 0) {
                    unset($predictions[str_replace($searchRe, '', $prediction)]);
                }
                foreach ($futuresPredictions as $futurePrediction => $futureScore) {
                    $predictions[str_replace($searchRe, '', $prediction).$futurePrediction] = $score + $futureScore;
                }
            }
        }

        arsort($predictions);

        return $predictions;
    }
}