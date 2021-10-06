<?php
require_once(__DIR__ . '/header-adm.php');
pageHeader('Estoque');
?>
<section class="container">

    <header>
        <div style="text-align: center">
            <h1 class="display-5"> Estoque de Produtos </h1>
        </div>
    </header>
    <div class="row">
        <div class="col-12">
            <div id="product-stock" class="card" style="">
                <div class="card-header pt-3">
                    <div style="float: left;">
                        <h5 class="card-title">Produtos</h5>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="area-menu-stock" style="display: none" class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="bg-dark text-light text-uppercase">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">TIPO</th>
                                <th scope="col">NOME</th>
                                <th scope="col">QUANTIDADE</th>
                            </tr>
                            </thead>
                            <tbody id="list-stock">
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
    function getStock() {

        this.loader = () => {
            document.getElementById('list-stock').innerHTML = `<tr>
                <td colspan="8" class="text-center">Carregando dados. Aguarde!</td>
            </tr>`;
        }

        this.trStock = function (product) {

                return `<tr>
                                    <td>${product.id_product}</td>
                                    <td>${product.product_type}</td>
                                    <td>${product.name}</td>
                                    <td>${product.quantity}</td>
                    </tr>`;

        }

        this.listStock = function () {

            document.getElementById('area-menu-stock').style.display = 'block';
            this.loader()
            Api.post({
                classe: 'user',
                acao: 'listStock'
            }).then(response => {
                if (response.codigo === 1) {
                    document.getElementById('list-stock').innerHTML = '';
                    response.dados.forEach(product => {
                        document.getElementById('list-stock').innerHTML += this.trStock(product);
                    });

                    return false;
                }

                document.getElementById('list-stock').innerHTML = `<tr>
                        <td colspan="8">${response.msg}</td>
                    </tr>`;
            })
        }
    }

    const listStock = new getStock();

    window.onload = function () {
        listStock.listStock();
    }
</script>
<?php require_once('footer.php');?>

