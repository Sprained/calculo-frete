<?php

namespace Sprained\Correios;

use Sprained\Validator;
use Sprained\Correios\Constants\Pacote;
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
            $this->playload['nVlPeso'] = $this->peso;
            $this->playload['nVlComprimento'] = $this->comprimento;
            $this->playload['nVlAltura'] = $this->alturo;
            $this->playload['nVlLargura'] = $this->largura;
            $this->playload['nVlDiametro'] = 0;
        }
        
        return array_merge($this->playloadPadrao, $this->playload);
    }

    /**
     * Serviços correio como Sedex, Pac
     * 
     * @param string $servico
     * 
     * @return self
     */
    public function servico($servico)
    {
        $this->playload['nCdServico'] = $servico;

        return $this;
    }

    /**
     * CEP de origem
     * 
     * @param string $cep
     * 
     * @return self
     */
    public function origem($cep)
    {
        $validator = new Validator();

        $this->playload['sCepOrigem'] = $validator->cep($cep);

        return $this;
    }

    /**
     * CEP de destino
     * 
     * @param string $cep
     * 
     * @return self
     */
    public function destino($cep)
    {
        $validator = new Validator();

        $this->playload['sCepDestino'] = $validator->cep($cep);

        return $this;
    }

    
}