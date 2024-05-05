<?
//yaDWcjx8k%HT!Snd
ini_set("soap.wsdl_cache_enabled", "0");
//$client = new SoapClient("http://wsf.cdyne.com/WeatherWS/Weather.asmx?wsdl");
$client = new SoapClient("https://ese.decor-evroplast.ru:4848/Work/ESE.1cws?wsdl",
    array(
        'login'=>'ese_user',
        'password'=>'yaDWcjx8k%HT!Snd'
    )
);
$params['SiteID'] = '4ec55941-480d-42ea-b410-45680124abcd';

//$result = $client->PostDocs($params);




print_r($client->__getFunctions());
print_r($result);


?>

