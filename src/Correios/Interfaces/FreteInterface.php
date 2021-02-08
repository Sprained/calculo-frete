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

    /**
     * Serviços correio como Sedex, Pac
     * 
     * @param string $servico
     * 
     * @return self
     */
    public function servico(...$servico);

    /**
     * CEP de origem
     * 
     * @param string $cep
     * 
     * @return self
     */
    public function origem($cep);

    /**
     * CEP de destino
     * 
     * @param string $cep
     * 
     * @return self
     */
    public function destino($cep);

    /**
     * Formato da encomeda (Caixa, pacote, rolo ou envelope)
     * 
     * @param int $formato
     * 
     * @return self
     */
    public function pacote($formato);

    /**
     * Indica se a encomenda seré entregue com o serviço adicional mão propria
     * 
     * @param bool $mao
     * 
     * @return self
     */
    public function entregaEmMaos($mao);

    /**
     * Indeca se a encomenda será entregue com o serviço adicional valor declarado,
     * valor deve ser informado em reais
     * 
     * @param int|float $valor
     * 
     * @return self
     */
    public function valorDeclarado($valor);

    /**
     * Codigo administrativo junto à ECT, disponivel no corpo do do contrato do Correios
     * 
     * Senha para acesso ao serviço, senha inicial correspondem os 8 primeiros digitos
     * do CNPJ
     * 
     * @param string $code
     * @param string $password
     * 
     * @return self
     */
    public function credenciais($code, $password);

    /**
     * Calculo junto com os correios preço e prazo
     * 
     * @return array
     */
    public function calculo();
}
