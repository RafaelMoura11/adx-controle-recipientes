<h1 class="h3 mb-4">Registrar saída</h1>

<?php if (! empty($erro)): ?>
	<div class="alert alert-danger"><?php echo $erro; ?></div>
<?php endif; ?>

<div class="card">
	<div class="card-body">
		<?php echo form_open('saidas/criar'); ?>

			<div class="row g-3 mb-3">
				<div class="col-md-4">
					<label class="form-label">Motorista responsável</label>
					<select name="motorista_id" class="form-select" required>
						<option value="">Selecione</option>
						<?php foreach ($motoristas as $m): ?>
							<option value="<?php echo $m->id; ?>" <?php echo $m->situacao !== 'ativo' ? 'disabled' : ''; ?>>
								<?php echo htmlspecialchars($m->nome); ?><?php echo $m->situacao !== 'ativo' ? ' (bloqueado)' : ''; ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-4">
					<label class="form-label">Rota</label>
					<select name="rota_id" id="select-rota" class="form-select" required>
						<option value="">Selecione</option>
						<?php foreach ($rotas as $rota): ?>
							<option value="<?php echo $rota['id']; ?>"><?php echo htmlspecialchars($rota['nome']); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-4">
					<label class="form-label">Data/hora da saida</label>
					<input type="datetime-local" name="data_hora_saida" class="form-control" required
						value="<?php echo date('Y-m-d\TH:i'); ?>">
				</div>
				<div class="col-12">
					<label class="form-label">Observações</label>
					<textarea name="observacoes" class="form-control" rows="2"></textarea>
				</div>
			</div>

			<?php foreach ($rotas as $rota): ?>
				<div class="rota-pontos" data-rota-id="<?php echo $rota['id']; ?>" style="display:none;">
					<h6 class="mt-3">Pontos de entrega - <?php echo htmlspecialchars($rota['nome']); ?></h6>

					<?php if (empty($rota['pontos'])): ?>
						<p class="text-muted">Esta rota não possui pontos de entrega ativos.</p>
					<?php endif; ?>

					<?php foreach ($rota['pontos'] as $ponto): ?>
						<div class="card mb-2">
							<div class="card-body py-2">
								<label class="form-label mb-1">
									<?php echo htmlspecialchars($ponto->nome); ?>
									<?php if ($ponto->endereco): ?>
										<small class="text-muted">- <?php echo htmlspecialchars($ponto->endereco); ?></small>
									<?php endif; ?>
								</label>
								<div class="input-group">
									<textarea name="pontos[<?php echo $ponto->id; ?>]" class="form-control campo-recipientes" rows="2"
										placeholder="Códigos dos recipientes (ex: REC-000001, REC-000002) - separe por vírgula, espaço ou linha"></textarea>
									<button type="button" class="btn btn-outline-secondary btn-scan-qr" data-target="pontos[<?php echo $ponto->id; ?>]">
										<i class="bi bi-qr-code-scan"></i> Escanear
									</button>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>

			<div class="mt-4">
				<button type="submit" class="btn btn-primary">Registrar saída</button>
				<a href="<?php echo site_url('saidas'); ?>" class="btn btn-link">Cancelar</a>
			</div>

		<?php echo form_close(); ?>
	</div>
</div>

<script>
document.getElementById('select-rota').addEventListener('change', function () {
	document.querySelectorAll('.rota-pontos').forEach(function (div) {
		div.style.display = (div.dataset.rotaId === this.value) ? 'block' : 'none';
	}.bind(this));
});
</script>
