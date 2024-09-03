<?php

// App/Libraries/Multilingual.php
namespace App\Libraries;

class Multilingual
{
    protected $session;
    protected $Crud;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->Crud = new \App\Models\Crud(); // Replace with your actual model name
    }

    public function _ph($phrase = '')
    {
        $currentLang = $this->session->get('current_language') ?? 'english';
        $nicename = strtolower($phrase);
        $nicename = preg_replace("/[^a-z0-9_\s-]/", "", $nicename);
        $nicename = preg_replace("/[\s-]+/", " ", $nicename);
        $nicename = preg_replace("/[\s_]/", "_", $nicename);

        // echo $nicename.' '.$currentLang;

        $checkPhrase = $this->Crud->read_field('phrase', $nicename, 'language', strtolower($currentLang));

        if ($checkPhrase != '' && $checkPhrase != NULL) {
            return $checkPhrase;
        } else {
           
            $langPhrase = ucwords(str_replace("_", " ", $nicename));
            $eng_langPhrase = ucwords(str_replace("_", " ", $nicename));

            $currentLang = strtolower($currentLang);
            //Translate the Word
            if($currentLang != 'english'){
                $target_language = $this->Crud->read_field('name', strtolower($currentLang), 'language_code', 'code');
                $response = $this->Crud->google_translate($langPhrase, 'en', $target_language);
               
                // print_r($response);
                $resp = json_decode($response, true);

                
                $langPhrase = $resp['data']['translations'][0]['translatedText'];
                
            }

            if($this->Crud->check('phrase', $nicename, 'language') > 0){
                $this->Crud->updates('phrase', $nicename, 'language', ['phrase' => $nicename, $currentLang => $langPhrase, 'english'=>$eng_langPhrase]);
            } else {

                if($currentLang == 'english'){
                    $this->Crud->create('language', ['phrase' => $nicename, 'english'=>$eng_langPhrase]);
    
                } else{
                    $this->Crud->create('language', ['phrase' => $nicename, $currentLang => $langPhrase, 'english'=>$eng_langPhrase]);
    
                }
               
            }
            return $langPhrase;
        }
    }

    public function _translate($text = '', $src = '', $target = '')
    {
        $result = '';

        if ($text && $src && $target) {
            $apiKey = 'AIzaSyBVr7w0HzbOhjlVanMaWNQD1iB57Eo9_w4'; // Replace with your actual API key
            $url = 'https://www.googleapis.com/language/translate/v2?key=' . $apiKey . '&q=' . rawurlencode($text) . '&source=' . $src . '&target=' . $target;

            $client = \Config\Services::curlrequest();
            $response = $client->request('GET', $url);
            $responseCode = $response->getStatusCode();

            if ($responseCode == 200) {
                $responseData = json_decode($response->getBody(), true);
                $result = $responseData['data']['translations'][0]['translatedText'];
            }
        }

        return $result;
    }

    public function _langCode($text = '')
    {
        $result = '';

        if ($text) {
            $text = strtolower($text);

            $language = file_get_contents(base_url('assets/js/language.js'));
            $language = json_decode($language);

            foreach ($language->data->languages as $lang) {
                $langName = strtolower($lang->name);
                $langCode = $lang->language;

                if ($text == $langName) {
                    $result = $langCode;
                    break;
                }
            }
        }

        return $result;
    }
}
