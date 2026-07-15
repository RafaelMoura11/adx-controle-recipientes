<div class="d-flex justify-content-between align-items-center mb-3">
	<h1 class="h3 mb-0">Saídas</h1>
	<a href="<?php echo site_url('saidas/novo'); ?>" class="btn btn-primary">
		<i class="bi bi-box-arrow-up"></i> Registrar saída
	</a>
</div>

<div class="card">
	<div class="table-responsive">
		<table class="table table-hover mb-0 align-middle">
			<thead class="table-light">
				<tr>
					<th>#</th>
					<th>Data/Hora</th>
					<th>Motorista</th>
					<th>Rota</th>
					<th>Status</th>
					<th class="text-end">Ações</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($saidas as $s): ?>
				<tr>
					<td>#<?php echo (int) $s->id; ?></td>
					<td><?php echo date('d/m/Y H:i', strtotime($s->data_hora_saida)); ?></td>
					<td><?php echo htmlspecialchars($s->motorista_nome); ?></td>
					<td><?php echo $s->rota_nome ? htmlspecialchars($s->rota_nome) : '-'; ?></td>
					<td>
						<?php
                            $badges = array('aberta' => 'primary', 'parcialmente_retornada' => 'warning', 'concluida' => 'success');
			    ?>
						<span class="badge bg-<?php echo $badges[$s->status] ?? 'secondary'; ?>"><?php echo htmlspecialchars(str_replace('_', ' ', $s->status)); ?></span>
					</td>
					<td class="text-end">
						<a href="<?php echo site_url('saidas/detalhe/'.$s->id); ?>" class="btn btn-sm btn-outline-secondary">Detalhes</a>
					</td>
				</tr>
			<?php endforeach; ?>
			<?php if (empty($saidas)): ?>
				<tr><td colspan="6" class="text-center text-muted py-3">Nenhuma saída registrada.</td></tr>
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
