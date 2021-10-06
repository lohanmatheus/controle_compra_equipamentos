<div class="modal fade" id="modal-request" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-light">
                <h5 class="modal-title" id="staticBackdropLabel">Comprar Produtos da Requisição</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="body-products-request" class="modal-body">
                <div class="row mt-3">
                    <div class="col-12">
                        <div id="area-request2" style="display: none" class="table-responsive">
                            <table class="table">
                                <thead class="text-uppercase bg-primary text-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Tipo</th>
                                    <th>Nome</th>
                                    <th>Quantidade</th>
                                    <th>Link</th>
                                </tr>
                                </thead>
                                <tbody id="request-list">
                                </tbody>
                            </table>
                            <div id="footer-products-request" class="modal-footer" style="display: flex;
                             justify-content: space-between; float: right; border-top: solid 0px">
                                <div>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-dismiss="modal"
                                            aria-label="Close">Voltar
                                    </button>

                                    <button type="button" class="btn btn-sm btn-danger"
                                            onclick="ModalListProductRequestAdm.showFormInsertProduct();">
                                        Recusar
                                    </button>

                                    <button id="qtd-mais" class="btn btn-sm btn-success btn-add" onclick="approveRequest()">Aprovar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="body-recuse-request" class="modal-body" style="display: none;">

                <div class="row">
                    <div class="col-12" id="insert-product">
                        <form method="post" id='insert-form'>
                            <div class="form-group col-md-10 mb-1">
                                <label for="reason-textarea">Motivo:</label>
                                <textarea class="form-control" id="reason-textarea" placeholder="Motivo da recusa!" rows="3" required></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div id="footer-recuse-request" class="modal-footer" style="display: none;">
                <button type="button" class="btn btn-secondary"
                        onclick="ModalListProductRequestAdm.hideFormInsertProduct()">Voltar</button>
                <button type="button" class="btn btn-primary"
                        onclick="checkReasonCancellation()">Enviar</button>
            </div>

        </div>
    </div>
</div>

<script>
    let idPurchaseRequestReason = ''

    function ModalAddProductRequestAdm() {
        this.showFormInsertProduct = () => {
            document.getElementById('body-products-request').style.display = 'none'
            document.getElementById('footer-products-request').style.display = 'none'
            document.getElementById('body-recuse-request').style.display = 'block'
            document.getElementById('footer-recuse-request').style.display = 'block'
        }

        this.hideFormInsertProduct = () => {
            document.getElementById('body-recuse-request').style.display = 'none'
            document.getElementById('footer-recuse-request').style.display = 'none'
            document.getElementById('body-products-request').style.display = 'block'
            document.getElementById('footer-products-request').style.display = 'flex'
        }

        this.loaderRequest = () => {
            document.getElementById('request-list').innerHTML = `<tr>
                <td colspan="8" class="text-center">Carregando dados. Aguarde!</td>
            </tr>`;
        }

        this.trProductRequest = function (product) {

            return `<tr>
                        <td>${product.id}</td>
                        <td>${product.product_type}</td>
                        <td>${product.product_name}</td>
                        <td>${product.quantity}</td>
                        <td><input type="text" value="${product.link}"></td>
                    </tr>`;

        }
        this.listProductsRequestAdm = function (id) {

            idPurchaseRequestReason = id;
            document.getElementById('area-request2').style.display = 'block';
            this.loaderRequest()
            Api.post({
                id,
                classe: 'user',
                acao: 'listRequestPendentesModal'
            }).then(response => {
                if (response.codigo === 1) {
                    document.getElementById('request-list').innerHTML = '';
                    response.dados.forEach(registro => {
                        document.getElementById('request-list').innerHTML += this.trProductRequest(registro);
                    });
                    return false;
                }

                document.getElementById('request-list').innerHTML = `<tr>
                        <td colspan="8">${response.msg}</td>
                    </tr>`;
            })
        }
    }

    const modal = document.getElementById('modal-request')
    const ModalListProductRequestAdm = new ModalAddProductRequestAdm();

    function checkReasonCancellation() {
        if (confirm("Confirmar o cancelamento ?")) {
            let reason = document.getElementById('reason-textarea').value
            if (reason === '') {
                alert("Insira um motivo!");
                return false;
            }
            sendReasonCancellation(reason)
        }
    }
    function sendReasonCancellation(reason){

            Api.post({
                reason,
                id: idPurchaseRequestReason,
                classe: 'user',
                acao: 'reason'
            }).then(response => {
                if (response.codigo === 1) {
                    alert("Motivo da recusa enviado!")
                    window.location.href = "http://localhost:8000/controle_equipamentos/indexadm.php";
                    return false;
                }

                alert(response.msg);
            })
    }

    function approveRequest() {
        if (confirm("Aprovar requisição ?")) {

            Api.post({
                id: idPurchaseRequestReason,
                classe: 'user',
                acao: 'setStatusApprove'
            }).then(response => {
                if (response.codigo === 1) {
                    alert("Aprovado!")
                    window.location.href = "http://localhost:8000/controle_equipamentos/indexadm.php";
                    return false;
                }

                alert(response.msg);
            })
        }
    }

</script>
