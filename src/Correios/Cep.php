<?php

namespace Sprained\Correios;

use Sprained\Validator;

class Cep {
    public function cep($cep)
    {
        $validator = new Validator();
        $cep = $validator->cep($cep);

        $ch = curl_init(); // INICIA CONEXÃO
        curl_setopt($ch, CURLOPT_URL, "https://viacep.com.br/ws/$cep/json/"); // LIGAÇÃO COM O LINK

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // HABILITA RESPONSE
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // DESABILITAR CERTIFICAÇÃO SSL
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // DESABILITAR CERTIFICAÇÃO SSL

        $response = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($response);

        if(isset($json->erro)){
            http_response_code(400);
            echo json_encode(['message' => 'CEP Inválido!']);
            // die;
        }

        return $json;
    }
}