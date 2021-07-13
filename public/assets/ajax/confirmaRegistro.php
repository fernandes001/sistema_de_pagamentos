<?php
error_reporting(1);

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){    
	
	if(isset($_POST['registro_id'])&&isset($_POST['user_id'])&&isset($_POST['confirmacao_id_atual'])){
		$registro_id			= $_POST['registro_id'];
		$confirmacao_id_atual	= $_POST['confirmacao_id_atual'];

		date_default_timezone_set('America/New_York');
		$data_hora			= date("Y-m-d H:i:s");

		//verifico se coloco o id do user que confirmou ou se coloco 0 como nao confirmado
		if($confirmacao_id_atual==null||$confirmacao_id_atual==0){
			$user_id			= $_POST['user_id'];
			$volta_ativado	= 1;
		} else {
			$user_id			= 0;
			$volta_ativado	= 0;
		}

		//conexao com BD
		$servername	= "174.138.37.25";
		//$servername	= "localhost";
		$username 	= "root";
		$password 	= 'FCp*=Em$rc*5S&EU';
		//$password 	= '';

		try {
			$conn = new PDO("mysql:host=$servername;dbname=dyspaga2", $username, $password);
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}

		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql	= "UPDATE registros SET confirmacao_id=?, updated_at=? WHERE id=?";
		$stmt	= $conn->prepare($sql);
		$stmt->execute([$user_id, $data_hora, $registro_id]);

		//return $volta_ativado;
		echo $volta_ativado;

  }

} else {
	die();
}

