<?php

	error_reporting(0);
	set_time_limit(0);
	
	//usuarios
	$caixa_login			= 'caixa@gmail.com';
	$caixa_senha			= '123';
	$caixa_id				= 1;
	$desconhecido_login		= 'desconhecido@gmail.com';
	$desconhecido_senha		= '123';
	$desconhecido_id		= 2;
	$geral_login			= 'geral@gmail.com';
	$geral_senha			= '123';
	$geral_id				= 3;
	$husk_login				= 'husk@gmail.com';
	$husk_senha				= '123';
	$husk_id				= 4;
	$kyle_login				= 'kyle@gmail.com';
	$kyle_senha				= '123';
	$kyle_id				= 5;
	$veem_login				= 'veem@gmail.com';
	$veem_senha				= '123';
	$veem_id				= 6;

	if(isset($_COOKIE['id_user'])&&$_COOKIE['id_user']!=null){
		verifica($_COOKIE['id_user']);
		die();
	} else {
		//die($_POST['email']);
		if(isset($_POST['email'])&&$_POST['email']!=null&&isset($_POST['senha'])&&$_POST['senha']!=null){
			$email = $_POST['email'];
			$senha = $_POST['senha'];

			switch ($email) {
				case $caixa_login:
					if($caixa_senha==$senha){
						verifica($caixa_id);
					} else {
					erro_login('login_invalido');
						
					}
					break;
				case $desconhecido_login:
					if($desconhecido_senha==$senha){ 
						verifica($desconhecido_id);
					} else {
						//erro senha
						erro_login('login_invalido');
					}
					break;
				case $geral_login:
					if($geral_senha==$senha){ 
						verifica($geral_id);
					} else {
						//erro senha
						erro_login('login_invalido');
					}
					break;
				case $husk_login:
					if($husk_senha==$senha){ 
						verifica($husk_id);
					} else {
						//erro senha
						erro_login('login_invalido');
					}
					break;
				case $kyle_login:
					if($kyle_senha==$senha){ 
						verifica($kyle_id);
					} else {
						//erro senha
						erro_login('login_invalido');
					}
					break;
				case $veem_login:
					if($veem_senha==$senha){ 
						verifica($veem_id);
					} else {
						//erro senha
						erro_login('login_invalido');
					}
					break;
				 default;
					erro_login('login_invalido');
					break;
			}

		} else {
			//volta tela de login com erro de login
			erro_login('login_invalido');
			die();
			
		}

	}

function erro_login($msg){
	setcookie('id_user');
	unset($_COOKIE['id_user']);
	setcookie('id_user', null, -1, '/');
	header('Location: index.php');
	?>
	<script>window.location.href = "index.php";</script>
	<?php
	exit;
}	
	
function verifica ( $id_user ){

	if(isset($_COOKIE['id_user'])&&isset($_COOKIE['id_user'])!=null){
		setcookie('id_user', $id_user, (time() + (2 * 3600)));// duas horas
	} else {
		setcookie('id_user', $id_user, (time() + (2 * 3600)));// duas horas
		//setcookie('email', $id_user, (time() + (2 * 3600)));// duas horas
	}

	//conexao com BD
	$servername	= "174.138.37.25";
	$username 	= "root";
	$password 	= 'FCp*=Em$rc*5S&EU';
	
//	$servername	= "localhost";
//	$username 	= "root";
//	$password 	= '';
	
	
	try {
		$conn = new PDO("mysql:host=$servername;dbname=dyspaga", $username, $password);
	} catch (PDOException $e) {
		print "Error!: " . $e->getMessage() . "<br/>";
		die();
	}

	// set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//echo "Connected successfully";

	$stmt 	= $conn->query("SELECT * FROM registros WHERE status_ultimo = 0 AND cliente_id = ".$id_user." AND valor <> 0 ORDER BY created_at DESC ");
	$busca 	= $stmt->fetchAll();

	$totalRegistros = count($busca);

	header('Content-Type: text/html; charset=utf-8');
	ini_set('default_charset', 'utf-8');
	require_once 'inc_topo.php';
	
	for($i = 0 ; $i < $totalRegistros; $i++ ){
	   ?>
		<tr class="lista_hover">
			<td><?php echo date("d/m/Y", strtotime($busca[$i]['created_at']));?></td>
			<td><?php echo $busca[$i]['saque_id'];?></td>
			<td><?php echo 'R$' . number_format($busca[$i]['valor'], 2, ',', '.');?></td>
			<td><?php echo $busca[$i]['favorecido'];?></td>
			<td><?php echo $busca[$i]['url_comprovante'];?></td>

			<td id="confirmacao_on_off">
				<label class="switch">
					<input type="checkbox" id="checkbox_confirmacao<?php echo $busca[$i]['id'];?>" class="checkboxConfirmacao" onclick="confirmaResolvido(<?php echo $busca[$i]['id'];?>);" <?php if(isset($busca[$i]['cliente_confirma_erro'])&&$busca[$i]['cliente_confirma_erro']>0){ echo 'checked'; } else { echo '';}?> >
				  <span class="slider round"></span>
				</label>

				<input type="hidden" value="<?php echo $busca[$i]['id'];?>" name="checkbox_id<?php echo $busca[$i]['id'];?>" id="checkbox_id<?php echo $busca[$i]['id'];?>">
				
				<input type="hidden" value="<?php echo $busca[$i]['cliente_confirma_erro']?>" name="checkbox_atual<?php echo $busca[$i]['id'];?>" id="checkbox_atual<?php echo $busca[$i]['id'];?>">

			</td>
		</tr>
		<?php
	}
require_once 'inc_rodape.php';

} 

