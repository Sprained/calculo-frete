<?php

namespace Sprained\Correios;

use Sprained\Validator;

class Cep {
    public function cep($cep)
    {
        $validator = new Validator();
        $cep = $validator->count(8, 8, 'Cep', trim($cep));

        $ch = curl_init(); // INICIA CONEXÃO
        curl_setopt($ch, CURLOPT_URL, "https://viacep.com.br/ws/$cep/json/"); // LIGAÇÃO COM O LINK

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // HABILITA RESPONSE
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // DESABILITAR CERTIFICAÇÃO SSL
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // DESABILITAR CERTIFICAÇÃO SSL

        $response = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($response);

        if(isset($json->erro)){
            header("HTTP/1.0 400 Bad Request");
            echo "CEP Inválido!";
            die();
        }

        return $json;
    }
}