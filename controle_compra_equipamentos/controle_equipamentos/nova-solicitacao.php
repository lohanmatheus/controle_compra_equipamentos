<?php
require_once(__DIR__ . '/header.php');
pageHeader('Criar Solicitação');
?>
    <br/>
<section class="container">

    <header>
        <div style="text-align: center">
            <h1 class="display-5"> Nova solicitação </h1>
        </div>
    </header>
    <div class="row">
        <div class="col-12">
            <div id="produtos-adcionados" class="card">
                <div class="card-header pt-3">
                    <div style="float: left;">
                        <h5 class="card-title">Produtos Adcionados</h5>
                    </div>
                    <div style="float: right;">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Adicionar Produtos
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" >
                            <thead class="bg-dark text-light text-uppercase">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Produtos</th>
                                <th scope="col">Quantidade</th>
                                <th scope="col">Link</th>
                                <th scope="col" colspan="3" style="text-align: center;">Opcoes</th>
                            </tr>
                            </thead>
                            <tbody id="list-add">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-foot p-1" style="text-align: right;">
                    <button type="button" class="btn btn-outline-success"
                            onclick="solicitar()">Enviar Solicitação de Compra
                    </button>
                </div>

            </div>
        </div>
    </div>

</section>

    <script>
        function setVal(id, value) {
            document.getElementById(id).value = value;
        }

        const getVal = selector => document.querySelector(selector).value;

        const verificaCadastroProduto = function () {
            let productType = document.getElementById('product-type').value
            let name = document.getElementById('name').value
            let description = document.getElementById('description').value

            if (productType == '') {
                alert('Insira um tipo de produto!')
                return
            }
            if (name == '') {
                alert('Insira o Login!')
                return
            }
            sendCadastroProduto(productType, name, description);
        }

        function sendCadastroProduto(productType, name, description) {
            const params = {
                data: {
                    name: name,
                    description: description,
                    productType: productType
                },
                classe: 'request',
                acao: 'inserir'
            }

            const nameProduct = getVal('#name')
            const id = getVal('#id-product')
            if (id !== '') {
                if (!confirm(`Confirmar a Requisição do ${nameProduct} ?`))
                    return;

                params['data']['id'] = parseInt(id);
                params['acao'] = 'request';
            }

            let configRequest = {
                method: 'POST',
                cache: 'default',
                body: JSON.stringify(params),
                headers: {
                    'Content-Type': 'application/json'
                }
            }

            let myRequest = new Request('http://localhost:8000/controle_equipamentos/conecta.php', configRequest);

            fetch(myRequest).then(response => {
                return response.json();
            }).then(function (response) {
                alert(response.msg)
                if (response.codigo === 1) {
                    document.getElementById('insert-form').reset();
                    modalAddProduct.hideFormInsertProduct();
                    return;
                }
            })
        }

        const verificaRequestProduct = function () {
            let productType = document.getElementById('product-type').value
            let name = document.getElementById('name').value
            let description = document.getElementById('description').value

            if (productType === '') {
                alert('Insira um tipo de produto!')
                return
            }
            if (name === '') {
                alert('Insira o Login!')
                return
            }
            sendCadastroProduto(productType, name, description);
        }

        function listScreen() {
            document.getElementById('area-menu2').style.display = 'block';
        }

        function resetForm() {
            document.getElementById('insert-form').reset();
        }

        function insertScreen() {
            document.getElementById('request-product').style.display = 'none';
            document.getElementById('area-menu2').style.display = 'none'
            document.getElementById('form-search').style.display = 'none';
            document.getElementById('solicitar-product').style.display = 'none';
            document.getElementById('title').innerHTML = 'Cadastrar produto para solicitação';
            document.getElementById('insert-product').style.display = 'block';
        }

        function voltarScreen() {
            document.getElementById('produtos-adcionados').style.display = 'block';
            document.getElementById('solicitar-product').style.display = 'none';
            document.getElementById('request-product').style.display = 'none';
            document.getElementById('insert-product').style.display = 'none';
            document.getElementById('form-search').style.display = 'block';
            document.getElementById('area-menu2').style.display = 'none';
            document.getElementById('title').innerHTML = 'Solicitar Produtos';
        }

        function selectOption() {

            let verificaSelect = document.getElementById('product-type').getElementsByTagName('option');
            let select = document.getElementById("product-type");

            let bodyRequest = JSON.stringify({
                classe: 'request',
                acao: 'selectType'
            })
            let configRequest = {
                method: 'POST',
                cache: 'default',
                body: bodyRequest,
                headers: {
                    'Content-Type': 'application/json'
                }
            }
            let myRequest = new Request('http://localhost:8000/controle_equipamentos/conecta.php', configRequest);
            fetch(myRequest).then(function (response) {
                return response.json();
            }).then(response => {
                if (response.codigo === 1) {
                    console.log(response.dados)
                    response.dados.forEach(registro => {

                        let option = document.createElement("option");
                        option.value = registro.id;
                        option.text = registro.name;

                        if (verificaSelect.length < 7) {
                            select.appendChild(option)
                        }

                    });

                }

            })
        }

        const verificaSearch = function () {
            let searchProduct = document.getElementById('search').value
            searchData(searchProduct);
        }

        function mais(register) {
            register = JSON.parse(decodeURIComponent(register));
            let productAdd = JSON.parse(localStorage.getItem('products_add') || '[]');
            let quantityProduct = JSON.parse(localStorage.getItem('quantity_product') || '{}')
            let currentQuantity = quantityProduct[register.id] || 0;
            let trQuantity = document.getElementById('quantity-' + register.id);
            let modalQuantity = document.getElementById('quantity-modal-' + register.id);

            if (currentQuantity === 0) productAdd.push(register);
            quantityProduct[register.id] = parseInt(currentQuantity) + 1;

            localStorage.setItem('quantity_product', JSON.stringify(quantityProduct))
            localStorage.setItem('products_add', JSON.stringify(productAdd))

            if (trQuantity !== null)
                trQuantity.innerText = quantityProduct[register.id];

            if (modalQuantity !== null)
                modalQuantity.innerText = quantityProduct[register.id];

        }

        function menos(register) {
            register = JSON.parse(decodeURIComponent(register));
            let productAdd = JSON.parse(localStorage.getItem('products_add') || '[]');
            let quantityProduct = JSON.parse(localStorage.getItem('quantity_product') || '{}')
            let currentQuantity = quantityProduct[register.id] || 0;
            let trQuantity = document.getElementById('quantity-' + register.id);
            let modalQuantity = document.getElementById('quantity-modal-' + register.id);
            let trProduct = document.getElementById('linha-' + register.id);

            if (typeof quantityProduct[register.id] === "undefined" || currentQuantity === 0) return false;

            if (currentQuantity === 1) {
                if (!confirm(`Deseja excluir o ${register.name} da lista ?`)) return false;
            }

            if (currentQuantity > 0)
                quantityProduct[register.id] = parseInt(currentQuantity) - 1;

            if (quantityProduct[register.id] === 0)
                productAdd = productAdd.filter(object => quantityProduct[object.id] > 0)

            if (quantityProduct[register.id] === 0) {
                delete quantityProduct[register.id];
                if (trProduct !== null)
                    trProduct.outerHTML = '';
            }

            if (trQuantity !== null)
                trQuantity.innerText = quantityProduct[register.id] || 0;

            if (modalQuantity !== null)
                modalQuantity.innerText = quantityProduct[register.id] || 0;


            localStorage.setItem('products_add', JSON.stringify(productAdd));
            localStorage.setItem('quantity_product', JSON.stringify(quantityProduct));
        }

        const solicitar = () => {
            let quantityProduct = JSON.parse(localStorage.getItem('quantity_product') || '{}')
            let link = '';
            let products = [];
            for(let idProduct in quantityProduct) {
                link = document.getElementById('link-'+idProduct).value
                products.push(new Object({
                    link: link,
                    idProduct: idProduct,
                    quantity: quantityProduct[idProduct]
                }));
            }

            if (confirm('Confirmar Requisição')) {
                Api.post({
                    data: {
                        products: products
                    },
                    classe: 'request',
                    acao: 'request'
                }).then(response => {

                    if(typeof response.codigo === 'undefined'){
                        console.error(response)
                        return false
                    }
                    if (response.codigo === 1) {
                        document.getElementById('request-list').innerHTML = '';
                        document.getElementById('request-list').innerHTML += getTR(response.dados);

                        return false;
                    }

                    document.getElementById('request-list').innerHTML = `<tr>
                        <td colspan="8">${response.msg}</td>
                    </tr>`;
                    alert(response.msg);

                })

                localStorage.removeItem("products_add")
                localStorage.removeItem("quantity_product")
                location.href = 'listar-solicitacoes-aprovadas-recusadas.php';
            }

        }


        function listADD() {
            const getTR = registro => {

                let quantityProduct = JSON.parse(localStorage.getItem('quantity_product') || '{}')
                let dados = encodeURIComponent(JSON.stringify(registro));

                return `<tr id="linha-${registro.id}">
                        <td>${registro.id}</td>
                        <td>${registro.name}</td>
                        <td id="${'quantity-' + registro.id}">${quantityProduct[registro.id]}</td>
                        <td><input id="link-${registro.id}" placeholder="Insira o Link"></td>
                        <td colspan="3" style="text-align: center;">
                            <button id="qtd-mais" class="btn btn-sm btn-success btn-add" onclick="mais('${dados}')">+</button>

                            <button id="qtd-menos" class="btn btn-sm btn-danger btn-remove" onclick="menos('${dados}')">-</button>

                            <button class="btn btn-sm btn-danger" onclick="removeAddProduct('${dados}')">Remover</button>
                        </td>
                    </tr>`;
            }

            let productsList = JSON.parse(localStorage.getItem('products_add') || 'Nenhum produto adcionado!!')
            document.getElementById('list-add').innerHTML = '';

            productsList.forEach(registro => {
                document.getElementById('list-add').innerHTML += getTR(registro);
            });
            return false;

        }

        function removeAddProduct(register) {
            register = JSON.parse(decodeURIComponent(register));
            let productAdd = JSON.parse(localStorage.getItem('products_add') || '[]')
            let quantityProduct = JSON.parse(localStorage.getItem('quantity_product') || '{}')

            console.log(quantityProduct)

            if (confirm(`Confirmar exclusao do produto ${register.name} ?`)) {
                productAdd = productAdd.filter(object => object.id !== register.id)
                delete quantityProduct[register.id];
            }

            localStorage.setItem('quantity_product', JSON.stringify(quantityProduct));
            localStorage.setItem('products_add', JSON.stringify(productAdd));
            listADD();
        }

        window.onload = function () {
            listADD();
        }

    </script>

<?php require_once (__DIR__ . '/modal-add-product.php')?>
<?php require_once('footer.php');
