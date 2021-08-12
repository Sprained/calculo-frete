<?php

namespace Sprained;

use Sprained\Correios\Frete;
use Sprained\Correios\Service;
use PHPUnit\Framework\TestCase;

class FreteTest extends TestCase //comentar o die() na função de erro para fazer os teste
{
    public function testCep()
    {
        $frete = new Frete();

        $items = [
            [16, 16, 16, 16],
            [16, 16, 16, 16]
        ];

        $calculo = $frete->origem('51021020')
                    ->destino('50060230')
                    ->servico(Service::SEDEX, Service::PAC)
                    ->items($items)
                    ->calculo();
        
        $resposta = [
            [
                'codigo' => $calculo[0]['codigo'],
                'valor' => $calculo[0]['valor'],
                'prazo' => $calculo[0]['prazo']
            ],
            [
                'codigo' => $calculo[1]['codigo'],
                'valor' => $calculo[1]['valor'],
                'prazo' => $calculo[1]['prazo']
            ]
        ];
        $this->assertEquals($resposta, $calculo);
    }
}