<h1 class="h3 mb-4">Dashboard</h1>

<div class="row g-3 mb-4">
	<div class="col-md-3">
		<div class="card card-kpi text-bg-success">
			<div class="card-body">
				<div class="text-uppercase small">Em estoque</div>
				<div class="display-6"><?php echo (int) $total_estoque; ?></div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card card-kpi text-bg-primary">
			<div class="card-body">
				<div class="text-uppercase small">Em uso</div>
				<div class="display-6"><?php echo (int) $total_em_uso; ?></div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card card-kpi text-bg-warning">
			<div class="card-body">
				<div class="text-uppercase small">Em manutenção</div>
				<div class="display-6"><?php echo (int) $total_manutencao; ?></div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card card-kpi text-bg-secondary">
			<div class="card-body">
				<div class="text-uppercase small">Saídas / Entradas hoje</div>
				<div class="display-6"><?php echo (int) $saidas_hoje; ?> / <?php echo (int) $entradas_hoje; ?></div>
			</div>
		</div>
	</div>
</div>

<div class="row g-3">
	<div class="col-md-4">
		<a href="<?php echo site_url('recipientes'); ?>" class="btn btn-outline-dark w-100 py-3">
			<i class="bi bi-box"></i> Gerenciar Recipientes
		</a>
	</div>
	<div class="col-md-4">
		<a href="<?php echo site_url('saidas/novo'); ?>" class="btn btn-outline-dark w-100 py-3">
			<i class="bi bi-box-arrow-up"></i> Registrar Saída
		</a>
	</div>
	<div class="col-md-4">
		<a href="<?php echo site_url('entradas/novo'); ?>" class="btn btn-outline-dark w-100 py-3">
			<i class="bi bi-box-arrow-in-down"></i> Registrar Entrada
		</a>
	</div>
</div>
