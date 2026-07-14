<h1 class="h3 mb-4">Meus destinos</h1>

<div class="card">
	<div class="table-responsive">
		<table class="table mb-0 align-middle">
			<thead class="table-light">
				<tr>
					<th>Saida</th>
					<th>Destino</th>
					<th>Endereco</th>
					<th class="text-end">Quantidade de recipientes</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($destinos as $d): ?>
				<tr>
					<td>#<?php echo (int) $d->saida_id; ?> - <?php echo date('d/m/Y H:i', strtotime($d->data_hora_saida)); ?></td>
					<td><?php echo htmlspecialchars($d->ponto_nome); ?></td>
					<td><?php echo $d->ponto_endereco ? htmlspecialchars($d->ponto_endereco) : '-'; ?></td>
					<td class="text-end"><span class="badge bg-primary fs-6"><?php echo (int) $d->quantidade_recipientes; ?></span></td>
				</tr>
			<?php endforeach; ?>
			<?php if (empty($destinos)): ?>
				<tr><td colspan="4" class="text-center text-muted py-4">Nenhum destino pendente no momento.</td></tr>
			<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>
