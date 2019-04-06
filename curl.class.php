<?php

/**
 * simple curl-class
 *
 * @author     ildar r. khasanshin <ildar.khasanshin@gmail.com>
 * @copyright  2019
 * @license    Apache license 2.0
 * @version    1.0
 * @link       https://github.com/ildarkhasanshin/curl
 */
class curl
{
    private $returntransfer;
    private $customrequest;

    /**
     * uifaces constructor
     * @param bool $returntransfer
     * @param string $customrequest
     */
    public function __construct($returntransfer = true, $customrequest = 'get')
    {
        $this->returntransfer = $returntransfer;
        $this->customrequest = $customrequest;
    }

    /**
     * @param $url
     * @param $api_key
     * @return bool|mixed
     */
    public function curl($url, $api_key)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $this->returntransfer);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->customrequest);
        if (!empty($api_key)) {
            $headers = array();
            $headers[] = 'X-API-KEY: ' . $api_key;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        $result = curl_exec($ch);
        $res = $result !== false ? json_decode($result) : false;
        $errors = false;
        $errors_key = 'errors';
        if ($res !== null) {
            $errs = @$res->errors;
            if (isset($errs) || curl_errno($ch)) {
                $errors[$errors_key] = array(
                    'curl_errno' => curl_errno($ch),
                    'curl_error' => curl_error($ch)
                );
                if (is_array($errs)) {
                    foreach ($errs[0] as $j => $err) {
                        $errors[$errors_key][$j] = $err;
                    }
                }
            }
            curl_close($ch);
            if (isset($errors[$errors_key])) {
                print_r($errors);
                return false;
            }
            return $res;
        }
        echo $errors[$errors_key] = $result;
        return false;
    }

    /**
     * @param $url
     * @param $params
     * @param $api_key
     * @return bool|mixed
     */
    public function get($url, $params = array(), $api_key = '')
    {
        return $this->curl($url . (count($params) > 0 ? '?' . http_build_query($params) : ''), $api_key);
    }
}
