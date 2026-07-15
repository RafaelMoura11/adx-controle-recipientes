<div class="d-flex justify-content-between align-items-center mb-3">
	<h1 class="h3 mb-0">Rotas</h1>
	<a href="<?php echo site_url('rotas/novo'); ?>" class="btn btn-primary">
		<i class="bi bi-plus-lg"></i> Nova rota
	</a>
</div>

<div class="card">
	<div class="table-responsive">
		<table class="table table-hover mb-0 align-middle">
			<thead class="table-light">
				<tr>
					<th>Nome</th>
					<th>Descrição</th>
					<th>Situação</th>
					<th class="text-end">Ações</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($rotas as $rota): ?>
				<tr>
					<td><?php echo htmlspecialchars($rota->nome); ?></td>
					<td><?php echo htmlspecialchars($rota->descricao); ?></td>
					<td>
						<?php if ($rota->ativa): ?>
							<span class="badge bg-success">Ativa</span>
						<?php else: ?>
							<span class="badge bg-secondary">Inativa</span>
						<?php endif; ?>
					</td>
					<td class="text-end">
						<a href="<?php echo site_url('rotas/editar/'.$rota->id); ?>" class="btn btn-sm btn-outline-secondary">Editar / Pontos</a>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
