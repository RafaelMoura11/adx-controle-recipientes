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
		<a class="navbar-brand" href="<?php echo site_url($usuario_logado->tipo_usuario === 'motorista' ? 'motorista' : 'dashboard'); ?>">
			<i class="bi bi-box-seam"></i> Controle de Recipientes
		</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarMain">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
				<?php if ($usuario_logado->tipo_usuario === 'motorista'): ?>
				<li class="nav-item"><a class="nav-link" href="<?php echo site_url('motorista'); ?>">Meus destinos</a></li>
				<?php else: ?>
				<li class="nav-item"><a class="nav-link" href="<?php echo site_url('dashboard'); ?>">Dashboard</a></li>
				<li class="nav-item"><a class="nav-link" href="<?php echo site_url('recipientes'); ?>">Recipientes</a></li>
				<li class="nav-item"><a class="nav-link" href="<?php echo site_url('saidas'); ?>">Saídas</a></li>
				<li class="nav-item"><a class="nav-link" href="<?php echo site_url('entradas'); ?>">Entradas</a></li>
				<li class="nav-item"><a class="nav-link" href="<?php echo site_url('rotas'); ?>">Rotas</a></li>
				<?php endif; ?>
				<?php if ($usuario_logado->tipo_usuario === 'administrador'): ?>
				<li class="nav-item"><a class="nav-link" href="<?php echo site_url('usuarios'); ?>">Usuários</a></li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Relatórios</a>
					<ul class="dropdown-menu">
						<li><a class="dropdown-item" href="<?php echo site_url('relatorios/movimentacao'); ?>">Movimentação</a></li>
						<li><a class="dropdown-item" href="<?php echo site_url('relatorios/motoristas'); ?>">Devoluções por motorista</a></li>
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

<?php if (isset($usuario_logado) && $usuario_logado->tipo_usuario !== 'motorista'): ?>
<div class="modal fade" id="modal-qr-scanner" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Escanear QR Code</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<div id="qr-scanner-reader" class="qr-scan-preview"></div>
				<p class="text-muted small mt-2 mb-0">Aponte a câmera para o QR Code do recipiente.</p>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php if (isset($usuario_logado) && $usuario_logado->tipo_usuario !== 'motorista'): ?>
<script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script src="<?php echo base_url('assets/js/qr-scanner.js'); ?>"></script>
<?php endif; ?>
</body>
</html>
