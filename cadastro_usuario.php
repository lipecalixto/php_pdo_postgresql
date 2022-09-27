<?php
    include_once ('conexao.php');

    $results_cad = '';

    //query para mostrar usuarios cadastrados
    $qry= " SELECT 
                pessoas.nome,
                TO_CHAR(pessoas.dt_insercao, 'DD/MM/YYYY') AS dt_insercao,
                email.contato,
                endereco.endereco
            FROM
                pessoas
                    LEFT JOIN 
                email ON (pessoas.id = email.id_pessoa)
                    LEFT JOIN
                endereco ON (pessoas.id = endereco.id_pessoa)
            ORDER BY 
                pessoas.dt_insercao,
                pessoas.id
    ";
    foreach($pdo->query($qry) as $row){
        $results_cad .="<tr>
                            <td> {$row['nome']} </td>
                            <td>".( ( !empty($row['contato']))?$row['contato'] : '-')."</td>
                            <td>".( ( !empty($row['endereco']))?$row['endereco'] : '-')."</td>
                            <td> {$row['dt_insercao']}</td>
                        </tr>";
    }

?>

<head>
    <title> Cadastro </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS E Jquery-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <div class='container-fluid'>
        <div class="row">
            <div class="col-6">
                <div class="col-12 card card-body bg-light">
                    <h3> Cadastro de usuario </h3>
                    <hr>
                    <form  id="cad_usuario" name="cad_usuario" method="POST" action="cadastro_usuario.php">
                        <div class="row">
                            <div class="col-12">
                                <label for="fNome"><b>Nome</b></label>
                                <input type="text" class="form-control" id="fNome" name="fNome" placeholder="Nome" required maxlength="40">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="fEmail"><b>Email </b></label>
                                <input type="email" class="form-control " placeholder="email" id="fEmail" name="fEmail" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="fEndereco"><b>Endereço</b></label>
                                <input type="text" class="form-control" id="fEndereco" name="fEndereco" placeholder="Endereço" >
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-grid gap-2 d-md-block">
                                    <br>
                                    <button class="btn btn-success" type="button" id="btnCad"><i class="fas fa-check"></i></i> Cadastrar</button>
                                    <button class="btn btn-primary" type="button" id="btnLimpar"><i class="fas fa-eraser"></i> Limpar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <br>
                <div class="col-12 card card-body bg-light">
                    <h4> Pesquisa por ID contato</h5>
                    <hr>
                    <div class="col-6">
                        <div class="input-group mb-6">
                            <input type="text" class="form-control" id="id_contato" placeholder="ID contato"  aria-describedby="button-addon2">
                            <button class="btn btn-outline-primary" type="button" id="button-addon2">Pesquisar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="col-12 card card-body ">
                    <h3> Usuarios cadastrados </h3>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Endereço</th>
                                    <th>Data de cadastro</th>
                                </thead>
                                <tbody>
                                    <?=$results_cad?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    //limpar campos
    $('#btnLimpar').on("click" , function(){
        $('input').val('')
    });

    $('#btnCad').on("click" , function(){
        //verificar se os campos foram preenchidos
        if ($('#fNome').val() == ''){
            Swal.fire({
                icon: 'warning',
                title: 'Atenção',
                text: 'Digite o campo Nome!'
            }) 
            return
        }
        $.ajax({
            type: "POST",
            url: "cadastro_usuario_ajax.php",
            data: 
                $("#cad_usuario").serialize()
            ,
            dataType: "json",  
            success: function(data) { 
                // var dados = jQuery.parseJSON(data)
                Swal.fire({
                    icon: data.status,
                    title: data.status,
                    text: data.mensagem
                }).then((result) => {
                    location.reload();
                })
                
            },
            error: function (data) { 
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Ocorreu um erro'
                }) 
              
            } 
        })

    })

    $('#button-addon2').on("click" , function(){
        //verificar se os campos foram preenchidos
        if ($('#id_contato').val() == ''){
            Swal.fire({
                icon: 'warning',
                title: 'Atenção',
                text: 'Digite o campo id contato!'
            }) 
            return
        }
        $.ajax({
            type: "GET",
            url: "cadastro_usuario_ajax.php?id_contato="+$('#id_contato').val(),
            dataType: "json",  
            success: function(data) { 
                // var dados = jQuery.parseJSON(data)
                Swal.fire({
                    icon: data.status,
                    title: data.status,
                    html: data.mensagem
                })
            },
            error: function (data) { 
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Ocorreu um erro com id'
                }) 
              
            } 
        })

    })


   
</script>