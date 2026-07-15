<div class="d-flex justify-content-between align-items-center mb-3">
	<h1 class="h3 mb-0">Recipientes</h1>
	<a href="<?php echo site_url('recipientes/novo'); ?>" class="btn btn-primary">
		<i class="bi bi-plus-lg"></i> Novo recipiente
	</a>
</div>

<div class="card mb-3">
	<div class="card-body py-2">
		<div class="btn-group btn-group-sm">
			<a href="<?php echo site_url('recipientes'); ?>" class="btn btn-outline-secondary <?php echo ! $status_filtro ? 'active' : ''; ?>">Todos</a>
			<a href="<?php echo site_url('recipientes?status=estoque'); ?>" class="btn btn-outline-secondary <?php echo $status_filtro === 'estoque' ? 'active' : ''; ?>">Em estoque</a>
			<a href="<?php echo site_url('recipientes?status=em_uso'); ?>" class="btn btn-outline-secondary <?php echo $status_filtro === 'em_uso' ? 'active' : ''; ?>">Em uso</a>
			<a href="<?php echo site_url('recipientes?status=manutencao'); ?>" class="btn btn-outline-secondary <?php echo $status_filtro === 'manutencao' ? 'active' : ''; ?>">Manutenção</a>
			<a href="<?php echo site_url('recipientes?status=inativo'); ?>" class="btn btn-outline-secondary <?php echo $status_filtro === 'inativo' ? 'active' : ''; ?>">Inativo</a>
		</div>
	</div>
</div>

<div class="card">
	<div class="table-responsive">
		<table class="table table-hover mb-0 align-middle">
			<thead class="table-light">
				<tr>
					<th>Código</th>
					<th>Descrição</th>
					<th>Status</th>
					<th>Localização atual</th>
					<th class="text-end">Ações</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($recipientes as $r): ?>
				<tr>
					<td><code><?php echo htmlspecialchars($r->codigo); ?></code></td>
					<td><?php echo htmlspecialchars($r->descricao); ?></td>
					<td>
						<?php
                            $badges = array(
                                'estoque' => 'success',
                                'em_uso' => 'primary',
                                'manutencao' => 'warning',
                                'inativo' => 'secondary',
                            );
			    ?>
						<span class="badge bg-<?php echo $badges[$r->status] ?? 'secondary'; ?>"><?php echo htmlspecialchars($r->status); ?></span>
					</td>
					<td><?php echo htmlspecialchars($r->localizacao_atual); ?></td>
					<td class="text-end">
						<a href="<?php echo site_url('recipientes/detalhe/'.$r->codigo); ?>" class="btn btn-sm btn-outline-secondary">Detalhes</a>
					</td>
				</tr>
			<?php endforeach; ?>
			<?php if (empty($recipientes)): ?>
				<tr><td colspan="5" class="text-center text-muted py-3">Nenhum recipiente encontrado.</td></tr>
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
