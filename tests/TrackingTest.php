<?php

namespace Sprained;

use Sprained\Correios\Tracking;
use PHPUnit\Framework\TestCase;
use Sprained\Correios\Exceptions\TrackingException;

class TrackingTest extends TestCase
{
    public function testTracking()
    {
        $tracking = new Tracking();
        $result = $tracking->tracking('LB208326091SG');

        $json = '{
            "code": "LB208326091SG",
            "last_status": "Objeto em trânsito - por favor aguarde",
            "last_date": "03/02/2021 14:49",
            "last_locale": "CTCE FORTALEZA - Fortaleza / CE",
            "tracking": [
              {
                "status": "Objeto em trânsito - por favor aguarde",
                "date": "03/02/2021 14:49",
                "local": "CTCE FORTALEZA - Fortaleza / CE",
                "destino": "CTE RECIFE - Recife / PE"
              },
              {
                "status": "Objeto em trânsito - por favor aguarde",
                "date": "29/01/2021 14:26",
                "local": "UNIDADE INTERNACIONAL CURITIBA - Curitiba / PR",
                "destino": "CTE CAJAMAR - Cajamar / SP"
              },
              {
                "status": "Fiscalização aduaneira finalizada",
                "date": "29/01/2021 14:24",
                "local": "UNIDADE INTERNACIONAL CURITIBA - Curitiba / PR"
              },
              {
                "status": "Objeto recebido pelos Correios do Brasil",
                "date": "29/01/2021 10:44",
                "local": "UNIDADE INTERNACIONAL CURITIBA - Curitiba / PR"
              },
              {
                "status": "Objeto em trânsito - por favor aguarde",
                "date": "09/01/2021 11:47",
                "local": "CINGAPURA -  /",
                "destino": "Unidade de Tratamento Internacional -  / BR"
              }
            ]
          }';

        $this->assertEquals(json_decode($json), json_decode($result));
    }

    public function testTrackingError()
    {
        $this->expectException(TrackingException::class);
        $tracking = new Tracking();
        $tracking->tracking('JJJJJJJJJ');
    }
}