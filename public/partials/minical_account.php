<?php
if(!function_exists('wp_get_current_user')) {
    include(ABSPATH . "wp-includes/pluggable.php"); 
}
class ApiClientPublic
{
    private $baseUrl = '';
    private $xApiKey  = '';

    public function __construct($xApiKey, $url)
    {
        $this->xApiKey = $xApiKey;
        $this->baseUrl= $url;
    }

    public function sendRequest($path, $method='GET', $data=array())
    {
        $url = $this->baseUrl . $path;
        $method = strtoupper($method);
        $curl = curl_init();
        $headers = array(
            "X-API-KEY: ".$this->xApiKey
        );    
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        switch ($method) {
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, count($data));
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                break;
            case 'PUT':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                break;
            case 'DELETE':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                break;
            default:
                if (!empty($data)) {
                    $url .= '?' . http_build_query($data);
                }
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        $response = curl_exec($curl);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $headerCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $responseBody = substr($response, $header_size);
        curl_close($curl);
        $retval = new stdClass();
        $retval->data = json_decode($responseBody);
        $retval->http_code = $headerCode;
        return $retval;
    }
}
