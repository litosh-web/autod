<?php

require_once 'index.class.php';

class GenerateautoD extends IndexautoD
{
    public $login;
    public $pass;

    public function process()
    {
        $data = $this->getProperties();

        $errors = $this->getErrors($data);

        if ($errors) {
            return $this->failure($this->modx->lexicon('autod_fields_required'), [
                'errors' => $errors
            ]);
        }

        $get = [
            'from' => $data['from'],
            'to' => $data['to'],
        ];

        $this->login = $this->modx->getOption('autod_login', '', '');
        $this->pass = $this->modx->getOption('autod_pass', '', '');

        $ch = curl_init('https://api.avtodispetcher.ru/v1/route?' . http_build_query($get));
        curl_setopt($ch, CURLOPT_USERPWD, join(":", [$this->login, $this->pass]));
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['accept: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $html = curl_exec($ch);
        curl_close($ch);

        $arr = $this->modx->fromJSON($html);

        if (in_array($arr['status'], [401])) {
            return $this->failure($this->modx->lexicon('autod_auth_failed'));
        }

        return $this->success('', [
            'km' => $arr['kilometers'],
            'minutes' => $arr['minutes'],
        ]);
    }

    public function getErrors($data)
    {
        if (!$data['from']) {
            $errors[] = 'autod__from';
        }
        if (!$data['to']) {
            $errors[] = 'autod__to';
        }

        return $errors;
    }


}

return 'GenerateautoD';