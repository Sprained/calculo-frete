<?php

namespace Sprained\Correios;

use SimpleXMLElement;
use Sprained\Validator;
use Sprained\Correios\Constants\Pacote;
use Sprained\Correios\Constants\WebService;
use Sprained\Correios\Interfaces\FreteInterface;

class Frete implements FreteInterface
{
    //playload para base na requisição correios
    protected $playloadPadrao = [
        'nCdEmpresa' => '',
        'sDsSenha' => '',
        'nCdServico' => '',
        'sCepOrigem' => '',
        'sCepDestino' => '',
        'nVlPeso' => 0,
        'nVlComprimento' => 0,
        'nVlAltura' => 0,
        'nVlLargura' => 0,
        'nVlDiametro' => 0,
        'sCdMaoPropria' => 'N',
        'nVlValorDeclarado' => 0,
        'sCdAvisoRecebimento' => 'N',
        'nCdFormato' => Pacote::PACOTE
    ];

    //playload para requisição
    protected $playload = [];

    /**
     * Itens a serem transportado
     * Maior largura entre itens
     * Soma total da altura de todos os itens
     * Maior comprimento entre os itens
     * Soma total do peso dos itens
     */
    protected $items = [];

    public function item($largura, $altura, $comprimento, $peso, $quantidade = 1) {
        $this->items[] = compact('largura', 'altura', 'comprimento', 'peso', 'quantidade');

        return $this;
    }

    public function items($items = [])
    {
        foreach($items as $value) {
            $this->item($value[0], $value[1], $value[2], $value[3], isset($value[4]) ? $value[4] : 1);
        }

        return $this;
    }

    /**
     * Calcular e retornar a maior largura
     * 
     * @return int|float
     */
    public function largura()
    {
        return max(array_map(function($item) {
            return $item['largura'];
        }, $this->items));
    }

    /**
     * Calcular e retornar o maior comprimento
     * 
     * @return int|float
     */
    public function comprimento()
    {
        return max(array_map(function($item) {
            return $item['comprimento'];
        }, $this->items));
    }

    /**
     * Calcular e retornar a soma de todas as alturas
     * 
     * @return int|float
     */
    public function altura()
    {
        return array_sum(array_map(function($item) {
            return $item['altura'] * $item['quantidade'];
        }, $this->items));
    }

    /**
     * Calcular e retornar a soma de todas as alturas
     * 
     * @return int|float
     */
    public function peso()
    {
        return array_sum(array_map(function($item) {
            return $item['peso'] * $item['quantidade'];
        }, $this->items));
    }

    /**
     * Playload para requisição ao correios
     * 
     * @return array
     */
    public function playload()
    {
        if($this->items) {
            $this->playload['nVlPeso'] = $this->volumeOrPeso();
            $this->playload['nVlComprimento'] = $this->comprimento();
            $this->playload['nVlAltura'] = $this->altura();
            $this->playload['nVlLargura'] = $this->largura();
            $this->playload['nVlDiametro'] = 0;
        }
        
        return array_merge($this->playloadPadrao, $this->playload);
    }

    public function servico(...$servico)
    {
        $this->playload['nCdServico'] = implode(',', array_unique($servico));

        return $this;
    }

    public function origem($cep)
    {
        $validator = new Validator();

        $this->playload['sCepOrigem'] = $validator->cep($cep);

        return $this;
    }

    public function destino($cep)
    {
        $validator = new Validator();

        $this->playload['sCepDestino'] = $validator->cep($cep);

        return $this;
    }

    public function pacote($formato)
    {
        $this->playload['nCdFormato'] = $formato;

        return $this;
    }

    public function entregaEmMaos($mao)
    {
        $playload['sCdMaoPropria'] = (bool) $mao ? 'S' : 'N';

        return $this;
    }

    public function valorDeclarado($valor)
    {
        $this->playload['nVlValorDeclarado'] = floatval($valor);
    }

    public function credenciais($code, $password)
    {
        $this->playload['nCdEmpresa'] = $code;
        $this->playload['sDsSenha'] = $password;

        return $this;
    }

    /**
     * Calcular volume do frete com base no comprimento, algura e largura dos itens
     * 
     * @return int|float
     */
    public function volume()
    {
        return ($this->comprimento() * $this->largura() * $this->altura()) / 6000;
    }

    /**
     * Calcular qual valor (volume ou peso) deve ser utilizado no final
     * 
     * @return int|float
     */
    public function volumeOrPeso()
    {
        if($this->volume() < 10 || $this->volume() <= $this->peso()) {
            return $this->peso();
        }
        return $this->volume();
    }

    public function calculo()
    {
        $correios = WebService::CALC . '?' . http_build_query($this->playload());
        
        $ch = curl_init(); //INICIA CONEXÃO
        curl_setopt($ch, CURLOPT_URL, $correios); //LIGAÇÃO COM URL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // HABILITA RESPONSE
        $response = curl_exec($ch);
        
        $xml = json_decode(json_encode(simplexml_load_string($response)));
        $json = $xml->Servicos->cServico;

        $arr = [];
        if($json->Erro == '0') {
            $arr['codigo'] = $json->Codigo[0];
            $arr['valor'] = $json->Valor;
            $arr['prazo'] = $json->PrazoEntrega . ' Dias';
            return $arr;
        } else {
            $arr['code'] = $json->Erro;
            $arr['messagge'] = $json->MsgErro;
            return $arr;
        }
    }
}