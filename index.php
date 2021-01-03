<?php

require_once "vendor/econea/nusoap/src/nusoap.php";
login();

$namespace = "Soap.alv";
$server = new soap_server();
$server->configureWSDL("SOAP-ORDEN-DE-COMPRAS",$namespace);
$server->wsdl->schemaTargetNamespace = $namespace;

$server->wsdl->addComplexType(
    'articulo',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'Nombre' => array('name' => 'Nombre', 'type' => 'xsd:string'),
        'Cantidad' => array('name' => 'Cantidad', 'type' => 'xsd:int'),
        'Precio' => array('name' => 'Precio', 'type' => 'xsd:decimal')
    )
);

$server->wsdl->addComplexType(
    'listaArticulos',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:articulo[]')
    )
);

$server->wsdl->addComplexType(
    'ordenDeCompra',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'NumeroOrden' => array('name' => 'NumeroOrden', 'type'=>'xsd:string'),
        'Nombre' => array('name' => 'Nombre', 'type'=>'xsd:string'),
        'Articulos' => array('name' => 'Articulos', 'type' => 'tns:listaArticulos'),
        'Fecha' => array('name' => 'Fecha', 'type'=>'xsd:string'),
        'Total' => array('name' => 'Total', 'type'=>'xsd:decimal')
    )
);

$server->wsdl->addComplexType(
    'response',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'NumeroDeAutorizacion' => array('name'=>'NumeroDeAutorizacion', 'type'=>'xsd:string'),
        'Articulo' => array('name' => 'Articulo', 'type' => 'xsd:string'),
        'Resultado' => array('name' => 'Resultado', 'type' => 'xsd:boolean')
    )
);

$server->register(
    'guardarOrden',
    array('name' => 'tns:ordenDeCompra'),
    array('name' => 'tns:response'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Recibe una orden de compra y regresa un número de autorización'
);

function guardarOrden($request){
    return array(
        "NumeroDeAutorizacion" => "La orden ".$request["NumeroOrden"]." ha sido autorizada con el # ". rand(10000, 100000),
        "Articulo" => 'Articulo recibido: '.$request["Articulos"][0]["Nombre"],
        "Resultado" => true
    );
}

function login(){
    if(!isset($_SERVER['PHP_AUTH_USER'])){
        header('WWW-Authenticate: Basic reaml="MiSoap"');
        header('HTTP/1.0 401 Unauthorized');
        exit;
    }

    if($_SERVER['PHP_AUTH_USER'] = 'jefferson' && $_SERVER['PHP_AUTH_PW'] = 'jefferson'){
        header('Content-Type: application/soap+xml; charset=utf-8');
        return true;
    }
    else{
        header('WWW-Authenticate: Basic reaml="MiSoap"');
        header('HTTP/1.0 401 Unauthorized');
        exit;
    }

}

$POST_DATA = file_get_contents("php://input");
$server->service($POST_DATA);
exit();