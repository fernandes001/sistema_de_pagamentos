<?php
	$url = 'http://'.$_SERVER['SERVER_NAME'].'/';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dys - relatório erro</title>

	<!-- Custom fonts for this template-->
	<link href="<?php echo $url;?>assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<!--<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">-->

	<!-- Custom styles for this template-->
	<link href="<?php echo $url;?>assets/css/sb-admin-2.min.css" rel="stylesheet">

	<link href="<?php echo $url;?>assets/css/dys-temp-custom.css" rel="stylesheet">
</head>
    <body id="page-top">

        <!-- Page Wrapper -->
        <div id="wrapper">
            <!-- Sidebar -->

<!-- End of Sidebar -->
            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">

                    <!-- Begin Page Content -->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
										<li class="breadcrumb-item"><a href="logout.php">SAIR</a></li>
                                    </ol>
                                </nav>

                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Pagamentos com erro (<?php echo $totalRegistros;?>)</h6>
                                    </div>

                                    <div class="card-body">
										
										
										<table class="table table-striped">
			<thead>
				<tr>
					<th scope="col">data</th>
					<th scope="col">ID Saque</th>
					<th scope="col">Valor</th>
					<th scope="col">Favorecido</th>
					<th scope="col">Comprovante</th>
					<th scope="col">Resolvido</th>
				</tr>
			</thead>

			<tbody>