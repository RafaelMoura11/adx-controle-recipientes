<h1 class="h3 mb-4">Relatorio de movimentacao de recipientes</h1>

<div class="card mb-3">
	<div class="card-body">
		<form method="get" class="row g-2 align-items-end">
			<div class="col-auto">
				<label class="form-label mb-0">De</label>
				<input type="date" name="de" class="form-control" value="<?php echo htmlspecialchars($de); ?>">
			</div>
			<div class="col-auto">
				<label class="form-label mb-0">Ate</label>
				<input type="date" name="ate" class="form-control" value="<?php echo htmlspecialchars($ate); ?>">
			</div>
			<div class="col-auto">
				<button type="submit" class="btn btn-primary">Filtrar</button>
			</div>
		</form>
	</div>
</div>

<div class="card">
	<div class="table-responsive">
		<table class="table table-sm mb-0 align-middle">
			<thead class="table-light">
				<tr>
					<th>Local de destino</th>
					<th>Data de saida</th>
					<th>Recipientes da saida</th>
					<th class="text-end">Quantidade saida</th>
					<th class="text-end">Quantidade retornado</th>
					<th class="text-end">Recipiente em uso</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($linhas as $l): ?>
				<tr>
					<td><?php echo htmlspecialchars($l->local_destino); ?></td>
					<td><?php echo date('d/m/Y H:i', strtotime($l->data_hora_saida)); ?></td>
					<td><code><?php echo htmlspecialchars($l->recipientes_saida); ?></code></td>
					<td class="text-end"><?php echo (int) $l->quantidade_saida; ?></td>
					<td class="text-end"><?php echo (int) $l->quantidade_retornado; ?></td>
					<td class="text-end"><?php echo (int) $l->recipiente_em_uso; ?></td>
				</tr>
			<?php endforeach; ?>
			<?php if (empty($linhas)): ?>
				<tr><td colspan="6" class="text-center text-muted py-4">Nenhuma movimentacao no periodo selecionado.</td></tr>
			<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>
