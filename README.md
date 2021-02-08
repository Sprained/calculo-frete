# Calculo Frete Correios PHP

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

Biblioteca para a facilitação do cálculo de preços e prazos de entregas, usando a API disponibilizada pelos Correios. E realização de consultas de endereços por CEP, através do VIACEP.

## Funcionalidades

- [CEP](#consulta-pelo-cep)
- [Frete](#calculo-frete)

## Instalação

Via Composer

``` bash
$ composer require sprained/calculo-frete
```

## Uso

### Consulta pelo CEP

Encontra endereço pelo CEP, consultando diretamente no VIACEP;

``` php
require 'vendor/autoload.php';

use Sprained\Correios\Cep;

$cep = new Cep();

/*
    Retorna endereço pelo CEP
    Parâmetro: CEP com hífen ou sem o mesmo
*/
$retorno = $cep->cep('51021-020');

/*
stdClass Object
(
    [cep] => 51021-020
    [logradouro] => Avenida Conselheiro Aguiar
    [complemento] => de 3812/3813 ao fim
    [bairro] => Boa Viagem
    [localidade] => Recife
    [uf] => PE
    [ibge] => 2611606
    [gia] => 
    [ddd] => 81
    [siafi] => 2531
)
*/
```

### Calculo frete

Calcula o prazo e preço da entrega por meio da API disponibilizada pelos Correio.

``` php
require 'vendor/autoload.php';

use Sprained\Correios\Frete;
use Sprained\Correios\Service;

$frete = new Frete();

/*
    Array de itens para cálculo do frete
    Parâmetros em ordem:
    largura, altura, comprimento, peso, quantidade (caso conter mais de um item)
*/
$items = [
    [16, 16, 16, 16],
    [16, 16, 16, 16]
];

/*
    Cálculo frete e prazo para entrega

    Campos Obrigatórios
    origem: cep de origem da encomenda
    destino: cep de destino para entrega da encomenda
    items: array de itens a enviar
    servico: tipo do serviço utilizado para entrega dos itens
    calculo: faz o cálculo e retorna o valor

    Campos Não Obrigatórios
    pacote: informa tipo de pacote (caixa, prisma, envelope), por padrão se encontra em caixa
    entregaEmMaos: informa que a encomenda será entregue com serviço mão propria
    valorDeclarado: informa que encomenda será entregue com serviço valor declarado
    credenciais: código administrativo junto à ECT, disponivel no corpo do contrato do Correios 

    Serviços de Entregas
    Service::PAC
    Service::SEDEX
    Service::SEDEX_10
    Service::SEDEX_12
    Service::SEDEX_HOJE
    Para serviços de entregas adicionais, passar numeração do serviço informado no contrato com correios
*/
$retorno = $frete->origem('51021020')
                ->destino('50060230')
                ->servico(Service::SEDEX, Service::PAC)
                ->items($items)
                ->calculo();

/*
Array
(
    [0] => Array
        (
            [codigo] => 4
            [valor] => 169,30
            [prazo] => 1 Dias
        )

    [1] => Array
        (
            [codigo] => 4
            [valor] => 151,70
            [prazo] => 5 Dias
        )
)
*/
```

## Créditos

- [Gabriel Resende][link-author]
- [Vitoria Camila][link-vickie]

[ico-version]: https://img.shields.io/packagist/v/sprained/calculo-frete.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/sprained/calculo-frete.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/sprained/calculo-frete
[link-downloads]: https://packagist.org/packages/sprained/calculo-frete
[link-author]: https://github.com/sprained
[link-vickie]: https://github.com/itsvickie