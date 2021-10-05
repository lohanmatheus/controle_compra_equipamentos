<?php
require_once(__DIR__ .'/header-adm.php');
pageHeader('Administrador');
?>
    <section class="container">

        <header>
            <div style="text-align: center">
                <h1 class="display-5"> Solicitações Pendentes</h1>
            </div>
        </header>
        <div class="row">
            <div class="col-12">
                <div id="product-request-adm" class="card" style="">
                    <div class="card-header pt-3">
                        <div style="float: left;">
                            <h5 class="card-title">Solicitações</h5>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div id="area-menu-request-adm" style="display: none" class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="bg-dark text-light text-uppercase">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Usuario</th>
                                    <th scope="col">Data</th>
                                    <th scope="col">Quantidade</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" colspan="2">Opcoes</th>
                                </tr>
                                </thead>
                                <tbody id="list-request-adm">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-foot p-1" style="text-align: right;">

                    </div>

                </div>
            </div>
        </div>

    </section>
    <script>
        let iDPurchaseRequest = '';

        function getRequestAdm() {
            this.getIdPurchaseRequest = (id) => {
                iDPurchaseRequest = id;
            }

            this.loader = () => {
                document.getElementById('list-request-adm').innerHTML = `<tr>
                <td colspan="8" class="text-center">Carregando dados. Aguarde!</td>
            </tr>`;
            }

            this.trRequest = function (product) {
                    return `<tr>
                                    <td>${product.id}</td>
                                    <td>${product.user_request}</td>
                                    <td>${product.request_date}</td>
                                    <td>${product.quantity}</td>
                                    <td>${product.status_name}</td>
                        <td><button id="btn-buy" class="btn btn-sm btn-primary btn-add" data-bs-toggle="modal"
                         data-bs-target="#modal-request" onclick="ModalListProductRequestAdm.listProductsRequestAdm('${product.id}')">Verificar</button></td>
                    </tr>`;
            }

            this.listRequestAdm = function () {

                document.getElementById('area-menu-request-adm').style.display = 'block';
                this.loader()
                Api.post({
                    id: iDPurchaseRequest,
                    classe: 'user',
                    acao: 'listRequestPendentes'
                }).then(response => {
                    if (response.codigo === 1) {
                        document.getElementById('list-request-adm').innerHTML = '';
                        response.dados.forEach(product => {
                            document.getElementById('list-request-adm').innerHTML += this.trRequest(product);
                        });

                        return false;
                    }

                    document.getElementById('list-request-adm').innerHTML = `<tr>
                        <td colspan="8">${response.msg}</td>
                    </tr>`;
                })
            }
        }


        const listAllRequestAdm = new getRequestAdm();

        window.onload = function () {
            listAllRequestAdm.listRequestAdm();
        }
    </script>
<?php require_once (__DIR__ . '/modal-request-product-adm.php');
        require_once('footer.php'); ?>