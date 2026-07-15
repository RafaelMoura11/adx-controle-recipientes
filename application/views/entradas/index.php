<div class="d-flex justify-content-between align-items-center mb-3">
	<h1 class="h3 mb-0">Entradas</h1>
	<a href="<?php echo site_url('entradas/novo'); ?>" class="btn btn-primary">
		<i class="bi bi-box-arrow-in-down"></i> Registrar entrada
	</a>
</div>

<div class="card">
	<div class="table-responsive">
		<table class="table table-hover mb-0 align-middle">
			<thead class="table-light">
				<tr>
					<th>#</th>
					<th>Data/Hora</th>
					<th>Motorista (devolveu)</th>
					<th>Registrado por</th>
					<th class="text-end">Ações</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($entradas as $e): ?>
				<tr>
					<td>#<?php echo (int) $e->id; ?></td>
					<td><?php echo date('d/m/Y H:i', strtotime($e->data_hora_entrada)); ?></td>
					<td><?php echo $e->motorista_nome ? htmlspecialchars($e->motorista_nome) : '-'; ?></td>
					<td><?php echo htmlspecialchars($e->registrado_por_nome); ?></td>
					<td class="text-end">
						<a href="<?php echo site_url('entradas/detalhe/'.$e->id); ?>" class="btn btn-sm btn-outline-secondary">Detalhes</a>
					</td>
				</tr>
			<?php endforeach; ?>
			<?php if (empty($entradas)): ?>
				<tr><td colspan="5" class="text-center text-muted py-3">Nenhuma entrada registrada.</td></tr>
			<?php endif; ?>
			</tbody>
		</table>
	</div>
	<?php if ($links_paginacao): ?>
	<div class="card-footer">
		<?php echo $links_paginacao; ?>
	</div>
	<?php endif; ?>
</div>
