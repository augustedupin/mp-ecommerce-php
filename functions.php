<?php

    function obtenerPago($uri, $method, $public_key, $secret_key, $request) {
        
        $domainUri="https://api.mercadopago.com/";
        $connect = curl_init();

        curl_setopt($connect, CURLOPT_URL, $domainUri.$uri);
        curl_setopt($connect, CURLOPT_HEADER, 0);
		curl_setopt($connect, CURLOPT_RETURNTRANSFER, 1);
        $resultado = curl_exec($connect);
        $http_code = curl_getinfo($connect, CURLINFO_HTTP_CODE);
        $respuesta = json_decode($resultado, true);

		return $respuesta;
    }

?>