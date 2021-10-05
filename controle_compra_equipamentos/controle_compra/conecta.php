<?php
include ('User.php');
$parametros = (array)json_decode(file_get_contents('php://input'),true);
$link = pg_connect("host=localhost port=5432 dbname=compra_equipamentos user=postgres password=2032418202lo");

if(empty($parametros)){
    $parametros = $_REQUEST;
}

if ($parametros['classe'] == 'user') {
    $usuario = new User($link, $parametros);

    switch ($parametros['acao']){

        case "logging":
            $arrayResposta = $usuario->login();
            break;
        case "select":
            $arrayResposta = $usuario->selectFuncionario();
            break;
        case "update":
            $arrayResposta = $usuario->updateFuncionario();
            break;
        case "listar":
            $arrayResposta = $usuario->listFuncionario();
            break;
        case "inserir":
            $arrayResposta = $usuario->insertFuncionario();
            break;
        case "remove":
            $arrayResposta = $usuario->removeFuncionario();
            break;
        case "listRequestPendentes":
            $arrayResposta = $usuario->listRequestPendentes();
            break;
        case "listRequestPendentesModal":
            $arrayResposta = $usuario->listRequestPendentesModal();
            break;
        case "reason":
            $arrayResposta = $usuario->reason();
            break;
        case "setStatusApprove":
            $arrayResposta = $usuario->setStatusApprove();
            break;
        case "listStock":
            $arrayResposta = $usuario->listStock();
            break;
        default:
            echo 0;
    }
    echo json_encode($arrayResposta);
    exit;
}

if ($parametros['classe'] == 'request') {
    $usuario = new User($link, $parametros);

    switch ($parametros['acao']){

        case "selectType":
            $arrayResposta = $usuario->selectType();
            break;
        case "inserir":
            $arrayResposta = $usuario->insertProduct();
            break;
        case "search":
            $arrayResposta = $usuario->searchProduct();
            break;
        case "select":
            $arrayResposta = $usuario->selectProduct();
            break;
        case "request":
            $arrayResposta = $usuario->requestProduct();
            break;
        case "listADD":
            $arrayResposta = $usuario->listADDProduct();
            break;
        case "listRequest":
            $arrayResposta = $usuario->listRequest();
            break;
        case "listRequestApproved":
            $arrayResposta = $usuario->listRequestApproved();
            break;
        case "getReason":
            $arrayResposta = $usuario->getReason();
            break;
        case "removeRequest":
            $arrayResposta = $usuario->removeRequest();
            break;
        case "slcPaymentType":
            $arrayResposta = $usuario->slcPaymentType();
            break;
        case "insertUploadBuyRequest":
            $arrayResposta = $usuario->insertInvoiceBuyRequest();
            break;
        case "insertBuyRequest":
            $arrayResposta = $usuario->insertBuyRequest();
            break;
        case "listProductsComprados":
            $arrayResposta = $usuario->listProductsComprados();
            break;
        case "listRequestBuy":
            $arrayResposta = $usuario->listRequestBuy();
            break;
        default:
            echo 0;
    }
    echo json_encode($arrayResposta);
    exit;
}
echo json_encode([
    'codigo' => 0,
    'msg' => 'Informe uma acao e ferramenta para utilizar a api!',
    'dados' => [],
    'productType' => []
]);