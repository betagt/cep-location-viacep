<?php
/**
 * Created by PhpStorm.
 * User: dsoft
 * Date: 05/08/2016
 * Time: 17:45
 */

namespace Begagt\Services;


class CepService
{

    private static $endpoint = 'http://viacep.com.br/ws/';

    const RETURN_TYPE_JSON = 'json';

    const RETURN_TYPE_XML = 'xml';

    const RETURN_TYPE_PIPED = 'piped';

    const RETURN_TYPE_QUERTY = 'querty';

    /**
     * @var CacheService
     */
    private $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * @param int $cep
     * @return array
     * @throws RequestException
     */
    public function requestCep($cep,$type = self::RETURN_TYPE_JSON){
        $json_file = $this->builderUrl($cep,$type);
        if(array_key_exists('erro',$json_file)){
            throw new \Exception("CEP Inválido!");
        }
        return $json_file;
    }

    /**
     * @param array $args ['estado','cidade','endereco']
     * @return mixed
     * @throws RequestException
     */
    public function cepLocation($args,$type = self::RETURN_TYPE_JSON){
        $result = $this->builderUrl($args,$type);

        return $result;
    }

    private function builderUrl($args, $type){

        if(is_array($args))
            $args = implode('/',$args);

        $args = self::$endpoint.$args.'/'.$type.'/';

        if(!$this->cacheService->has('zipcode_'.$args)){

            $result = $this->strategyConvert($args,$type);
            if(array_key_exists('erro',$result)){
                throw new \Exception("Localidade Inválida!");
            }

            $this->cacheService->put('zipcode_'.$args,$result);
        }
        return (array)$this->cacheService->get('zipcode_'.$args);
    }

    private function strategyConvert($args , $type){
        switch ($type){
            case self::RETURN_TYPE_JSON:
                return json_decode(file_get_contents($args));
                break;
            case self::RETURN_TYPE_XML:
                return file_get_contents($args);
                break;
        }
    }
}