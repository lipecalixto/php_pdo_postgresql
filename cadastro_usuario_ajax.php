<?php
include_once ('conexao.php');

if($_GET){
    $id  = $_GET['id_contato'];

    $qry= " SELECT
                pessoas.id,
                pessoas.nome,
                TO_CHAR(pessoas.dt_insercao, 'DD/MM/YYYY') AS dt_insercao,
                email.contato,
                endereco.id AS id_endereco,
                endereco.endereco
            FROM
                pessoas
                    LEFT JOIN 
                email ON (pessoas.id = email.id_pessoa)
                    LEFT JOIN
                endereco ON (pessoas.id = endereco.id_pessoa)
            WHERE
                pessoas.id = :id
         
    ";
    $result = $pdo->prepare($qry);

    $result->bindValue(':id',$id,PDO::PARAM_INT);
    $result->execute();
  
    $row = $result->fetch();

    if ($result->rowCount() < 1){
        $output = array( 
            "status" => "warning", 
            "mensagem" => "Contato com esse ID não cadastrado"
        ); 
        echo json_encode($output, JSON_FORCE_OBJECT);
        return;
    }

    $msg    ="
                ID pessoa = {$row['id']} <br>
                Nome = {$row['nome']} <br>
                Data de cadastro = {$row['dt_insercao']}<br>
                Contato = {$row['contato']} <br>
                Id Endereco = {$row['id_endereco']} <br>
                Endereco = {$row['endereco']} <br>
            ";

    $output = array( 
        "status" => "success", 
        "mensagem" => $msg
    ); 
    
    echo json_encode($output, JSON_FORCE_OBJECT);
    return;
}

if ($_POST){
    $nome       = addslashes($_POST['fNome']);
    $email      = ((!empty($_POST['fEmail']))? addslashes($_POST['fEmail']) : '');
    $endereco   = ((!empty($_POST['fEndereco']))? addslashes($_POST['fEndereco']) : '');
 
    $data       = date("Y-m-d"); 
    $qry_insert ="  INSERT INTO pessoas 
                    (
                        nome,dt_insercao
                    )
                    VALUES
                    (
                        :nome,
                        :data
                    )
                ";
    $result_insert = $pdo->prepare($qry_insert);

    $result_insert->bindValue(':nome',$nome,PDO::PARAM_STR);
    $result_insert->bindValue(':data',$data,PDO::PARAM_STR);

    $result_insert->execute();

    if(!$result_insert) {
        $output = array( 
            "status" => "error", 
            "mensagem" => "Não foi possivel cadastrar."
        ); 
        echo json_encode($output, JSON_FORCE_OBJECT);
        return;
    }

    $id_pessoa = $pdo->lastInsertId();
  
    if (!empty($email)){
        $qry_insert ="  INSERT INTO email 
                        (
                            id_pessoa,contato
                        )
                        VALUES
                        (
                            :id_pessoa,
                            :email
                        )
                    ";
        $result_insert = $pdo->prepare($qry_insert);

        $result_insert->bindValue(':id_pessoa',$id_pessoa,PDO::PARAM_INT);
        $result_insert->bindValue(':email',$email,PDO::PARAM_STR);
    
        $result_insert->execute();

        if(!$result_insert) {
            $output = array( 
                "status" => "error", 
                "mensagem" => "Não foi possivel cadastrar."
            ); 
            echo json_encode($output, JSON_FORCE_OBJECT);
            return;
        }
    }
    if (!empty($endereco)){
        $qry_insert ="  INSERT INTO endereco 
                        (
                            id_pessoa,endereco
                        )
                        VALUES
                        (
                            :id_pessoa,
                            :endereco
                        )
                    ";

        $result_insert = $pdo->prepare($qry_insert);

        $result_insert->bindValue(':id_pessoa',$id_pessoa,PDO::PARAM_INT);
        $result_insert->bindValue(':endereco',$endereco,PDO::PARAM_STR);
    
        $result_insert->execute();

        if(!$result_insert) {
            $output = array( 
                "status" => "error", 
                "mensagem" => "Não foi possivel cadastrar."
            ); 
            echo json_encode($output, JSON_FORCE_OBJECT);
            return;
        }
    }

    $output = array( 
        "status" => "success", 
        "mensagem" => "Cadastrado com sucesso!"
    ); 
    
    echo json_encode($output, JSON_FORCE_OBJECT);
    return;
}

?>