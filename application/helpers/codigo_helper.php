<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (! function_exists('parse_codigos_recipientes')) {
    /**
     * Extrai uma lista de codigos de recipiente a partir de um texto livre
     * (digitado ou preenchido pelo scanner de QR), aceitando virgula,
     * ponto-e-virgula, espaco ou quebra de linha como separador.
     */
    function parse_codigos_recipientes($texto)
    {
        $partes = preg_split('/[\s,;]+/', trim((string) $texto));
        $codigos = array();

        foreach ($partes as $parte) {
            $parte = strtoupper(trim($parte));

            if ($parte !== '') {
                $codigos[] = $parte;
            }
        }

        return $codigos;
    }
}
