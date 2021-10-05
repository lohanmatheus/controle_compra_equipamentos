<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-light">
                <h5 class="modal-title" id="staticBackdropLabel">Adicionar Produtos </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="body-search-products" class="modal-body">

                <div class="row">
                    <div class="col-12">
                        <form class="d-flex">
                            <input class="form-control me-2" id="product-name" type="search"
                                   placeholder="Faça sua Busca" aria-label="Search">
                            <button class="btn btn-outline-success" type="button"
                                    onclick="modalAddProduct.listProducts()">Buscar
                            </button>
                        </form>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div id="area-menu2" style="display: none" class="table-responsive">
                            <table class="table">
                                <thead class="text-uppercase bg-primary text-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Tipo</th>
                                    <th>Descrição</th>
                                    <th>Quantidade</th>
                                    <th colspan="2">OPCOES</th>
                                </tr>
                                </thead>
                                <tbody id="result-list">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <div id="footer-search-products" class="modal-footer" style="display: flex; justify-content: space-between">
                <div>
                    <button type="button" class="btn btn-secondary" onclick="modalAddProduct.showFormInsertProduct()">
                        Cadastrar Produto
                    </button>
                </div>
                <div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="listADD()">Adicionar
                    </button>
                </div>
            </div>

            <div id="body-add-products" class="modal-body" style="display: none;">

                <div class="row">
                    <div class="col-12" id="insert-product">

                        <form method="post" id='insert-form'>
                            <input id="id-product" value="" type="hidden">

                            <label for="product-type">Tipo do produto</label>
                            <br/>
                            <select id="product-type" name="product-type" onclick="selectOption()" required>
                                <option>Selecione</option>
                            </select>
                            <br/>

                            <label for="name">Nome do Produto</label>
                            <br/>
                            <input id="name" value="" placeholder="" required>
                            <br/>

                            <label for="description">Descrição</label>
                            <br/>
                            <input id="description" value="" placeholder="">
                        </form>
                    </div>
                </div>
            </div>
            <div id="footer-add-products" class="modal-footer" style="display: none;">
                <button type="button" class="btn btn-secondary"
                        onclick="modalAddProduct.hideFormInsertProduct()">Voltar</button>
                <button type="button" class="btn btn-primary"
                        onclick="verificaCadastroProduto()">Cadastrar</button>
            </div>
        </div>
    </div>
</div>


<script>

    function ModalAddProduct() {
        this.showFormInsertProduct = () => {
            document.getElementById('body-search-products').style.display = 'none'
            document.getElementById('footer-search-products').style.display = 'none'
            document.getElementById('body-add-products').style.display = 'block'
            document.getElementById('footer-add-products').style.display = 'block'
        }

        this.hideFormInsertProduct = () => {
            document.getElementById('body-add-products').style.display = 'none'
            document.getElementById('footer-add-products').style.display = 'none'
            document.getElementById('body-search-products').style.display = 'block'
            document.getElementById('footer-search-products').style.display = 'flex'
        }

        this.loader = () => {
            document.getElementById('result-list').innerHTML = `<tr>
                <td colspan="8" class="text-center">Carregando dados. Aguarde!</td>
            </tr>`;
        }

        this.trProduct = function (product) {
            let quantityProduct = JSON.parse(localStorage.getItem('quantity_product') || '{}')
            let dados = encodeURIComponent(JSON.stringify(product));

            return `<tr>
                        <td>${product.id}</td>
                        <td>${product.name}</td>
                        <td>${product.product_type_name}</td>
                        <td>${product.description}</td>
                        <td id="${'quantity-modal-' + product.id}">${quantityProduct[product.id] || 0}</td>
                        <td><button id="qtd-mais" class="btn btn-sm btn-success btn-add" onclick="mais('${dados}')">+</button></td>
                        <td><button id="qtd-menos" class="btn btn-sm btn-danger btn-remove" onclick="menos('${dados}')">-</button></td>
                    </tr>`;
        }
        this.listProducts = function () {
            const productName = document.getElementById('product-name').value

            if (productName === '') {
                alert('Informe o nome do produto no campo de busca!');
                return;
            }

            document.getElementById('area-menu2').style.display = 'block';
            this.loader()
            Api.post({
                data: {
                    name: productName
                },
                classe: 'request',
                acao: 'search'
            }).then(response => {
                if (response.codigo === 1) {
                    document.getElementById('result-list').innerHTML = '';
                    response.dados.forEach(registro => {
                        document.getElementById('result-list').innerHTML += this.trProduct(registro);
                    });
                    return false;
                }

                document.getElementById('result-list').innerHTML = `<tr>
                        <td colspan="8">${response.msg}</td>
                    </tr>`;
            })
        }
    }

    const modal = document.getElementById('staticBackdrop')
    const card = document.getElementById('produtos-adcionados')
    const modalAddProduct = new ModalAddProduct();
    modal.addEventListener('shown.bs.modal', function () {
        card.style.display = 'flex'
    })
    modal.addEventListener('show.bs.modal', function () {
        card.style.display = 'flex'
    })
</script>