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
        
        $resposta = [
            [
                'codigo' => '4',
                'valor' => '169,30',
                'prazo' => '1 Dias'
            ],
            [
                'codigo' => '4',
                'valor' => '151,70',
                'prazo' => '5 Dias'
            ]
        ];
        $this->assertEquals($resposta, $frete->origem('51021020')
                                            ->destino('50060230')
                                            ->servico(Service::SEDEX, Service::PAC)
                                            ->items($items)
                                            ->calculo()
                            );
    }
}