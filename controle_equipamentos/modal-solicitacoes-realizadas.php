<div class="modal fade" id="modal-request-user" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true" style="display: none">
    <div class="modal-dialog  modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-light">
                <h5 class="modal-title" id="staticBackdropLabel-user">Produtos da Requisição</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close" onclick="listProductsBuy.trLimparValueTotal()">
                </button>
            </div>
            <div id="body-products-request-user" class="modal-body">
                <div class="row mt-3">
                    <div class="col-12">
                    <div id="area-menu-produtos-comprados" style="display: none" class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="bg-dark text-light text-uppercase">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">TIPO</th>
                                <th scope="col">NOME</th>
                                <th scope="col">QUANTIDADE</th>
                                <th scope="col">DATA REQUISIÇÃO</th>
                                <th scope="col">VALOR</th>
                                <th scope="col">NOTA FISCAL</th>
                            </tr>
                            </thead>
                            <tbody id="list-produtos-comprados">
                            </tbody>
                            <tfoot id="value-total-products">
                                   <tr>
                                       <th scope="col">Valor Total</th>
                                   </tr>
                            </tfoot>
                        </table>
                        <div id="footer-search-products-user" class="modal-footer" style="display: flex;
                             justify-content: space-between; float: right; border-top: solid 0px">
                            <button type="button" class="btn btn-sm btn-danger btn-remove"
                                    data-bs-dismiss="modal" onclick="listProductsBuy.trLimparValueTotal()">Fechar
                            </button>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function getProducts() {

        this.loader = () => {
            document.getElementById('list-produtos-comprados').innerHTML = `<tr>
                <td colspan="8" class="text-center">Carregando dados. Aguarde!</td>
            </tr>`;
        }

        this.trStock = function (product) {

            return `<tr>
                        <td>${product.id}</td>
                        <td>${product.product_type}</td>
                        <td>${product.name}</td>
                        <td>${product.quantity}</td>
                        <td>${product.date}</td>
                        <td>R$: ${product.value}</td>
                        <td><a href="${product.invoice}" target="_blank">Nota Fiscal</a></td>
                    </tr>`;

        }
        this.trValueTotal = function (valueTotal) {
            return `<tr>
                        <td>R$: ${valueTotal}</td>
                    </tr>`;
        }
        this.trLimparValueTotal = function () {
            document.getElementById('value-total-products').innerHTML = '';
        }

        this.listProductsbuy = function (id) {

            document.getElementById('area-menu-produtos-comprados').style.display = 'block';
            this.loader()
            Api.post({
                idPurchase: id,
                classe: 'request',
                acao: 'listProductsComprados'
            }).then(response => {
                if (response.codigo === 1) {
                    document.getElementById('list-produtos-comprados').innerHTML = '';
                    response.dados.forEach(product => {
                        document.getElementById('list-produtos-comprados').innerHTML += this.trStock(product);
                    });
                    document.getElementById('value-total-products').innerHTML += this.trValueTotal(response.valueTotal);

                    return false;
                }

                document.getElementById('list-stock').innerHTML = `<tr>
                        <td colspan="8">${response.msg}</td>
                    </tr>`;
            })
        }
    }

    const listProductsBuy = new getProducts();

</script>
<?php require_once('footer.php');?>

