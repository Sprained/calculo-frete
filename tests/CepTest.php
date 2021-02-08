<?php

namespace Sprained;

use Sprained\Correios\Cep;
use PHPUnit\Framework\TestCase;

class CepTest extends TestCase //comentar o die() na função de erro para fazer os teste
{
    public function testCep()
    {
        $cep = new Cep();

        $this->assertEquals('51021-020', $cep->cep('51021-020')->cep);

        $cep->cep('11111111');
        $this->assertEquals(400, http_response_code());
        $this->expectOutputString(json_encode(['message' => 'CEP Inválido!']));
    }
}