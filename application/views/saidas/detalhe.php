<h1 class="h3 mb-4">Saída #<?php echo (int) $saida->id; ?></h1>

<div class="card mb-4">
	<div class="card-body">
		<dl class="row mb-0">
			<dt class="col-sm-3">Motorista</dt>
			<dd class="col-sm-9"><?php echo htmlspecialchars($saida->motorista_nome); ?></dd>

			<dt class="col-sm-3">Rota</dt>
			<dd class="col-sm-9"><?php echo $saida->rota_nome ? htmlspecialchars($saida->rota_nome) : '-'; ?></dd>

			<dt class="col-sm-3">Data/hora da saída</dt>
			<dd class="col-sm-9"><?php echo date('d/m/Y H:i', strtotime($saida->data_hora_saida)); ?></dd>

			<dt class="col-sm-3">Registrado por</dt>
			<dd class="col-sm-9"><?php echo htmlspecialchars($saida->registrado_por_nome); ?></dd>

			<dt class="col-sm-3">Status</dt>
			<dd class="col-sm-9">
				<?php $badges = array('aberta' => 'primary', 'parcialmente_retornada' => 'warning', 'concluida' => 'success'); ?>
				<span class="badge bg-<?php echo $badges[$saida->status] ?? 'secondary'; ?>"><?php echo htmlspecialchars(str_replace('_', ' ', $saida->status)); ?></span>
			</dd>

			<?php if ($saida->observacoes): ?>
			<dt class="col-sm-3">Observações</dt>
			<dd class="col-sm-9"><?php echo nl2br(htmlspecialchars($saida->observacoes)); ?></dd>
			<?php endif; ?>
		</dl>
	</div>
</div>

<div class="card">
	<div class="card-header">Recipientes desta saída</div>
	<div class="table-responsive">
		<table class="table mb-0 align-middle">
			<thead class="table-light">
				<tr>
					<th>Código</th>
					<th>Ponto de entrega</th>
					<th>Situação</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($itens as $item): ?>
				<tr>
					<td><a href="<?php echo site_url('recipientes/detalhe/'.$item->codigo); ?>"><code><?php echo htmlspecialchars($item->codigo); ?></code></a></td>
					<td><?php echo htmlspecialchars($item->ponto_nome); ?></td>
					<td>
						<?php if ($item->status_item === 'em_uso'): ?>
							<span class="badge bg-primary">Em uso</span>
						<?php else: ?>
							<span class="badge bg-success">Retornado</span>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
