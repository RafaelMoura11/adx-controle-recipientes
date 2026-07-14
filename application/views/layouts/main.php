<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Controle de Recipientes - ADX</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/app.css'); ?>" rel="stylesheet">
</head>
<body class="bg-light">

<?php if (isset($usuario_logado)): ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
	<div class="container-fluid">
		<a class="navbar-brand" href="<?php echo site_url('dashboard'); ?>">
			<i class="bi bi-box-seam"></i> Controle de Recipientes
		</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarMain">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
				<li class="nav-item"><a class="nav-link" href="<?php echo site_url('dashboard'); ?>">Dashboard</a></li>
				<li class="nav-item"><a class="nav-link" href="<?php echo site_url('recipientes'); ?>">Recipientes</a></li>
				<li class="nav-item"><a class="nav-link" href="<?php echo site_url('saidas'); ?>">Saidas</a></li>
				<li class="nav-item"><a class="nav-link" href="<?php echo site_url('entradas'); ?>">Entradas</a></li>
				<li class="nav-item"><a class="nav-link" href="<?php echo site_url('rotas'); ?>">Rotas</a></li>
				<?php if ($usuario_logado->tipo_usuario === 'administrador'): ?>
				<li class="nav-item"><a class="nav-link" href="<?php echo site_url('usuarios'); ?>">Usuarios</a></li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Relatorios</a>
					<ul class="dropdown-menu">
						<li><a class="dropdown-item" href="<?php echo site_url('relatorios/movimentacao'); ?>">Movimentacao</a></li>
						<li><a class="dropdown-item" href="<?php echo site_url('relatorios/motoristas'); ?>">Devolucoes por motorista</a></li>
					</ul>
				</li>
				<?php endif; ?>
			</ul>
			<span class="navbar-text text-light me-3">
				<?php echo htmlspecialchars($usuario_logado->nome); ?>
				<span class="badge bg-secondary text-uppercase"><?php echo htmlspecialchars($usuario_logado->tipo_usuario); ?></span>
			</span>
			<a href="<?php echo site_url('logout'); ?>" class="btn btn-outline-light btn-sm">Sair</a>
		</div>
	</div>
</nav>
<?php endif; ?>

<div class="container-fluid px-4 pb-5">
	<?php if ($this->session->flashdata('sucesso')): ?>
		<div class="alert alert-success"><?php echo htmlspecialchars($this->session->flashdata('sucesso')); ?></div>
	<?php endif; ?>
	<?php if ($this->session->flashdata('erro')): ?>
		<div class="alert alert-danger"><?php echo htmlspecialchars($this->session->flashdata('erro')); ?></div>
	<?php endif; ?>
	<?php echo $conteudo_html; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
