<?php
require_once(__DIR__ . '/header.php');
pageHeader('Solicitaçoes realizadas');
?>
    <section class="container">

        <header>
            <div style="text-align: center">
                <h1 class="display-5"> Solicitações Aprovadas/Recusadas</h1>
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
                                    <th scope="col">Data</th>
                                    <th scope="col">Quantidade Solicitações</th>
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

        function getRequest() {
            this.getIdPurchaseRequest = (id) => {
                iDPurchaseRequest = id;
            }

            this.loader = () => {
                document.getElementById('list-request').innerHTML = `<tr>
                <td colspan="8" class="text-center">Carregando dados. Aguarde!</td>
            </tr>`;
            }

            this.trRequest = function (product) {

                if(product.status_name === 'aprovado') {
                    return `<tr>
                                    <td>${product.id}</td>
                                    <td>${product.request_date}</td>
                                    <td>${product.quantity_products}</td>
                                    <td>${product.status_name}</td>
                        <td><button id="btn-list-request" class="btn btn-sm btn-success btn-add" data-bs-toggle="modal"
                         data-bs-target="#modal-request-user" onclick="ModalListProductRequest.listProductsRequest('${product.id}');">Comprar</button></td>
                    </tr>`;
                }
                else if(product.status_name === 'recusado') {
                    return `<tr>
                                <td>${product.id}</td>
                                <td>${product.request_date}</td>
                                <td>${product.quantity_products}</td>
                                <td>${product.status_name}</td>
                                <td><button id="buy" class="btn btn-sm btn-danger btn-remove" onclick="removeRequest('${product.id}')">Remover</button>
                                    <button id="btn-view-reason" class="btn btn-sm btn-primary btn-add" data-bs-toggle="modal"
                                            data-bs-target="#modal-reason" onclick="ModalListReason.listReason('${product.id}')">Motivo</button></td>
                            </tr>`;
                }
                else if(product.status_name === 'pendente') {
                    return `<tr>
                                    <td>${product.id}</td>
                                    <td>${product.request_date}</td>
                                    <td>${product.quantity_products}</td>
                                    <td>${product.status_name}</td>
                                    <td></td>
                    </tr>`;
                }
            }

            this.listRequest = function () {

                document.getElementById('area-menu-request').style.display = 'block';
                this.loader()
                Api.post({
                    id: iDPurchaseRequest,
                    classe: 'request',
                    acao: 'listRequest'
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

        function removeRequest(id){
            if(confirm("Excluir essa requisição ?")) {
                Api.post({
                    id,
                    classe: 'request',
                    acao: 'removeRequest'
                }).then(response => {
                    if (response.codigo === 1) {
                        alert("Removido!");
                        window.location.href = "listar-solicitacoes-aprovadas-recusadas.php"
                        return false;
                    }
                    alert(response.msg);
                })
            }
        }

        const listAllRequest = new getRequest();

        window.onload = function () {
            listAllRequest.listRequest();
        }
    </script>
<?php require_once (__DIR__ . '/modal-reason.php');
require_once (__DIR__ . '/modal-request-product.php');
 require_once('footer.php');?>

