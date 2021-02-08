# Calculo Frete Correios PHP

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

Biblioteca para a realização de calculo de frete junto a api do correios e consulta de cep.

## Funcionalidades

- [CEP](#consulta-pelo-cep)
- [Frete](#calculo-frete)

## Instalação

Via Composer

``` bash
$ composer require sprained/validator-php
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

Calcula valor e tempo de envio junto com a API do Correios.

``` php
require 'vendor/autoload.php';

use Sprained\Correios\Frete;
use Sprained\Correios\Service;

$frete = new Frete();

/*
    Array de itens para calculo do frete
    Parametros em ordem:
    largura, altura, comprimento, peso, quantidade (caso conter mais de um item)
*/
$items = [
    [16, 16, 16, 16],
    [16, 16, 16, 16]
];

/*
    Calculo frete e prazo para entrega

    Obrigatorio
    origem: cep de origem da encomenda
    destino: cep de destino para entrega da encomenda
    items: array de itens a enviar
    servico: tipo do serviço utilizado para entrega dos itens
    calculo: faz o calculo e retorna o valor

    Não obrigatorio
    pacote: informa tipo de pacote (caixa, prisma, envelope), por padrão se encontra em caixa
    entregaEmMaos: informa que incomenda sera entregue com serviço mão propria
    valorDeclarado: informa que incomenda sera entregue com serviço valor declarado
    credenciais: Codigo administrativo junto à ECT, disponivel no corpo do do contrato do Correios 

    Serviços de entrega
    Service::PAC
    Service::SEDEX
    Service::SEDEX_10
    Service::SEDEX_12
    Service::SEDEX_HOJE
    Para serviços de entrega adicional passar numeração do serviço informado no contrato com correios
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

[ico-version]: https://img.shields.io/packagist/v/sprained/validator-php.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/sprained/validator-php.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/sprained/validator-php
[link-downloads]: https://packagist.org/packages/sprained/validator-php
[link-author]: https://github.com/sprained
[link-vickie]: https://github.com/itsvickie