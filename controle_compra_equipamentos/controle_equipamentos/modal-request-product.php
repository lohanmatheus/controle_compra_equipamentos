<div class="modal fade" id="modal-request-user" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true" style="display: none">
    <div class="modal-dialog  modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-light">
                <h5 class="modal-title" id="staticBackdropLabel-user">Comprar Produtos da Requisição</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="body-products-request-user" class="modal-body">
                <div class="row mt-3">
                    <div class="col-12">
                        <div id="area-request2-user" style="display: none" class="table-responsive">
                            <table class="table">
                                <thead class="text-uppercase bg-primary text-light">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">TIPO</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Quantidade</th>
                                    <th scope="col">Link</th>
                                    <th scope="col">Valor</th>
                                    <th scope="col">Nota Fiscal</th>
                                </tr>
                                </thead>
                                <tbody id="request-list-user">
                                </tbody>
                                <tfoot id="foot-request-list-user"  class="text-black-50 bg-secondary text-light">
                                    <tr id="foot-request-list-user-tr">
                                        <th scope="col">Valor Total</th>
                                        <th id="valueTotal">R$: </th>
                                        <th scope="col">Forma de pagamento</th>
                                        <th>
                                            <select class="form-select form-select-sm mb-xl-2"
                                                    aria-label=".form-select-lg example" id="select-payment-type"
                                                    onclick="selectPaymentType()">
                                                <option>Selecione</option>
                                            </select>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                            <div id="footer-search-products-user" class="modal-footer" style="display: flex;
                             justify-content: space-between; float: right; border-top: solid 0px">
                                <button type="button" class="btn btn-sm btn-danger btn-remove" data-bs-dismiss="modal">Cancelar</button>
                                <button id="qtd-mais" class="btn btn-sm btn-success btn-add"
                                        onclick="ModalListProductRequest.verificaBuyRequest()">Confirmar
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

    function ModalAddProductRequest() {

        document.getElementById('valueTotal').innerHTML = 'R$: 0';

        this.loaderRequest = () => {
            document.getElementById('request-list-user').innerHTML = `<tr>
                <td colspan="8" class="text-center">Carregando dados. Aguarde!</td>
            </tr>`;
        }

        this.trProductRequest = function (product) {

                return `<tr>
                        <td>${product.id}</td>
                        <td>${product.product_type}</td>
                        <td>${product.product_name}</td>
                        <td>${product.quantity}</td>
                        <td><input type="text" class="link-product-request" id="link-${product.id}" value=""></td>
                        <td><input type="text" class="value-product-request" id="value-${product.id}" value="0"
                                   onchange="ModalListProductRequest.getValueTotal()">
                        </td>
                        <td><input name="${product.id}" onclick="ModalListProductRequest.verificaInvoiceRequest('${product.id}')"
                                   value="" class="invoice-product-request" id="${product.id}" type="file">
                            <input type="hidden" id="id-product" value="${product.id}">
                        </td>

                    </tr>`

        }

        this.verificaInvoiceRequest = function (id) {

            const invoice = document.getElementsByName(id)[0];
            invoice.addEventListener('change', () => {
                upload_invoice(invoice.files[0], id);
            });

            const upload_invoice = (file, id) => {
                if (file) {
                    if (!['image/jpeg', 'image/png', 'application/pdf'].includes(file.type)) {
                        document.getElementsByName('invoice')[0].value = ''
                        alert("Por favor enciar arquivos em PDF, JPEG ou PNG")
                        return;
                    }
                }
                const invoices = new FormData();

                invoices.append(id, file);
                invoices.append('classe', 'request')
                invoices.append('acao', 'insertUploadBuyRequest')
                invoices.append('id', id)

                Api.upload(invoices).then(response => {
                    if (response.codigo === 1) {
                        console.log(response.msg);
                        return false;
                    }
                    alert(response.msg);
                })
            }
        }

        this.verificaBuyRequest = function () {
            let inputLinks = {};
            let inputValues = {};
            let paymentType = document.getElementById('select-payment-type').value
            let idProduct = document.getElementById('id-product').value
            let ids = '';
            let isValid = true;

            document.querySelectorAll('#id-product').forEach(id => {
                ids += '-'+id.value
            })

            document.querySelectorAll('.invoice-product-request').forEach(invoice => {
                if(!invoice.value){
                    isValid = false;
                }
            })

            document.querySelectorAll('.link-product-request').forEach(inputLink => {
                if (!inputLink.value) {
                    isValid = false;
                }

                inputLinks[inputLink.id] = inputLink.value
            })

            if (isValid === true) {
                document.querySelectorAll('.value-product-request').forEach(inputValue => {
                    if (parseFloat(inputValue.value) === 0 || inputValue.value === '') {
                        isValid = false
                    }
                    inputValues[inputValue.id] = inputValue.value
                })
            }

            if (!isValid) {
                alert("Preencha todos os campos!")
                return false;
            }
            Api.post({
                inputLinks,
                inputValues,
                paymentType,
                ids,
                idProduct,
                classe: 'request',
                acao: 'insertBuyRequest'
            }).then(response => {
                if (response.codigo === 1) {
                    alert(response.msg);
                    window.location.href = "listar-solicitacoes-realizadas.php"
                    return false;
                }

                document.getElementById('request-list-user').innerHTML = `<tr>
                        <td colspan="8">${response.msg}</td>
                    </tr>`;
            })

        }

        this.getValueTotal = function (){
            let total = 0.00;
            document.querySelectorAll('.value-product-request').forEach(input => {
                console.log('input.value', input.value)
                let currentValue = input.value || "0.00";
                total += parseFloat(currentValue)
            })

            console.log(total)
            document.getElementById('valueTotal').innerHTML = 'R$: '+total.toFixed(2);
        }


        this.listProductsRequest = function (id) {
            document.getElementById('modal-request-user').style.display = 'block';
            document.getElementById('area-request2-user').style.display = 'block';
            this.loaderRequest()
            Api.post({
                id,
                classe: 'request',
                acao: 'listRequestApproved'
            }).then(response => {
                if (response.codigo === 1) {
                    document.getElementById('request-list-user').innerHTML = '';
                    response.dados.forEach(registro => {
                        document.getElementById('request-list-user').innerHTML += this.trProductRequest(registro);
                    });
                    return false;
                }

                document.getElementById('request-list-user').innerHTML = `<tr>
                        <td colspan="8">${response.msg}</td>
                    </tr>`;
            })
        }
    }

    function selectPaymentType() {

        let verificaSelect = document.getElementById('select-payment-type').getElementsByTagName('option');
        let select = document.getElementById("select-payment-type");

        Api.post({
            classe: 'request',
            acao: 'slcPaymentType'
        }).then(response => {
            if (response.codigo === 1) {
                console.log(response.dados)
                response.dados.forEach(registro => {

                    let option = document.createElement("option");
                    option.value = registro.id;
                    option.text = registro.name;

                    if (verificaSelect.length < 6) {
                        select.appendChild(option)
                    }

                });

            }

        })
    }

    const modalRequest = document.getElementById('modal-request-user')
    const ModalListProductRequest = new ModalAddProductRequest();
</script>
