<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><?=$_SESSION['login']?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="./indexadm.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./area-funcionario.php">Gerenciar Funcionarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./estoque.php">Estoque</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" style="cursor: pointer" onclick="logout()">Sair</a>
                </li>
            </ul>
        </div>
    </div>
</nav>