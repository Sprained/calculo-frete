<?php

namespace Sprained\Correios\Constants;

abstract class WebService
{
    //URL do webservice dos Correios para calculo do preço e do prazo
    const CALC = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx/CalcPrecoPrazo';

    //URL para rastreio de incomenda
    const RAST = 'https://www.linkcorreios.com.br/';
} 