<?php
require_once('Usuario.php');
require_once('Nota.php');
require_once('model/BaseModel.php');
class Base extends BaseModel {
    public function listar(){
        $user = new Usuario;
        if($user->getChefeBase() == true){
          $basesql = "SELECT * FROM base WHERE ResposavelBase = '$user->getIdUser()' ";
        }else{
          $basesql = "SELECT * FROM base";
        }
        return $basesql;
    }
 
    public function imagem(){
      echo "<a href='#'><img height='700' width='50' class='card-img-top img-fluid border-radius img-thumbnail' src='img/".$this->getImg()."' alt=''></a>";
    }
    
    public function entrar($id){
          global $mysqli;
          $user = new Usuario;
          $base = new Base;
          $base = $this->burcarBasePorId($id);
          if($base->getStatus() == 'Aberta'){
              $updateBase="UPDATE base SET status = 'Fechada', idUser = '".$user->getIdUser()."' WHERE id = '".$base->id."'";
              $ub = $mysqli->query($updateBase);
              $insertBaseFeitas = "INSERT INTO baseFeitas(idBase, idUser,ativo) VALUES (".$base->id.",".$user->getIdUser().",1)";
              $ibf = $mysqli->query($insertBaseFeitas);
              header('Location: '.$base->getLink());
          }else{?>
              <div class="alert alert-warning">
              <button type="button" class="close" data-dismiss="alert">×</button>
              <h4>Alerta!</h4>
              Foi quase... mas infelizmente esta base já foi acessada
              </div><?php
          }
    }
 public function abrirAvaliar($id){
    global $respObj;
    print_p($respObj);
    
    global $mysqli;
    $base = new Base;
    $user = new Usuario;
    $base = $this->burcarBasePorId($id);
    if($base->getStatus() == 'Aberta'){
      $status = 'Fechada';
    }else{
      $status = 'Aberta';
    }
    $atualizarBase = "UPDATE base SET status = '".$base->getStatus()."' WHERE id = '".$base->getId()."'";
    echo $atualizarBase;
    //$ab = $mysqli->query($atualizarBase);
    $nota = new Nota;
    $nota->novaNota(
      $base->getId(),
      $base->getIdUser(),
      $respObj->nota,
      $user->getIdUser()
    );
    $user->atualizaNotaTotal($nota);

 }


 public function exibeNota($id){
  echo "<button class='btn btn-large btn-block ' disabled href='#'>Nota ".$this->getNota($id)."</button>";
 }
 //BOTOES ##################################################
 function botoes(){
    $user = new Usuario;
    if(($user->getAdmin()!= true)){
      $this->botaoAbertoFechado();
    }else{
      $this->botaoVaziaAvaliar();
    }
 }

 public function botaoVaziaAvaliar(){
  $user = new Usuario;
  if(($this->getStatus() == 'Aberta') and ($user->getAdmin() == true)){
      echo "<button class='btn btn-large btn-block btn-success' disabled href='#'>Vazia</button>";
  }else{ ?>
      <form method="post">
      <label>Avaliar Patrulha <?=retornaNome($this->getIdUser() ,'user')?></label>
      <input type="number" min="1" max="10" step="0.5" name='nota' class="form-control" placeholder="Nota">
      <input type='hidden' name='acao' value='abrirAvaliar'>
      <input type='hidden' name='id' value='<?=$this->getId()?>'>
      <label></label>
      <input type='submit' value='Abrir e Avaliar' class="btn btn-large btn-block btn-primary">
      </form><?php
  }
 }

 public function botaoAbertoFechado(){
    if($this->getStatus() === 'Aberta'){?>
        <form method="post">
        <input type='hidden' name='acao' value='entrar'>
        <input type='hidden' name='id' value='<?=$this->id?>'>
        <input type='submit' value='Aberta!' class="btn btn-large btn-block btn-success">
        </form><?php
    }else{
        echo "<button class='btn btn-large btn-block btn-danger' disabled href='#'>Fechada</button>";
    }
 }
 

 public function burcarBasePorId($id){
  global $mysqli;
  $sql = "SELECT * FROM base WHERE id = '$id' ";
  $buscaBase = $mysqli->query($sql);
  $bb = $buscaBase->fetch_object();
  $base = new Base;
  $base->novaBase(
      $bb->id, 
      $bb->idUser,
      $bb->ResposavelBase,
      $bb->nome,
      $bb->img,
      $bb->link,
      $bb->status,
      $bb->ativa,
      $bb->dataHora
  );
  return $base;
}

 //remover ##################################################
 public function baseFeitas($id){
    global $mysqli;
    $sql = "SELECT * FROM baseFeitas WHERE idBase = '$id' AND idUser = '$this->idUser'";
    $feita = $mysqli->query($sql);
    $baseJaFeita = $feita->fetch_object();
    return $baseJaFeita->ativo;
 }
 public function getNota(){
      global $mysqli;
      $user = new Usuario;
      $sql = "SELECT nota FROM notas WHERE idUser = ".$user->getidUser()." and idBase = '".$this->getId()."'";
      $rnome = $mysqli->query($sql);
      $rn = $rnome->fetch_object();
      return $rn->nota;
 }
 public function avaliado(){
      global $mysqli;
      $user = new Usuario;
      $sql = "SELECT nota FROM notas WHERE idUser = ".$user->getidUser()." and idBase = '".$this->getId()."'";
      $rnome = $mysqli->query($sql);
      if($rn = $rnome->fetch_object()){
          return true;
      }else{
          return false;
      }
 }
}
?>