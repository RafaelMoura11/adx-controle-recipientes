<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Pagina nao encontrada - Controle de Recipientes</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">
	<div class="container text-center">
		<div class="display-1 text-secondary mb-3">404</div>
		<h1 class="h4 mb-3"><?php echo $heading; ?></h1>
		<div class="text-muted"><?php echo $message; ?></div>
		<a href="/" class="btn btn-primary mt-3">Voltar ao inicio</a>
	</div>
</body>
</html>
