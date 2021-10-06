<div class="modal fade" id="modal-reason" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-light">
                <h5 class="modal-title">Requesiçao Cancelada</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="body-reason" class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form method="post">
                            <div class="form-group col-md-10 mb-1">
                                <label for="view-reason"><b>Motivo:</b></label>
                                <textarea class="form-control" id="view-reason" rows="7" disabled></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div id="footer-view-reason" class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close">Voltar</button>
            </div>

        </div>
    </div>
</div>


<script>
    let idPurchaseRequestReason = ''

    function viewReason() {

        this.loaderReason = () => {
            document.getElementById('view-reason').innerHTML = 'Carregando dados. Aguarde!';
        }
        this.listReason = function (id) {

            idPurchaseRequestReason = id;
            document.getElementById('modal-reason').style.display = 'block';
            document.getElementById('body-reason').style.display = 'block';

            this.loaderReason()
            Api.post({
                id,
                classe: 'request',
                acao: 'getReason'
            }).then(response => {
                if (response.codigo === 1) {
                        document.getElementById('view-reason').innerHTML = `${response.dados.reason}




Administrador: ${response.dados.name}
Data: ${response.dados.verification_date_adm}`;
                    return false;
                }

                document.getElementById('view-reason').innerHTML = response.msg;

            })
        }
    }

    const modalReason = document.getElementById('modal-reason')
    const ModalListReason = new viewReason();

</script>