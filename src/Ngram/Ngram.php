<?php
namespace Melicerte\Ngram;

class Ngram {

    /**
     * @param string $word
     * @param int $n
     * @return array
     */
    protected function getNgrams(string $word, $n): array
    {
        $ngrams = array();
        $len = strlen($word);
        for ($i = 0; $i < $len; $i++) {
            if ($i > ($n - 2)) {
                $ng = '';
                for ($j = $n - 1; $j >= 0; $j--) {
                    $ng .= $word[$i - $j];
                }
                $ngrams[] = $ng;
            }
        }

        return $ngrams;
    }

    /**
     * @param array $grams
     * @param string $entry
     * @param int $n
     * @return array
     */
    protected function getProbabilities(array $grams, string $entry, $n) {
        $res = [];
        $total = 0;

        foreach ($grams as $gram) {
            if ($n === 1 || strpos($gram, $entry) === 0) {
                $next = substr($gram, $n - 1, 1);

                if (!isset($res[$next])) {
                    $res[$next] = 0;
                }

                $res[$next]++;
                $total++;
            }
        }

        $probas = [];
        foreach ($res as $next => $r) {
            $probas[$next] = ($r/$total)*100;
        }

        return $probas;
    }

    /**
     * @param string $serie
     * @param string $entry
     * @param int $n
     * @return mixed
     */
    public function getProbabilitiesForResult(string $serie, string $entry, $n) {
        $grams = $this->getNgrams($serie, $n);
        return $this->getProbabilities($grams, $entry, $n);
    }
}
