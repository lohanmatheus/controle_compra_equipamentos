<?php


class User
{
    private $dbConnect = null;
    private $parametros = null;

    public function __construct($dbConnect, $parametros)
    {
        if ($parametros)
            $this->parametros = $parametros;

        if ($dbConnect)
            $this->dbConnect = $dbConnect;
    }

    public function login()
    {
        $dataUser = (array)$this->parametros['data'];
        $login = filter_var($dataUser['login'], FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_var($dataUser['password'], FILTER_SANITIZE_SPECIAL_CHARS);

        $lista = [$login, $password];

        foreach ($lista as $linha) {
            if (empty($linha)) {
                return [
                    'codigo' => 0,
                    'msg' => 'Campos obrigatorios nao preenchidos!',
                    'dados' => []
                ];
            }
        }

        $query = "SELECT * FROM compra_equipamentos.buy.user WHERE login = '$login' and password = '$password'";
        try {
            $result = pg_query($this->dbConnect, $query);
            if (pg_affected_rows($result) <= 0) {
                return [
                    'codigo' => 0,
                    'msg' => 'Login ou senha incorretos!!',
                    'dados' => []
                ];
            }

            $row = pg_fetch_assoc($result);
            $row['register_date'] = (new \DateTime($row['register_date']))->format('d/m/Y');
            $row['birth_date'] = (new \DateTime($row['birth_date']))->format('Y-m-d');


            session_start();
            if ($row['id_access_group'] == 1) {
                $_SESSION['grupo'] = 'adm';
            } else if ($row['id_access_group'] == 2) {
                $_SESSION['grupo'] = 'funcionario';
            }
            $_SESSION['logado'] = 'ativo';
            $_SESSION['login'] = $row['login'];
            $_SESSION['id'] = $row['id'];


            return [
                'codigo' => 1,
                'msg' => 'Registro selecionado com sucesso!',
                'dados' => $row,
                'grupo' => $_SESSION['grupo']
            ];

        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage(),
                'dados' => []
            ];
        }
    }

//    ADM

    public function insertFuncionario()
    {
        $dataUser = (array)$this->parametros['data'];
        $name = filter_var($dataUser['name'], FILTER_SANITIZE_SPECIAL_CHARS);
        $login = filter_var($dataUser['login'], FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_var($dataUser['password'], FILTER_SANITIZE_SPECIAL_CHARS);
        $documento = filter_var($dataUser['documento'], FILTER_SANITIZE_SPECIAL_CHARS);
        $birthDate = filter_var($dataUser['birthDate'], FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var($dataUser['email'], FILTER_SANITIZE_SPECIAL_CHARS);
        $id_access_group = 2;


        $queryLogin = "SELECT * FROM compra_equipamentos.buy.user WHERE login = '$login' ";
        $queryDocumento = "SELECT * FROM compra_equipamentos.buy.user WHERE documento = '$documento' ";

        if ($queryLogin) {
            try {
                $resultLogin = pg_query($this->dbConnect, $queryLogin);
                if (!$resultLogin) {
                    return [
                        'codigo' => 0,
                        'msg' => 'Registro nao encontrado!',
                        'dados' => []
                    ];
                }

                if (pg_affected_rows($resultLogin) > 0) {

                    $rowLogin = pg_fetch_assoc($resultLogin);

                    if ($rowLogin['login'] == $login) {
                        return [
                            'codigo' => 0,
                            'msg' => 'Login ja existe no sistema!',
                            'dados' => []
                        ];
                        exit();
                    }
                }

            } catch (\Exception $exception) {
                return [
                    'codigo' => 0,
                    'msg' => $exception->getMessage(),
                    'dados' => []
                ];
            }
        }


        if ($queryDocumento) {
            try {
                $resultDocumento = pg_query($this->dbConnect, $queryDocumento);
                if (!$resultDocumento) {
                    return [
                        'codigo' => 0,
                        'msg' => 'Registro nao encontrado!',
                        'dados' => []
                    ];
                }

                if (pg_affected_rows($resultDocumento) > 0) {

                    $rowDocumento = pg_fetch_assoc($resultDocumento);

                    if ($rowDocumento['documento'] == $documento) {
                        return [
                            'codigo' => 0,
                            'msg' => 'CPF ja existe no sistema!',
                            'dados' => []
                        ];
                        exit();
                    }
                }

            } catch (\Exception $exception) {
                return [
                    'codigo' => 0,
                    'msg' => $exception->getMessage(),
                    'dados' => []
                ];
            }
        }


        $lista = [$name, $login, $password, $documento, $birthDate, $email];

        foreach ($lista as $linha) {
            if (empty($linha)) {
                return [
                    'codigo' => 0,
                    'msg' => 'Campos obrigatorios nao preenchidos!',
                    'dados' => []
                ];
            }
        }


        $query = "INSERT INTO
            compra_equipamentos.buy.user (name, login, password, documento, register_date, birth_date, email, id_access_group)
            VALUES ('$name', '$login', '$password', '$documento', current_date, '$birthDate', '$email', '$id_access_group' )
            ";
        try {
            $executado = pg_query($this->dbConnect, $query);
            if (!$executado) {
                return [
                    'codigo' => 0,
                    'msg' => pg_errormessage($this->dbConnect),
                    'dados' => []
                ];
            }
            return [
                'codigo' => 1,
                'msg' => 'Registro inserido com sucesso!',
                'dados' => []
            ];
        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage(),
                'dados' => []
            ];
        }
    }

    public function listFuncionario()
    {
        $query = "SELECT * FROM compra_equipamentos.buy.user WHERE id_access_group != 1 ORDER BY id DESC";

        try {
            $result = pg_query($this->dbConnect, $query);
            if (pg_affected_rows($result) <= 0) {
                return [
                    'codigo' => 0,
                    'msg' => "Nenhum registro encontrado!",
                    'dados' => []
                ];
            }

            $resultSet = [];
            while ($row = pg_fetch_assoc($result)) {
                $row['register_date'] = (new \DateTime($row['register_date']))->format('d/m/Y');
                $row['birth_date'] = (new \DateTime($row['birth_date']))->format('d/m/Y');
                $resultSet[] = $row;
            }

            return [
                'codigo' => 1,
                'msg' => 'Listado com sucesso!',
                'dados' => $resultSet
            ];


        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage()
            ];
        }
    }

    public function selectFuncionario()
    {
        $idUser = (int)$this->parametros['id'];
        if ($idUser <= 0) {
            return [
                'codigo' => 0,
                'msg' => 'Informe o id do registro a qual deseja selecionar.'
            ];
        }

        $query = "SELECT * FROM compra_equipamentos.buy.user WHERE id = '$idUser' ";
        try {
            $result = pg_query($this->dbConnect, $query);
            if (!$result) {
                return [
                    'codigo' => 0,
                    'msg' => 'Registro nao encontrado!'
                ];
            }

            $row = pg_fetch_assoc($result);
            $row['register_date'] = (new \DateTime($row['register_date']))->format('d/m/Y');
            $row['birth_date'] = (new \DateTime($row['birth_date']))->format('Y-m-d');

            return [
                'codigo' => 1,
                'msg' => 'Registro selecionado com sucesso!',
                'dados' => $row
            ];


        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage()
            ];
        }
    }

    public function updateFuncionario()
    {
        $dataUser = (array)$this->parametros['data'];
        $id = (int)filter_var($dataUser['id'], FILTER_SANITIZE_SPECIAL_CHARS);
        $name = filter_var($dataUser['name'], FILTER_SANITIZE_SPECIAL_CHARS);
        $login = filter_var($dataUser['login'], FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_var($dataUser['password'], FILTER_SANITIZE_SPECIAL_CHARS);
        $documento = filter_var($dataUser['documento'], FILTER_SANITIZE_SPECIAL_CHARS);
        $birthDate = filter_var($dataUser['birthDate'], FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var($dataUser['email'], FILTER_SANITIZE_SPECIAL_CHARS);


        $querySlc = "SELECT login, documento FROM compra_equipamentos.buy.user
                        WHERE id != '$id'";
            try {
                $resultSlc = pg_query($this->dbConnect, $querySlc);
                if (!$resultSlc) {
                    return [
                        'codigo' => 0,
                        'msg' => 'Registro nao encontrado!',
                        'dados' => []
                    ];
                }

                while ($rowSlc = pg_fetch_assoc($resultSlc)) {
                    if($login == $rowSlc['login']){
                        return [
                            'codigo' => 0,
                            'msg' => 'Login ja existe no sistema!',
                            'dados' => []
                        ];
                    } else if($documento == $rowSlc['documento']){
                        return [
                            'codigo' => 0,
                            'msg' => 'CPF ja existe no sistema!',
                            'dados' => []
                        ];
                    }
                }


            } catch (\Exception $exception) {
                return [
                    'codigo' => 0,
                    'msg' => $exception->getMessage()
                ];
            }



        if ($id <= 0) {
            return [
                'codigo' => 0,
                'msg' => 'Informe o id do registro a qual deseja alterar.',
                'dados' => []
            ];
        }

        $lista = [$name, $login, $password, $documento, $birthDate, $email];

        foreach ($lista as $linha) {
            if (empty($linha)) {
                return [
                    'codigo' => 0,
                    'msg' => 'Campos de alteração nao Recebidos!',
                    'dados' => []
                ];
            }
        }
        $campoPassword = '';
        if ($dataUser['password'] !== '******')
            $campoPassword = ", password = '$password'";

        $query = "UPDATE compra_equipamentos.buy.user 
                     SET name = '$name',
                         email = '$email',
                         birth_date = '$birthDate',
                         documento = '$documento',
                         login = '$login'
                         $campoPassword
                   WHERE id = '$id' ";

        try {
            $result = pg_query($this->dbConnect, $query);
            if (!$result) {
                return [
                    'codigo' => 0,
                    'msg' => pg_errormessage($this->dbConnect),
                    'dados' => []
                ];
            }

            return [
                'codigo' => 1,
                'msg' => 'Alterado com sucesso!',
            ];

        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage()
            ];
        }
    }

    public function removeFuncionario()
    {
        $idUser = (int)$this->parametros['id'];
        if ($idUser <= 0) {
            return [
                'codigo' => 0,
                'msg' => 'Informe o id do registro a qual deseja remover.'
            ];
        }

        $query = "DELETE FROM compra_equipamentos.buy.user WHERE id = '$idUser' ";
        try {
            $result = pg_query($this->dbConnect, $query);
            if (!$result) {
                return [
                    'codigo' => 0,
                    'msg' => pg_errormessage($this->dbConnect)
                ];
            }

            return [
                'codigo' => 1,
                'msg' => 'Registro removido com sucesso!',
                'dados' => []
            ];


        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage()
            ];
        }
    }

    public function listRequestPendentes()
    {

        $querySlcProductOfRequestPendente = "
            SELECT pr.id, TO_CHAR(pr.request_date, 'DD/MM/YYYY') as request_date,
                   prs.name AS status_name, ur.name AS user_request, pr.quantity
                FROM compra_equipamentos.buy.purchase_request AS pr
                    JOIN compra_equipamentos.buy.purchase_request_status AS prs ON pr.id_purchase_request_status = prs.id
                    JOIN compra_equipamentos.buy.user AS ur ON ur.id = pr.id_user_request
                 WHERE prs.id = 3";
        try {

            $resultProductOfRequestPendente = pg_query($this->dbConnect, $querySlcProductOfRequestPendente);
            if (pg_affected_rows($resultProductOfRequestPendente) < 1) {
                return [
                    'codigo' => 0,
                    'msg' => 'Nenhum registo encontrado!',
                    'dados' => []
                ];
            }

            $resultSetProductOfRequestPendente = [];
            while ($rowProductOfRequestPendente = pg_fetch_assoc($resultProductOfRequestPendente)) {
                $resultSetProductOfRequestPendente[] = $rowProductOfRequestPendente;
            }

            return [
                'codigo' => 1,
                'msg' => 'Registro selecionado com sucesso!',
                'dados' => $resultSetProductOfRequestPendente
            ];

        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage()
            ];
        }
    }

    public function listRequestPendentesModal()
    {
        $idPurchaseRequest = $this->parametros['id'];

        $querySlcProductOfRequestPendente = "
            SELECT pr.id, pr.request_date, prs.name AS status_name, ur.name AS user_request, por.quantity AS quantity, 
                   pt.name AS product_type, product.name AS product_name, por.link AS link
                FROM compra_equipamentos.buy.purchase_request AS pr
                    JOIN compra_equipamentos.buy.purchase_request_status AS prs ON pr.id_purchase_request_status = prs.id
                    JOIN compra_equipamentos.buy.user AS ur ON ur.id = pr.id_user_request
                    JOIN compra_equipamentos.buy.product_of_request AS por ON pr.id = por.id_purchase_request
                    JOIN compra_equipamentos.buy.product ON por.id_product = product.id
                    JOIN compra_equipamentos.buy.product_type AS pt ON product.id_product_type = pt.id            
                 WHERE id_purchase_request = '$idPurchaseRequest' AND pr.id_purchase_request_status = 3";
        try {

            $resultProductOfRequestPendente = pg_query($this->dbConnect, $querySlcProductOfRequestPendente);
            if (pg_affected_rows($resultProductOfRequestPendente) < 1) {
                return [
                    'codigo' => 0,
                    'msg' => 'Nenhum registo encontrado!',
                    'dados' => []
                ];
            }

            $resultSetProductOfRequestPendente = [];
            while ($rowProductOfRequestPendente = pg_fetch_assoc($resultProductOfRequestPendente)) {
                $resultSetProductOfRequestPendente[] = $rowProductOfRequestPendente;
            }

            return [
                'codigo' => 1,
                'msg' => 'Registro selecionado com sucesso!',
                'dados' => $resultSetProductOfRequestPendente
            ];

        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage()
            ];
        }
    }

    public function reason(){
        session_start();
        $idAdm = $_SESSION['id'];
        $reason = $this->parametros['reason'];
        $idPurchaseRequest = $this->parametros['id'];

        $queryReason = "INSERT INTO compra_equipamentos.buy.reason_cancellation (reason, id_purchase_request)
                        VALUES ('$reason', '$idPurchaseRequest')";

        try {
            pg_query($this->dbConnect, "BEGIN TRANSACTION");
            $resultReason = pg_query($this->dbConnect, $queryReason);
            if (!$resultReason) {
                return [
                    'codigo' => 0,
                    'msg' => 'Erro ao inserir o motivo de cancelamento',
                    'dados' => []
                ];
            }

            $queryAlterStatus = "UPDATE compra_equipamentos.buy.purchase_request 
                     SET id_purchase_request_status = 2,
                         verification_date_adm = current_date,
                         id_adm = '$idAdm'
                   WHERE id = '$idPurchaseRequest'";

            if (!pg_query($this->dbConnect, $queryAlterStatus)) {
                pg_query($this->dbConnect, "ROLLBACK");
                return [
                    'codigo' => 0,
                    'msg' => 'Erro ao inserir Registro!',
                    'dados' => []
                ];
            }

            pg_query($this->dbConnect, "COMMIT");

            return [
                'codigo' => 1,
                'msg' => 'Compra cancelada!',
                'dados' => []
            ];

        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage(),
                'dados' => []
            ];
        }
    }

    public function setStatusApprove()
    {
        session_start();
        $id = $_SESSION['id'];

        $idPurchaseRequest = $this->parametros['id'];

        $queryApprove = "UPDATE compra_equipamentos.buy.purchase_request 
                     SET id_purchase_request_status = 1,
                         id_adm = '$id', 
                         verification_date_adm = current_date   
                   WHERE id = '$idPurchaseRequest' ";
        try {
            pg_query($this->dbConnect, "BEGIN TRANSACTION");
            $resultApprove = pg_query($this->dbConnect, $queryApprove);
            if (pg_affected_rows($resultApprove) < 1) {
                return [
                    'codigo' => 0,
                    'msg' => 'Erro ao aprovar requisição!',
                    'dados' => []
                ];
            }

            $queryReleasePurchaseRequest = "INSERT INTO compra_equipamentos.buy.release_of_purchase_request
                                            (id_user, id_purchase_request) VALUES ('$id', '$idPurchaseRequest')";

            if (!pg_query($this->dbConnect, $queryReleasePurchaseRequest)) {
                pg_query($this->dbConnect, "ROLLBACK");
                return [
                    'codigo' => 0,
                    'msg' => 'Erro ao inserir Liberação na Requisição de Compra!',
                    'dados' => []
                ];
            }

            pg_query($this->dbConnect, "COMMIT");

            return [
                'codigo' => 1,
                'msg' => 'Requisição aprovada!',
                'dados' => []
            ];
            exit();


        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage(),
                'dados' => []
            ];
        }
    }

    public function listStock(){
        $queryStockProduct = "SELECT stock.*, product.name, pt.name AS product_type FROM compra_equipamentos.buy.stock
                                JOIN compra_equipamentos.buy.product AS product ON stock.id_product = product.id
                                JOIN compra_equipamentos.buy.product_type AS pt ON product.id_product_type = pt.id
                               WHERE quantity != 0
                                ORDER BY pt.id";

        try {
            $resultStockProduct = pg_query($this->dbConnect, $queryStockProduct);
            if (pg_affected_rows($resultStockProduct) < 1) {
                return [
                    'codigo' => 0,
                    'msg' => 'Nenhum registo encontrado!',
                    'dados' => []
                ];
            }

            $resultSetStockProduct = [];
            while ($rowStockProduct = pg_fetch_assoc($resultStockProduct)) {
                $resultSetStockProduct[] = $rowStockProduct;
            }

            return [
                'codigo' => 1,
                'msg' => 'Registro selecionado com sucesso!',
                'dados' => $resultSetStockProduct
            ];

        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage()
            ];
        }
    }

//    FUNCIONARIOS

    public function insertProduct()
    {

        $dataUser = (array)$this->parametros['data'];
        $name = filter_var($dataUser['name'], FILTER_SANITIZE_SPECIAL_CHARS);
        $description = filter_var($dataUser['description'], FILTER_SANITIZE_SPECIAL_CHARS);
        $productType = filter_var($dataUser['productType'], FILTER_SANITIZE_SPECIAL_CHARS);

        if ($productType == 'Selecione') {
            return [
                'codigo' => 0,
                'msg' => 'Selecione o tipo do produto!'
            ];
            exit();
        }
        $lista = [$name, $productType];

        foreach ($lista as $linha) {
            if (empty($linha)) {
                return [
                    'codigo' => 0,
                    'msg' => 'Campos obrigatorios nao preenchidos!'
                ];
            }
        }

        $querySlcProduct = "SELECT * FROM compra_equipamentos.buy.product WHERE name = '$name'";

        try {
            pg_query($this->dbConnect, "BEGIN TRANSACTION");
            $resultSlcProduct = pg_query($this->dbConnect, $querySlcProduct);

            if (pg_affected_rows($resultSlcProduct) > 0) {
                return [
                    'codigo' => 0,
                    'msg' => 'Produto ja existe no sistema adm precisa removelo!!',
                    'dados' => []
                ];
            }

            $queryInsert = "INSERT INTO 
            compra_equipamentos.buy.product (name, description, id_product_type)
            VALUES ('$name', '$description', '$productType')";

            if (!pg_query($this->dbConnect, $queryInsert)) {
                pg_query($this->dbConnect, "ROLLBACK");
                return [
                    'codigo' => 0,
                    'msg' => 'Falha ao inserir produto!',
                    'dados' => []
                ];
            }

            $queryIdProduct = "SELECT id FROM compra_equipamentos.buy.product WHERE name = '$name'";

            if (!pg_query($this->dbConnect, $queryIdProduct)) {
                pg_query($this->dbConnect, "ROLLBACK");
                return [
                    'codigo' => 0,
                    'msg' => 'Erro ao selecionar Id do produto para alerar stock!',
                    'dados' => []
                ];
            }

            $resultIdProduct = pg_query($this->dbConnect, $queryIdProduct);

            $rowIdProduct = pg_fetch_assoc($resultIdProduct);
            $idProduct = $rowIdProduct['id'];

            $queryInsertStock = "INSERT INTO compra_equipamentos.buy.stock (id_product, quantity)
                                    VALUES ('$idProduct', 0)";

            if (!pg_query($this->dbConnect, $queryInsertStock)) {
                pg_query($this->dbConnect, "ROLLBACK");
                return [
                    'codigo' => 0,
                    'msg' => 'Erro ao inserir produto no stock!',
                    'dados' => []
                ];
            }

            pg_query($this->dbConnect, "COMMIT");

            return [
                'codigo' => 1,
                'msg' => 'Produto inserido com sucesso!',
                'dados' => []
            ];

        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage()
            ];
        }

    }

    public function selectType()
    {

        $query = "SELECT * FROM compra_equipamentos.buy.product_type ORDER BY id";

        try {
            $result = pg_query($this->dbConnect, $query);
            if (!$result) {
                return [
                    'codigo' => 0,
                    'msg' => 'Registro nao encontrado!'
                ];
            }


            $resultSet = [];
            while ($row = pg_fetch_assoc($result)) {
                $resultSet[] = $row;
            }

            return [
                'codigo' => 1,
                'msg' => 'Registro selecionado com sucesso!',
                'dados' => $resultSet
            ];


        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage()
            ];
        }
    }

    public function searchProduct()
    {

        $dataUser = (array)$this->parametros['data'];
        $name = filter_var($dataUser['name'], FILTER_SANITIZE_SPECIAL_CHARS);

        $query = "
           SELECT product.*, product_type.name as product_type_name
             FROM compra_equipamentos.buy.product
             JOIN compra_equipamentos.buy.product_type ON product_type.id = product.id_product_type
            WHERE (product.name LIKE '%$name%' OR product_type.name LIKE '%$name%' OR product.description LIKE '%$name%')";

        try {
            $result = pg_query($this->dbConnect, $query);
            if (pg_affected_rows($result) <= 0) {
                return [
                    'codigo' => 0,
                    'msg' => 'Nenhum item encontrado!!',
                    'dados' => []
                ];
            }

            $resultSet = [];
            while ($row = pg_fetch_assoc($result)) {
                $resultSet[] = $row;
            }

            return [
                'codigo' => 1,
                'msg' => 'Listado com sucesso!',
                'dados' => $resultSet

            ];


        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage(),
                'dados' => []
            ];
        }

    }

    public function selectProduct()
    {
        $idUser = (int)$this->parametros['idUser'];

        $idProduct = (int)$this->parametros['id'];
        if ($idProduct <= 0) {
            return [
                'codigo' => 0,
                'msg' => 'Informe o id do produto a qual deseja selecionar.',
                'dados' => []
            ];
        }

        $query = "SELECT * FROM compra_equipamentos.buy.product WHERE id = '$idProduct' ";
        try {
            $result = pg_query($this->dbConnect, $query);
            if (!$result) {
                return [
                    'codigo' => 0,
                    'msg' => 'Produto nao encontrado!',
                    'dados' => []
                ];
            }

            $row = pg_fetch_assoc($result);
            $idProductType = $row['id_product_type'];

            $queryProductType = "SELECT name FROM compra_equipamentos.buy.product_type WHERE id = '$idProductType'";

            if ($queryProductType) {
                try {
                    $resultProductType = pg_query($this->dbConnect, $queryProductType);
                    if (!$resultProductType) {
                        return [
                            'codigo' => 0,
                            'msg' => 'Produto nao encontrado!',
                            'dados' => []
                        ];
                    }

                    $productType = pg_fetch_assoc($resultProductType);

                } catch (\Exception $exception) {
                    return [
                        'codigo' => 0,
                        'msg' => $exception->getMessage(),
                        'dados' => []
                    ];
                }
            }


            return [
                'codigo' => 1,
                'msg' => 'Produto selecionado com sucesso!',
                'dados' => $row,
                'productType' => $productType,
                'idUser' => $idUser
            ];

        } catch
        (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage(),
                'dados' => []
            ];
        }

    }

    public function listADDProduct()
    {
        $id = (int)$this->parametros['id'];

        $query = "SELECT * FROM compra_equipamentos.buy.product
                    WHERE id = '$id' ORDER BY id";

        try {
            $result = pg_query($this->dbConnect, $query);
            if (pg_affected_rows($result) == 0) {
                return [
                    'codigo' => 0,
                    'msg' => 'Nenhum produto adcionado!',
                    'dados' => []
                ];
            }

            $resultSet = [];
            while ($row = pg_fetch_assoc($result)) {
                $resultSet[] = $row;
            }

            return [
                'codigo' => 1,
                'msg' => 'Listado com sucesso!',
                'dados' => $resultSet
            ];


        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage()
            ];
        }
    }

    public function requestProduct()
    {
        session_start();
        $dataProduct = (array)$this->parametros['data'];
        $idUser = $_SESSION['id'];
        $products = $dataProduct['products'];

        if (count($products) < 1) {
            return [
                'codigo' => 0,
                'msg' => 'Produtos nao encontrado!',
                'dados' => []
            ];
        }

        $queryPurchaseRequest = "INSERT INTO compra_equipamentos.buy.purchase_request (request_date, id_user_request,
                                 id_purchase_request_status) VALUES (current_date, '$idUser', 3)
                                 RETURNING id ";

        try {
            pg_query($this->dbConnect, "BEGIN TRANSACTION");
            $resultPurchaseRequest = pg_query($this->dbConnect, $queryPurchaseRequest);
            if (!$resultPurchaseRequest) {
                return [
                    'codigo' => 0,
                    'msg' => 'Registro nao encontrado!',
                    'dados' => []
                ];
            }

            $rowPurchaseRequest = pg_fetch_row($resultPurchaseRequest);
            $idPurchaseRequest = $rowPurchaseRequest[0];
            $quantity = 0;

            foreach ($products as $product) {
                $quantity = $quantity + intval($product['quantity']);

                $querySlcProduct = "INSERT INTO compra_equipamentos.buy.product_of_request 
                                       (quantity, link, id_purchase_request, id_product)
                                        VALUES ('{$product['quantity']}','{$product['link']}','$idPurchaseRequest',
                                                '{$product['idProduct']}')";

                if (!pg_query($this->dbConnect, $querySlcProduct)) {
                    pg_query($this->dbConnect, "ROLLBACK");
                    return [
                        'codigo' => 0,
                        'msg' => 'Erro ao inserir Registro!',
                        'dados' => []
                    ];
                }

            }

            $queryUpdateQuantity = "UPDATE compra_equipamentos.buy.purchase_request
                                    SET quantity = '$quantity'
                                    WHERE id = '$idPurchaseRequest'";


            if (!pg_query($this->dbConnect, $queryUpdateQuantity)) {
                pg_query($this->dbConnect, "ROLLBACK");
                return [
                    'codigo' => 0,
                    'msg' => 'Erro ao inserir Quantidade na Requisição!',
                    'dados' => []
                ];
            }

            pg_query($this->dbConnect, "COMMIT");

            return [
                'codigo' => 1,
                'msg' => 'Registro inserido com sucesso!',
                'dados' => []
            ];

        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage()
            ];
        }
    }

    public function listRequest()
    {
        session_start();
        $idUser = $_SESSION['id'];


        $querySlcIdPurchaseRequest = "
            SELECT pr.id,TO_CHAR(pr.request_date, 'DD/MM/YYYY') as request_date, prs.name AS status_name
              FROM compra_equipamentos.buy.purchase_request AS pr
              JOIN compra_equipamentos.buy.purchase_request_status AS prs ON pr.id_purchase_request_status = prs.id
             WHERE id_user_request = '$idUser' and id_purchase_request_status != 4";

        try {

            $resultIdPurchaseRequest = pg_query($this->dbConnect, $querySlcIdPurchaseRequest);
            if (pg_affected_rows($resultIdPurchaseRequest) < 1) {
                return [
                    'codigo' => 0,
                    'msg' => 'Nenhum registo encontrado!',
                    'dados' => []
                ];
            }

            $resultSetPurchaseRequest = [];
            while ($rowIdPurchaseRequest = pg_fetch_assoc($resultIdPurchaseRequest)) {
                $queryQuantityProduct = "
                    SELECT count(id) AS quantidade 
                      FROM compra_equipamentos.buy.product_of_request
                     WHERE id_purchase_request = {$rowIdPurchaseRequest['id']}";
                $resultRowQuantityProduct = pg_query($this->dbConnect, $queryQuantityProduct);
                $rowQuantityProduct = pg_fetch_assoc($resultRowQuantityProduct);
                $rowIdPurchaseRequest['quantity_products'] = $rowQuantityProduct['quantidade'];
                $resultSetPurchaseRequest[] = $rowIdPurchaseRequest;
            }

            return [
                'codigo' => 1,
                'msg' => 'Registro selecionado com sucesso!',
                'dados' => $resultSetPurchaseRequest
            ];

        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage()
            ];
        }
    }

    public function listRequestApproved()
    {
        $idPurchaseRequest = $this->parametros['id'];

        $querySlcProductOfRequestApproved = "
            SELECT por.id, product.name AS product_name, por.quantity, por.link, pt.name AS product_type,
                   por.value AS value
                FROM compra_equipamentos.buy.product_of_request AS por                
                JOIN compra_equipamentos.buy.purchase_request AS pr ON por.id_purchase_request = pr.id 
                JOIN compra_equipamentos.buy.product ON por.id_product = product.id
                JOIN compra_equipamentos.buy.product_type AS pt ON product.id_product_type = pt.id
             WHERE id_purchase_request = '$idPurchaseRequest' AND pr.id_purchase_request_status = 1";

        try {

            $resultProductOfRequestApproved = pg_query($this->dbConnect, $querySlcProductOfRequestApproved);
            if (pg_affected_rows($resultProductOfRequestApproved) < 1) {
                return [
                    'codigo' => 0,
                    'msg' => 'Nenhum registo encontrado!',
                    'dados' => []
                ];
            }

            $resultSetProductOfRequestApproved = [];
            while ($rowProductOfRequestApproved = pg_fetch_assoc($resultProductOfRequestApproved)) {
                $resultSetProductOfRequestApproved[] = $rowProductOfRequestApproved;
            }

            return [
                'codigo' => 1,
                'msg' => 'Registro selecionado com sucesso!',
                'dados' => $resultSetProductOfRequestApproved
            ];

        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage()
            ];
        }
    }

    public function getReason(){
        $idPurchaseRequest = $this->parametros['id'];

        $queryReason = "SELECT rc.reason, TO_CHAR(pr.verification_date_adm, 'DD/MM/YYYY') AS verification_date_adm, ur.name
                        FROM compra_equipamentos.buy.reason_cancellation AS rc
                            JOIN compra_equipamentos.buy.purchase_request AS pr ON rc.id_purchase_request = pr.id
                            JOIN compra_equipamentos.buy.user AS ur ON pr.id_adm = ur.id                        
                           WHERE rc.id_purchase_request = '$idPurchaseRequest'";

        try {
            $resultReason = pg_query($this->dbConnect, $queryReason);
            if (pg_affected_rows($resultReason) < 1) {
                return [
                    'codigo' => 0,
                    'msg' => 'Registro nao encontrado!',
                    'dados' => []
                ];
            }

            $row = pg_fetch_assoc($resultReason);

            return [
                'codigo' => 1,
                'msg' => 'Registro selecionado com sucesso!',
                'dados' => $row
            ];


        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage()
            ];
        }
    }

    public function removeRequest()
    {

        $idPurchaseRequest = (int)$this->parametros['id'];

        if (!$idPurchaseRequest) {
            return [
                'codigo' => 0,
                'msg' => 'Informe o id do registro a qual deseja remover.',
                'dados' => []
            ];
        }

        $queryProductRequest = "DELETE FROM compra_equipamentos.buy.product_of_request
                    WHERE id_purchase_request = '$idPurchaseRequest'";
        try {
            pg_query($this->dbConnect, "BEGIN TRANSACTION");
            $resultProductRequest = pg_query($this->dbConnect, $queryProductRequest);
            if (!$resultProductRequest) {
                return [
                    'codigo' => 0,
                    'msg' => 'Erro ao remover Produto da Requisição',
                    'dados' => []
                ];
            }

            $queryReasonCancellation = "DELETE FROM compra_equipamentos.buy.reason_cancellation
                    WHERE id_purchase_request = '$idPurchaseRequest'";

                $resultReasonCancellation = pg_query($this->dbConnect, $queryReasonCancellation);
                if (!$resultReasonCancellation) {
                    pg_query($this->dbConnect, "ROLLBACK");
                    return [
                        'codigo' => 0,
                        'msg' => 'Erro ao remover Motivo do Cancelamento',
                        'dados' => []
                    ];
                }

                $queryPurchaseRequest = "DELETE FROM compra_equipamentos.buy.purchase_request
                                      WHERE id = '$idPurchaseRequest'";

                if (!pg_query($this->dbConnect, $queryPurchaseRequest)) {
                    pg_query($this->dbConnect, "ROLLBACK");
                    return [
                        'codigo' => 0,
                        'msg' => 'Erro ao Remover Requisição de Compra!',
                        'dados' => []
                    ];
                }

                pg_query($this->dbConnect, "COMMIT");

                return [
                    'codigo' => 1,
                    'msg' => 'Registro Removido com sucesso!',
                    'dados' => []
                ];

            } catch (\Exception $exception) {
                return [
                    'codigo' => 0,
                    'msg' => $exception->getMessage()
                ];
            }

    }

    public function slcPaymentType()
    {


        $query = "SELECT * FROM compra_equipamentos.buy.payment_type ORDER BY id";

        try {
            $result = pg_query($this->dbConnect, $query);
            if (!$result) {
                return [
                    'codigo' => 0,
                    'msg' => 'Registro nao encontrado!',
                    'dados' => []
                ];
            }


            $resultSet = [];
            while ($row = pg_fetch_assoc($result)) {
                $resultSet[] = $row;
            }

            return [
                'codigo' => 1,
                'msg' => 'Registro selecionado com sucesso!',
                'dados' => $resultSet
            ];


        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage()
            ];
        }
    }

    public function listRequestBuy()
    {
        session_start();
        $idUser = $_SESSION['id'];

        $querySlcIdPurchaseRequestBuy = "
            SELECT pr.*, prs.name AS status, ur.name AS name_user FROM compra_equipamentos.buy.purchase_request AS pr
              JOIN compra_equipamentos.buy.purchase_request_status AS prs ON pr.id_purchase_request_status = prs.id
              JOIN compra_equipamentos.buy.user AS ur ON pr.id_adm = ur.id
             WHERE id_user_request = '$idUser' and id_purchase_request_status = 4";

        try {

            $resultIdPurchaseRequest = pg_query($this->dbConnect, $querySlcIdPurchaseRequestBuy);
            if (pg_affected_rows($resultIdPurchaseRequest) < 1) {
                return [
                    'codigo' => 0,
                    'msg' => 'Nenhum registo encontrado!',
                    'dados' => []
                ];
            }

            $resultSetPurchaseRequestBuy = [];
            while ($rowIdPurchaseRequest = pg_fetch_assoc($resultIdPurchaseRequest)) {
                $queryQuantityProduct = "
                    SELECT count(id) AS quantidade 
                      FROM compra_equipamentos.buy.product_of_request
                     WHERE id_purchase_request = {$rowIdPurchaseRequest['id']}";
                $resultRowQuantityProduct = pg_query($this->dbConnect, $queryQuantityProduct);
                $rowQuantityProduct = pg_fetch_assoc($resultRowQuantityProduct);
                $rowIdPurchaseRequest['quantity_products'] = $rowQuantityProduct['quantidade'];
                $resultSetPurchaseRequestBuy[] = $rowIdPurchaseRequest;
            }

            return [
                'codigo' => 1,
                'msg' => 'Registro selecionado com sucesso!',
                'dados' => $resultSetPurchaseRequestBuy
            ];

        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage()
            ];
        }
    }

    public function insertInvoiceBuyRequest(){

       $id = (int)$this->parametros['id'];

        if (isset($_FILES[$id])) {

            $extension = pathinfo($_FILES[$id]['name'], PATHINFO_EXTENSION);

            $new_name = $id . '.' . $extension;

            move_uploaded_file($_FILES[$id]['tmp_name'], 'invoices/' . $new_name);

            $data = 'invoices/'.$new_name;

            $queryInsertInvoice = "UPDATE compra_equipamentos.buy.product_of_request 
                     SET invoice = '$data' WHERE id = '$id' ";

            try {

                $resultInvoice = pg_query($this->dbConnect, $queryInsertInvoice);
                if (!$resultInvoice) {
                    return [
                        'codigo' => 0,
                        'msg' => pg_errormessage($this->dbConnect),
                        'dados' => []
                    ];
                }

                return [
                    'codigo' => 1,
                    'msg' => 'Nota Fiscal inserida com sucesso!',
                    'dados' => []
                ];

            } catch (\Exception $exception) {
                return [
                    'codigo' => 0,
                    'msg' => $exception->getMessage()
                ];
            }

        }
    }

    public function insertBuyRequest()
    {

        $links = $this->parametros['inputLinks'];
        $values = $this->parametros['inputValues'];
        $paymentType = $this->parametros['paymentType'];
        $ids = $this->parametros['ids'];
        $idProduct = $this->parametros['idProduct'];
        $newIds = explode('-', $ids);
        unset($newIds[0]);
        $newValues = str_replace(',', '.', $values,);

        if ($paymentType === 'Selecione') {
            return [
                'codigo' => 0,
                'msg' => 'Selecione o tipo do produto!',
                'dados' => []
            ];
        }

        $lista = [$links, $newValues];

        foreach ($lista as $linha) {
            if (empty($linha)) {
                return [
                    'codigo' => 0,
                    'msg' => 'Campos obrigatorios nao preenchidos!',
                    'dados' => []
                ];
            }
        }

        foreach ($newIds as $newId) {
            $querySlcIdPurchaseRequest = "SELECT por.id_purchase_request, stock.quantity AS stock_quantity,
                                        por.quantity AS quantity_request, product.id
                                        FROM compra_equipamentos.buy.product_of_request AS por
                                        JOIN compra_equipamentos.buy.product ON por.id_product = product.id
                                        JOIN compra_equipamentos.buy.stock ON product.id = stock.id_product
                                        WHERE por.id = '$newId'";

            $quantityStock = 0;

            pg_query($this->dbConnect, "BEGIN TRANSACTION");
            $resultIdPurchaseRequest = pg_query($this->dbConnect, $querySlcIdPurchaseRequest);

            if (pg_affected_rows($resultIdPurchaseRequest) < 1) {
                return [
                    'codigo' => 0,
                    'msg' => 'ID da requisição nao encontrado!',
                    'dados' => []
                ];
            }

            $rowIdPurchaseRequest = pg_fetch_row($resultIdPurchaseRequest);
            $idPurchaseRequest = $rowIdPurchaseRequest[0];
            $productIdStock = $rowIdPurchaseRequest[3];
            $quantityStock = $rowIdPurchaseRequest[1] + $rowIdPurchaseRequest[2];

            $queryUpdateStock = "UPDATE compra_equipamentos.buy.stock
                                            SET quantity = '$quantityStock'
                                            WHERE id_product = '$productIdStock'";

            if (!pg_query($this->dbConnect, $queryUpdateStock)) {
                pg_query($this->dbConnect, "ROLLBACK");
                return [
                    'codigo' => 0,
                    'msg' => 'Falha ao alterar stock!',
                    'dados' => []
                ];
            }
        }
            try {
                $querySlcReleasePurchaseRequest = "SELECT rpq.id FROM compra_equipamentos.buy.purchase_request AS pr
                                            JOIN compra_equipamentos.buy.release_of_purchase_request AS rpq ON pr.id = rpq.id_purchase_request
                                            JOIN compra_equipamentos.buy.release_of_purchase_request AS por on pr.id = por.id_purchase_request
                                                WHERE rpq.id_purchase_request = '$idPurchaseRequest'";

                $resultReleasePurchaseRequest = pg_query($this->dbConnect, $querySlcReleasePurchaseRequest);
                if (pg_affected_rows($resultReleasePurchaseRequest) < 1) {
                    pg_query($this->dbConnect, "ROLLBACK");
                    return [
                        'codigo' => 0,
                        'msg' => 'ID da liberação da compra nao encontrado!',
                        'dados' => []
                    ];
                }

                $rowIdReleasePurchaseRequest = pg_fetch_row($resultReleasePurchaseRequest);
                $idReleasePurchaseRequest = $rowIdReleasePurchaseRequest[0];

                $queryInsertBuyOfRequest = "INSERT INTO compra_equipamentos.buy.buy_of_request 
                                        (id_release_of_purchase_request, id_payment_type, buy_date)
                                        VALUES ('$idReleasePurchaseRequest', '$paymentType', current_date)";

                if (!pg_query($this->dbConnect, $queryInsertBuyOfRequest)) {
                    pg_query($this->dbConnect, "ROLLBACK");
                    return [
                        'codigo' => 0,
                        'msg' => 'Falha ao inserir compra da requisição no banco!',
                        'dados' => []
                    ];
                }

                foreach (array_combine($links, $newIds) as $link => $id) {

                    $queryInsertLinkProduct = "UPDATE compra_equipamentos.buy.product_of_request
                                            SET link = '$link'
                                            WHERE id = '$id'";

                    if (!pg_query($this->dbConnect, $queryInsertLinkProduct)) {
                        pg_query($this->dbConnect, "ROLLBACK");
                        return [
                            'codigo' => 0,
                            'msg' => 'Erro ao inserir Link!',
                            'dados' => []
                        ];
                    }
                }
                foreach (array_combine($newValues, $newIds) as $value => $id) {

                    $queryInsertValueProduct = "UPDATE compra_equipamentos.buy.product_of_request
                                            SET value = '$value'
                                            WHERE id = '$id'";

                    if (!pg_query($this->dbConnect, $queryInsertValueProduct)) {
                        pg_query($this->dbConnect, "ROLLBACK");
                        return [
                            'codigo' => 0,
                            'msg' => 'Erro ao inserir Link!',
                            'dados' => []
                        ];
                    }
                }

                $queryAlterStatus = "UPDATE compra_equipamentos.buy.purchase_request
                                            SET id_purchase_request_status = 4,
                                                purchase_date = current_date
                                            WHERE id = '$idPurchaseRequest'";

                if (!pg_query($this->dbConnect, $queryAlterStatus)) {
                    pg_query($this->dbConnect, "ROLLBACK");
                    return [
                        'codigo' => 0,
                        'msg' => 'Erro ao Alterar status da requisição!',
                        'dados' => []
                    ];
                }

                pg_query($this->dbConnect, "COMMIT");

                return [
                    'codigo' => 1,
                    'msg' => 'Compra inserido com sucesso!',
                    'dados' => []
                ];

            } catch (\Exception $exception) {
                return [
                    'codigo' => 0,
                    'msg' => $exception->getMessage(),
                    'dados' => []
                ];
            }

    }

    public function listProductsComprados(){

        $idPurchase = $this->parametros['idPurchase'];

        $querySlcProducts = "SELECT product.id, pt.name AS product_type, product.name, por.quantity, por.value,
                                    TO_CHAR(br.buy_date, 'DD/MM/YYYY') AS date, por.invoice AS invoice
                                    FROM compra_equipamentos.buy.purchase_request AS pr
                                            JOIN compra_equipamentos.buy.product_of_request AS por ON pr.id = por.id_purchase_request
                                            JOIN compra_equipamentos.buy.product AS product ON por.id_product = product.id
                                            JOIN compra_equipamentos.buy.product_type AS pt ON product.id_product_type = pt.id
                                            JOIN compra_equipamentos.buy.release_of_purchase_request AS rpq ON pr.id = rpq.id_purchase_request
                                            JOIN compra_equipamentos.buy.buy_of_request AS br ON rpq.id = br.id_release_of_purchase_request
                                        WHERE pr.id = '$idPurchase' and pr.id_purchase_request_status = 4";

        try {

            $result = pg_query($this->dbConnect, $querySlcProducts);
            if (pg_affected_rows($result) < 1) {
                return [
                    'codigo' => 0,
                    'msg' => 'Nenhum registo encontrado!',
                    'dados' => []
                ];
            }

            $resultSetProducts = [];
            $valueTotal = 0;
            while ($rowProducts = pg_fetch_assoc($result)) {
                $rowProducts['value'] = str_replace('.', ',', $rowProducts['value'], );
                $valueTotal = $valueTotal + floatval($rowProducts['value']);
                $resultSetProducts[] = $rowProducts;
            }
            $valueTotal = number_format($valueTotal, 2, ',', '');

            return [
                'codigo' => 1,
                'msg' => 'Registro selecionado com sucesso!',
                'dados' => $resultSetProducts,
                'valueTotal' => $valueTotal
            ];

        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage()
            ];
        }
    }

}
