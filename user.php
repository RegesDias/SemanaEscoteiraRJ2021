<!DOCTYPE html>
<html lang="en">
  <?php require_once("incl/head.php"); ?>
<body>
    <?php 
        require_once('incl/config.php');
        require_once('incl/nav.php');
        print_r($respPost);
        if($respPost['acao']=='cadastroBase'){
            $insertBase = "INSERT INTO base(nome, Chave, status, ativa) VALUES ($nome,$Chave, aberta, 1)";
            $ib = $mysqli->query($insertBase);

            
        }
    ?>  
    <div class="container">
        <h1> Criar Usuário </h1>
        <p> Por favor digite abaixo os dados do usuário </p>
        <hr>
        <form method="post">
            <div class="form-group">
                <label for="Name">Nome</label>
                <input type="text" name="nome" class="form-control" id="Nome" aria-describedby="nameHelp" placeholder="Digite um nome">
                
            </div>

            <div class="form-group">
                <label for="Password">Senha</label>
                <input type="password" name="senha" class="form-control" id="Password" placeholder="Senha">
            </div>

            <div class="form-group">
                <label for="ConfirmPassword"> Confirme sua senha</label>
                <input type="password" name="confirmSenha" class="form-control" id="ConfirmPassword" placeholder="Confirme sua senha">
            </div>

                <input type="hidden" valor="cadastroUsuario" name="acao">
           

            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
       
    <hr>

    