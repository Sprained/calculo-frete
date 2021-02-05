<?php

namespace Sprained\Correios\Interfaces;

interface FreteInterface
{
    /**
     * Dimensões de um item, peso e quantidade
     * 
     * @param int|float $largura
     * @param int|float $altura
     * @param int|float $comprimento
     * @param int|float $peso
     * @param int       $quantidade
     * 
     * @return self
     */
    public function item($largura, $altura, $comprimento, $peso, $quantidade = 1);

    /**
     * Array com infomações dos itens
     * 
     * @param array $items
     * 
     * @return self
     */
    public function items($items = []);
}
