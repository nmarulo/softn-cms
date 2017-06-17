<?php
/**
 * Util.php
 */

namespace SoftnCMS\util;

/**
 * Class Util
 * @author Nicolás Marulanda P.
 */
class Util {
    
    /**
     * Método que obtiene la fecha actual.
     *
     * @param string $format Formato de la fecha.
     *
     * @return false|string
     */
    public static function dateNow($format = 'Y-m-d H:i:s') {
        return date($format, time());
    }
    
    public static function curl($url) {
        $curlInit = curl_init();
        curl_setopt($curlInit, CURLOPT_URL, $url);
        curl_setopt($curlInit, CURLOPT_USERAGENT, "SoftN CMS App");
        curl_setopt($curlInit, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, TRUE);
        $data     = curl_exec($curlInit);
        $httpCode = curl_getinfo($curlInit, CURLINFO_HTTP_CODE);
        curl_close($curlInit);
        
        if ($httpCode == 200) {
            return $data;
        }
        
        return FALSE;
    }
    
    /**
     * Método que realiza el HASH al valor pasado por parámetro.
     *
     * @param string $value
     * @param string $saltedKey
     *
     * @return string
     */
    public static function encrypt($value, $saltedKey) {
        return hash('sha256', $value . $saltedKey);
    }
    
    /**
     * Método para redireccionar a la pagina de inicio.
     *
     * @param string $siteUrl
     * @param string $route
     */
    public static function redirect($siteUrl, $route = '') {
        header('Location: ' . $siteUrl . $route);
        exit();
    }
}
