<?php

namespace Sprained\Correios;

use DomXPath;
use DOMDocument;
use Sprained\Correios\Constants\WebService;
use Sprained\Correios\Interfaces\TrackingInterface;
use Sprained\Correios\Exceptions\TrackingException;

class Tracking implements TrackingInterface
{
    public function tracking($rastreio)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, WebService::RAST . $rastreio);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // DESABILITAR CERTIFICAÇÃO SSL
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // DESABILITAR CERTIFICAÇÃO SSL
        $response = curl_exec($ch);
        if ($response === false) {
            throw new TrackingException(json_encode(['message' => curl_error($ch)]), 400);
        }
        curl_close($ch);

        $dom = new DOMDocument;
        @$dom->loadHTML($response);
        $finder = new DomXPath($dom);

        $arr = [];
        foreach ($finder->query("//*[contains(@class, 'linha_status')]") as $ul) {
            $li = $ul->getElementsByTagName('li');
            $status = str_replace('Status: ', '', $li[0]->textContent);
            $date = str_replace(['Data  : ', 'Data : ', ' | ', 'Hora:'], '', $li[1]->textContent);
            $local = str_replace(['Local: ', 'Origem: '], '', $li[2]->textContent);
            $destino = str_replace('Destino: ', '', isset($li[3]->textContent) ? $li[3]->textContent : null);
            $arr[$date] = [
                'status' => $status,
                'date' => $date,
                'local' => rtrim($local)
            ];

            if ($destino) $arr[$date] = array_merge($arr[$date], ['destino' => rtrim($destino)]);
        }

        $tracking = array_values($arr);

        if (!isset($tracking[0])) {
            throw new TrackingException(json_encode(['message' => 'Rastreio não encontrado']), 400);
        }

        return json_encode(array_merge(
            ['code' => $rastreio],
            ['last_status' => $tracking[0]['status']],
            ['last_date' => $tracking[0]['date']],
            ['last_locale' => $tracking[0]['local']],
            ['tracking' => $tracking]
        ));
    }
}
