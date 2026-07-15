<div class="d-flex justify-content-between align-items-center mb-3">
	<h1 class="h3 mb-0">Recipiente <?php echo htmlspecialchars($recipiente->codigo); ?></h1>
	<a href="<?php echo site_url('recipientes/editar/'.$recipiente->id); ?>" class="btn btn-outline-secondary">
		<i class="bi bi-pencil"></i> Editar
	</a>
</div>

<div class="row g-3 mb-4">
	<div class="col-md-4">
		<div class="card h-100">
			<div class="card-body text-center">
				<img src="<?php echo site_url('recipientes/qrcode/'.$recipiente->codigo); ?>"
					alt="QR Code <?php echo htmlspecialchars($recipiente->codigo); ?>"
					class="img-fluid mb-3" style="max-width: 220px;">
				<div>
					<a href="<?php echo site_url('recipientes/qrcode/'.$recipiente->codigo); ?>" download="<?php echo htmlspecialchars($recipiente->codigo); ?>.png" class="btn btn-sm btn-outline-primary">
						<i class="bi bi-download"></i> Baixar QR Code
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-8">
		<div class="card h-100">
			<div class="card-body">
				<dl class="row mb-0">
					<dt class="col-sm-4">Código</dt>
					<dd class="col-sm-8"><code><?php echo htmlspecialchars($recipiente->codigo); ?></code></dd>

					<dt class="col-sm-4">Descrição</dt>
					<dd class="col-sm-8"><?php echo htmlspecialchars($recipiente->descricao); ?></dd>

					<dt class="col-sm-4">Status atual</dt>
					<dd class="col-sm-8">
						<?php
                            $badges = array('estoque' => 'success', 'em_uso' => 'primary', 'manutencao' => 'warning', 'inativo' => 'secondary');
	?>
						<span class="badge bg-<?php echo $badges[$recipiente->status] ?? 'secondary'; ?>"><?php echo htmlspecialchars($recipiente->status); ?></span>
					</dd>

					<dt class="col-sm-4">Onde está</dt>
					<dd class="col-sm-8"><?php echo htmlspecialchars($recipiente->localizacao_atual); ?></dd>
				</dl>
			</div>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-header">Histórico completo de movimentações</div>
	<div class="table-responsive">
		<table class="table mb-0 align-middle">
			<thead class="table-light">
				<tr>
					<th>Tipo</th>
					<th>Data/Hora</th>
					<th>Local</th>
					<th>Motorista</th>
					<th>Registrado por</th>
					<th>Situação do item</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($historico as $h): ?>
				<tr>
					<td>
						<?php if ($h->tipo === 'saida'): ?>
							<span class="badge bg-primary"><i class="bi bi-box-arrow-up"></i> Saída</span>
						<?php else: ?>
							<span class="badge bg-success"><i class="bi bi-box-arrow-in-down"></i> Entrada</span>
						<?php endif; ?>
					</td>
					<td><?php echo date('d/m/Y H:i', strtotime($h->data_hora)); ?></td>
					<td><?php echo $h->local ? htmlspecialchars($h->local) : '-'; ?></td>
					<td><?php echo $h->motorista_nome ? htmlspecialchars($h->motorista_nome) : '-'; ?></td>
					<td><?php echo htmlspecialchars($h->registrado_por); ?></td>
					<td><?php echo $h->status_item ? htmlspecialchars($h->status_item) : '-'; ?></td>
				</tr>
			<?php endforeach; ?>
			<?php if (empty($historico)): ?>
				<tr><td colspan="6" class="text-center text-muted py-3">Nenhuma movimentação registrada ainda.</td></tr>
			<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>
