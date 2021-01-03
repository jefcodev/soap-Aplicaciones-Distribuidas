<?php

$location = "http://localhost/soap-php/index.php?wsdl";
$request = "<soapenv:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:soap=\"Soap.alv\" xmlns:SOAP-ENC=\"http://schemas.xmlsoap.org/soap/encoding/\">
<soapenv:Header/>
<soapenv:Body>
   <soap:guardarOrden soapenv:encodingStyle=\"http://schemas.xmlsoap.org/soap/encoding/\">
      <name xsi:type=\"soap:ordenDeCompra\">
         <!--You may enter the following 5 items in any order-->
         <NumeroOrden xsi:type=\"xsd:string\">123</NumeroOrden>
         <Nombre xsi:type=\"xsd:string\">Jose</Nombre>
         <Articulos xsi:type=\"soap:listaArticulos\" SOAP-ENC:arrayType=\"soap:articulo[]\"/>
         <Fecha xsi:type=\"xsd:string\">32</Fecha>
         <Total xsi:type=\"xsd:decimal\">12</Total>
      </name>
   </soap:guardarOrden>
</soapenv:Body>
</soapenv:Envelope>";

print("Request: <br>");
print("<pre>".htmlentities($request)."</pre>");


$action = "guardarOrden";
$headers = [
    'Method: POST',
    'Connection: Keep-Alive',
    'User-Agent: PHP-SOAP-CURL',
    'Content-Type: text/xml; charset=utf-8',
    'SOAPAction: "guardarOrden"',
];




$ch = curl_init($location);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, 'jefferson:jefferson'); //usuario:contraseña

$response = curl_exec($ch);
$err_status = curl_error($ch);

print("Response: <br>");
print("<pre>".$response."</pre>");