<h1 class="h3 mb-4">Entrada #<?php echo (int) $entrada->id; ?></h1>

<div class="card mb-4">
	<div class="card-body">
		<dl class="row mb-0">
			<dt class="col-sm-3">Motorista (devolveu)</dt>
			<dd class="col-sm-9"><?php echo $entrada->motorista_nome ? htmlspecialchars($entrada->motorista_nome) : '-'; ?></dd>

			<dt class="col-sm-3">Data/hora da entrada</dt>
			<dd class="col-sm-9"><?php echo date('d/m/Y H:i', strtotime($entrada->data_hora_entrada)); ?></dd>

			<dt class="col-sm-3">Registrado por</dt>
			<dd class="col-sm-9"><?php echo htmlspecialchars($entrada->registrado_por_nome); ?></dd>

			<?php if ($entrada->observacoes): ?>
			<dt class="col-sm-3">Observações</dt>
			<dd class="col-sm-9"><?php echo nl2br(htmlspecialchars($entrada->observacoes)); ?></dd>
			<?php endif; ?>
		</dl>
	</div>
</div>

<div class="card">
	<div class="card-header">Recipientes desta entrada</div>
	<div class="table-responsive">
		<table class="table mb-0 align-middle">
			<thead class="table-light">
				<tr>
					<th>Código</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($itens as $item): ?>
				<tr>
					<td><a href="<?php echo site_url('recipientes/detalhe/'.$item->codigo); ?>"><code><?php echo htmlspecialchars($item->codigo); ?></code></a></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
