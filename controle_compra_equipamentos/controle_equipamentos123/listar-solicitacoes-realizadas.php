<?php
require_once(__DIR__ . '/header.php');
pageHeader('Solicitaçoes realizadas');
?>
<section class="container">

    <header>
        <div style="text-align: center">
            <h1 class="display-5"> Solicitações Realizadas</h1>
        </div>
    </header>
    <div class="row">
        <div class="col-12">
            <div id="product-request" class="card" style="">
                <div class="card-header pt-3">
                    <div style="float: left;">
                        <h5 class="card-title">Produtos</h5>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="area-menu-request" style="display: none" class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="bg-dark text-light text-uppercase">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Data Aprovação</th>
                                <th scope="col">Adm</th>
                                <th scope="col">Data Compra</th>
                                <th scope="col">Quantidade</th>
                                <th scope="col">Status</th>
                                <th scope="col" colspan="2">Opcoes</th>
                            </tr>
                            </thead>
                            <tbody id="list-request">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<script>
    let iDPurchaseRequest = '';

    function getRequestBuy() {
        this.getIdPurchaseRequest = (id) => {
            iDPurchaseRequest = id;
        }

        this.loader = () => {
            document.getElementById('list-request').innerHTML = `<tr>
                <td colspan="8" class="text-center">Carregando dados. Aguarde!</td>
            </tr>`;
        }

        this.trRequest = function (product) {

                return `<tr>
                                    <td>${product.id}</td>
                                    <td>${product.verification_date_adm}</td>
                                    <td>${product.name_user}</td>
                                    <td>${product.purchase_date}</td>
                                    <td>${product.quantity}</td>
                                    <td>${product.status}</td>
                        <td><button id="btn-list-request" class="btn btn-sm btn-primary btn-add" data-bs-toggle="modal"
                         data-bs-target="#modal-request-user" onclick="listProductsBuy.listProductsbuy('${product.id}');">Verificar</button></td>
                    </tr>`;
        }

        this.listRequestBuy = function () {

            document.getElementById('area-menu-request').style.display = 'block';
            this.loader()
            Api.post({
                id: iDPurchaseRequest,
                classe: 'request',
                acao: 'listRequestBuy'
            }).then(response => {
                if (response.codigo === 1) {
                    document.getElementById('list-request').innerHTML = '';
                    response.dados.forEach(product => {
                        document.getElementById('list-request').innerHTML += this.trRequest(product);
                    });

                    return false;
                }

                document.getElementById('list-request').innerHTML = `<tr>
                        <td colspan="8">${response.msg}</td>
                    </tr>`;
            })
        }
    }


    const listAllRequestBuy = new getRequestBuy();

    window.onload = function () {
        listAllRequestBuy.listRequestBuy();
    }
</script>
<?php
require_once (__DIR__ . '/modal-solicitacoes-realizadas.php');
require_once('footer.php');
?>

