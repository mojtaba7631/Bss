<?php
function sms($receptor, $message)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.ghasedak.me/v2/sms/send/simple",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "message=$message&receptor=$receptor&linenumber=3000772120&senddate=1508144471&checkid=1",
        CURLOPT_HTTPHEADER => array(
//            "apikey: 5d9feff37917a7a5036940ef137be13f108d18b234811ddc705e11d8dd8668bd",
            "apikey: 3650af3ab846b6a86400b6868c83366ea3f2ad418c00865f015507fc679e1a09",
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded",
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        return false;
    } else {
        return true;
    }
}


function sms_otp($receptor, $template, ...$args)
{
    if (is_array($args[0])) {
        $args = $args[0];
    }
    if (is_array($receptor)) {
        $receptor = implode(",", $receptor);
    }
    $path = 'verification/send/simple';
    $params = array(
        "receptor" => $receptor,
        "type" => 1,
        "template" => $template
    );
    if (count($args) > 10 || count($args) == 0) {
        return response()->json(
            [
                "errorMesage" => 'Number of parameters exceeds maximum of 10'
            ], 419
        );
    }
    foreach ($args as $key => $arg) {
        $params[$key] = $arg;
    }
    return runCurl($path, $params);
}


function runCurl($path, $parameters = null, $method = 'POST')
{
    $headers = array(
        'apikey:3650af3ab846b6a86400b6868c83366ea3f2ad418c00865f015507fc679e1a09',
        'Accept: application/json',
        'Content-Type: application/x-www-form-urlencoded',
        'charset: utf-8'
    );

    $params = http_build_query($parameters);
    $url = "http://api.ghasedak.me/v2/" . $path . "?agent=php";

    $init = curl_init();
    curl_setopt($init, CURLOPT_URL, $url);
    curl_setopt($init, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($init, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($init, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($init, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($init, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($init, CURLOPT_POSTFIELDS, $params);

    $result = curl_exec($init);
    $code = curl_getinfo($init, CURLINFO_HTTP_CODE);
    $curl_errno = curl_errno($init);
    $curl_error = curl_error($init);
    if ($curl_errno) {
        throw new HttpException($curl_error, $curl_errno);
    }

    $json_result = json_decode($result);

    if ($code != 200 && is_null($json_result)) {
        throw new HttpException("Request http errors", $code);
    } else {
        $return = $json_result->result;
        if ($return->code != 200) {
            return response()->json(
                [
                    "errorMesage" => $return->message
                ], $return->code
            );
        }
        return $json_result;
    }
}
