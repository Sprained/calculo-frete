<?php

namespace Sprained\Correios\Interfaces;

interface TrackingInterface
{
    /**
     * Rastreio de incomendas
     * 
     * @param string $rastreio
     * 
     * @return object
     */
    public function tracking($rastreio);
}