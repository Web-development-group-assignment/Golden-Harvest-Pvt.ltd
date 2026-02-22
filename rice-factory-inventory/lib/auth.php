<?php
function login($pdo,$email,$password){
  $st=$pdo->prepare("SELECT u.*, r.name role_name FROM users u JOIN roles r ON u.role_id=r.id WHERE u.email=? AND u.is_active=1");
  $st->execute([$email]); $user=$st->fetch();
  if($user && password_verify($password,$user['password_hash'])){
    $_SESSION['user']=['id'=>$user['id'],'name'=>$user['full_name'],'email'=>$user['email'],'role'=>$user['role_name']];
    audit($pdo,$user['id'],'LOGIN','users',$user['id'],null);
    return true;
  } return false;
}
function require_login(){ 
  if(empty($_SESSION['user'])){ 
    redirect('index.php');
    }
}
function is_role($role){ 
  return !empty($_SESSION['user']) && $_SESSION['user']['role']===$role;
}
function require_role($roles=[]){ 
  require_login(); 
  if(!in_array($_SESSION['user']['role'],$roles)){ 
    http_response_code(403); 
    exit(
      'You Do not have access to these paths Please contact Admin or Manager'
    );
  }
}
function logout(){ session_destroy(); }
function audit($pdo,$user_id,$action,$entity,$entity_id,$payload){
  $st=$pdo->prepare(
    "INSERT INTO audit_logs (user_id,action,entity,entity_id,payload) VALUES (?,?,?,?,?)"
    );
  $st->execute([$user_id,$action,$entity,$entity_id,$payload?json_encode($payload):null]);
}
