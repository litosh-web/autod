<?php

require_once 'index.class.php';

class GetListautoD extends IndexautoD
{
    public $limit = 5;

    public function process()
    {
        $data = $this->getProperties();

        $get = array(
            'q' => $data['q'],
            'onlyCountries' => $data['onlyCountries'],
            'limit' => $this->limit,
        );

        $ch = curl_init('https://api.avtodispetcher.ru/v1/cities?' . http_build_query($get));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $html = curl_exec($ch);
        curl_close($ch);


        return $this->success('', [
            'html' => $html
        ]);
    }


}

return 'GetListautoD';