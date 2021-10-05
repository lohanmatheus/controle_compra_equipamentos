
<?php require_once (__DIR__ . './header.php');
pageHeader('ACORD');
?>
<br><br>
<div class="table-responsive">
    <table class="table table-hover ">
        <thead class="bg-dark text-light text-uppercase">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Data</th>
            <th scope="col">Quantidade</th>
            <th scope="col">Status</th>
            <th scope="col"  style="text-align: center;">Opcoes</th>
        </tr>
        </thead>
        <tbody id="list-request">

        <tr>
            <th scope="col">ID</th>
            <th scope="col">Data</th>
            <th scope="col">Quantidade</th>
            <th scope="col">Status</th>
            <th scope="col"  style="text-align: center;">Opcoes</th>
        </tr>
        <tr>
            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Accordion Item #1
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                        </div>
                    </div>
                </div>
            </div>
        </tr>
        </tbody>
    </table>
</div>

<?php require_once (__DIR__ . './footer.php');