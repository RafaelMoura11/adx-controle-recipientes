<h1 class="h3 mb-4">Registrar entrada</h1>

<?php if (! empty($erro)): ?>
	<div class="alert alert-danger"><?php echo $erro; ?></div>
<?php endif; ?>

<div class="card">
	<div class="card-body">
		<?php echo form_open('entradas/criar'); ?>

			<div class="row g-3 mb-3">
				<div class="col-md-6">
					<label class="form-label">Motorista que fez a devolução</label>
					<select name="motorista_id" class="form-select">
						<option value="">Não informado</option>
						<?php foreach ($motoristas as $m): ?>
							<option value="<?php echo $m->id; ?>"><?php echo htmlspecialchars($m->nome); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-6">
					<label class="form-label">Data/hora da entrada</label>
					<input type="datetime-local" name="data_hora_entrada" class="form-control" required
						value="<?php echo date('Y-m-d\TH:i'); ?>">
				</div>
				<div class="col-12">
					<label class="form-label">Recipientes retornando ao estoque</label>
					<div class="input-group">
						<textarea name="recipientes" id="campo-recipientes-entrada" class="form-control campo-recipientes" rows="4"
							placeholder="Códigos dos recipientes (ex: REC-000001, REC-000002) - separe por vírgula, espaço ou linha" required></textarea>
						<button type="button" class="btn btn-outline-secondary btn-scan-qr" data-target="recipientes">
							<i class="bi bi-qr-code-scan"></i> Escanear
						</button>
					</div>
				</div>
				<div class="col-12">
					<label class="form-label">Observações</label>
					<textarea name="observacoes" class="form-control" rows="2"></textarea>
				</div>
			</div>

			<div class="mt-4">
				<button type="submit" class="btn btn-primary">Registrar entrada</button>
				<a href="<?php echo site_url('entradas'); ?>" class="btn btn-link">Cancelar</a>
			</div>

		<?php echo form_close(); ?>
	</div>
</div>
