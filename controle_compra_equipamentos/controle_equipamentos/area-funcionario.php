<?php

require_once(__DIR__ . '/header-adm.php');
pageHeader('Funcionarios');
?>

<body>
<section class="container">
    <h1 id="title">Gerenciar Funcionarios</h1>
    <div id="insert-container" style="display: none;">
        <button type="button" class="btn btn-sm btn-close" onclick="voltarScreen()"></button>
        <form method="post" id='insert-form'>
            <div class="form-group col-md-3 mb-1">
                <input id="id-user" value="" type="hidden">
                <label for="name">Nome Completo</label>
                <input id="name" class="form-control" value="" placeholder="Insira o nome Completo" required>
            </div>

            <div class="form-group col-md-3 mb-1">
                <label for="login">Login</label>
                <input id="login" class="form-control" value="" placeholder="Insira o login" required>
            </div>

            <div class="form-group col-md-3 mb-1">
                <label for="password">Senha:</label>
                <input id="password" class="form-control" value="" placeholder="Insira uma senha" type="password" required>
            </div>

            <div class="form-group col-md-3 mb-1">
                <label for="documento">CPF:</label>
                <input type="text" class="form-control" name="documento" id="documento" minlength="14" maxlength="14" onkeyup="setMaskCPF(this)">
            </div>

            <div class="form-group col-md-3 mb-1">
                <label for="birthDate">Data de Nascimento:</label>
                <input id="birthDate" class="form-control" type="date" required>
            </div>

            <div class="form-group col-md-3 mb-1">
                <label for="email">E-mail:</label>
                <input id="email" class="form-control" value="" placeholder="Seu E-mail">
            </div>

            <div>
                 <button type="button" class="btn btn-sm btn-secondary" onclick="voltarScreen()">Cancelar</button>
                 <button type="button" class="btn btn-sm btn-primary" onclick="verificaCadastro();">Salvar</button>
            </div>

        </form>
    </div>

    <div id="list-container" style="display: block;">
        <div>
            <button type="button" class="btn btn-sm btn-primary" onclick="setVal('id-user',  '');insertScreen();resetForm()">
                Novo Funcionario
            </button>
            <button type="button" class="btn btn-sm btn-primary" id="button-listar-funcionario" onclick="listScreen();">
                Listar Funcionarios
            </button>
        </div>
    </div>
    <br/>
    <div class="card-body p-0">
        <div class="table-responsive" id="area-menu2" style="display: none">
            <table class="table table-hover table-striped">
                <thead class="bg-dark text-light text-uppercase">
                <tr>
                    <th>ID</th>
                    <th>Data Cad.</th>
                    <th>Nome</th>
                    <th>Login</th>
                    <th>Email</th>
                    <th>CPF</th>
                    <th>Data Nasc.</th>
                    <th colspan="2">OPCOES</th>
                </tr>
                </thead>
                <tbody id="result-list">
                </tbody>
            </table>
        </div>
    </div>

</section>

<script>

    function setMaskCPF(element) {
        element.value = element.value.replace(/\D/g, "").replace(/(\d{3})(\d)/, "$1.$2").replace(/(\d{3})(\d)/, "$1.$2").replace(/(\d{3})(\d{1,2})$/, "$1-$2")
    }

    function setVal(id, value) {
        document.getElementById(id).value = value;
    }

    const getVal = selector => document.querySelector(selector).value;

    function voltarScreen() {
        document.getElementById('area-menu2').style.display = 'block';
        document.getElementById('insert-container').style.display = 'none';
        document.getElementById('insert-container').style.display = 'none';
        document.getElementById('title').innerHTML = 'Gerenciar Funcionarios';
        document.getElementById('list-container').style.display = 'block';
        document.getElementById('button-listar-funcionario').innerHTML = 'Ocultar Funcionarios'
        listFuncionario()

    }

    function listScreen() {
        document.getElementById('area-menu2').style.display = 'block';
        document.getElementById('insert-container').style.display = 'none';
        document.getElementById('title').innerHTML = 'Gerenciar Funcionarios';
        document.getElementById('list-container').style.display = 'block';
        let listarOcultarFuncionarios = document.getElementById('button-listar-funcionario').innerText
        if (listarOcultarFuncionarios === 'Listar Funcionarios') {
            document.getElementById('button-listar-funcionario').innerHTML = 'Ocultar Funcionarios'
            document.getElementById('title').innerHTML = 'Funcionarios Cadastrados'
            listFuncionario();
        } else {
            document.getElementById('button-listar-funcionario').innerHTML = 'Listar Funcionarios'
            document.getElementById('title').innerHTML = 'Gerenciar Funcionarios'
            document.getElementById('area-menu2').style.display = 'none';
        }

    }

    function resetForm() {
        document.getElementById('insert-form').reset();
    }

    function insertScreen(update = false) {
        document.getElementById('area-menu2').style.display = 'none';
        document.getElementById('list-container').style.display = 'none';
        document.getElementById('title').innerHTML = (update ? 'Alterar' : 'Cadastrar') + ' Funcionario';
        document.getElementById('insert-container').style.display = 'block';
    }

    const verificaCadastro = function () {
        if(confirm("Todos os dados estao corretos ?")) {
            let name = document.getElementById('name').value
            let login = document.getElementById('login').value
            let password = document.getElementById('password').value
            let documento = document.getElementById('documento').value
            let birthDate = document.getElementById('birthDate').value
            let email = document.getElementById('email').value

            if (name === '') {
                alert('Insira o name!')
                return
            }
            if (login === '') {
                alert('Insira o Login!')
                return
            }
            if (password === '') {
                alert('Insira a senha')
                return
            }
            if (documento === '') {
                alert('Insira o CPF!')
                return
            }
            if (birthDate === '') {
                alert('Insira a Data de nascimento!')
                return
            }
            if (email === '') {
                alert('Insira o Email!')
                return
            }
            sendCadastro(name, login, password, documento, birthDate, email);
        }
    }

    function sendCadastro(name, login, password, documento, birthDate, email) {
        const params = {
            data: {
                name: name,
                login: login,
                password: password,
                documento: documento,
                birthDate: birthDate,
                email: email
            },
            classe: 'user',
            acao: 'inserir',
        }
        const id = getVal('#id-user')
        if (id !== '') {
            if (!confirm(`Confirmar alteracao do id ${id} ?`))
                return;

            params['data']['id'] = parseInt(id);
            params['acao'] = 'update';
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
                voltarScreen();
                return;
            }
        })
    }

    function listFuncionario() {
        const getTR = registro => {
            return `<tr>
                    <td>${registro.id}</td>
                    <td>${registro.register_date}</td>
                    <td>${registro.name}</td>
                    <td>${registro.login}</td>
                    <td>${registro.email}</td>
                    <td>${registro.documento}</td>
                    <td>${registro.birth_date}</td>
                    <td><button type="button" class="btn btn-sm btn-warning" onclick="alterar(${registro.id})">Alterar</button></td>
                    <td><button type="button" class="btn btn-sm btn-danger" onclick="remover(${registro.id})">Excluir</button></td>
                </tr>`;
        }

        const getLoaginTr = function () {
            return `<tr>
                    <td colspan="8" style="text-align: center;" ">Carregando dados. Aguarde!</td>
                </tr>`;
        }

        let bodyRequest = JSON.stringify({
            classe: 'user',
            acao: 'listar'
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
        document.getElementById('result-list').innerHTML = getLoaginTr();
        fetch(myRequest).then(function (response) {
            return response.json();
        }).then(response => {
            if (response.codigo === 1) {
                document.getElementById('result-list').innerHTML = '';
                response.dados.forEach(registro => {
                    document.getElementById('result-list').innerHTML += getTR(registro);
                });
                return false;
            }

            document.getElementById('result-list').innerHTML = `<tr>
                        <td colspan="8">${response.msg}</td>
                    </tr>`;
        })
    }

    const alterar = id => {
        let retorno = confirm(`Confirmar alteracao do id ${id} ?`)
        if (retorno == true) {
            let bodyRequest = JSON.stringify({
                classe: 'user',
                acao: 'select',
                id: id
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
                if (response.codigo === 0) {
                    alert(response.msg)
                } else if (response.codigo === 1) {
                    setVal('name', response.dados.name)
                    setVal('login', response.dados.login)
                    setVal('password', '******')
                    setVal('documento', response.dados.documento)
                    setVal('birthDate', response.dados.birth_date)
                    setVal('email', response.dados.email)
                    setVal('id-user', response.dados.id)
                    insertScreen(true)
                    return false;
                }
                alert(response.msg);

            })
            listScreen()
        }
    }

    function remover(id) {
        if (id === '') {
            alert("Informe o ID")
            return;
        }

        if (!confirm(`Confirmar exclusao do registro de id ${id} ?`))
            return;


        let configRequest = {
            method: 'POST',
            cache: 'default',
            body: JSON.stringify({
                id,
                classe: 'user',
                acao: 'remove'
            }),
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
                voltarScreen();
            }

        })
    }

    window.onload = function () {
        listScreen();
        listFuncionario();
    }

</script>

<?php require_once('footer.php');