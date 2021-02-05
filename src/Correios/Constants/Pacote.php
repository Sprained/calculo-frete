<?php

namespace Sprained\Correios\Constants;

abstract class Pacote 
{
    //Encomenda do tipo caixa/pacote
    const PACOTE = 1;

    //Encomenda do tipo rolo/prisma
    const ROLO = 2;

    //Encomenda do tipo envelope
    const ENVELOPE = 3;
}