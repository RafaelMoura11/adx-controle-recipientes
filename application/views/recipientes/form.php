<?php $editando = isset($recipiente) && $recipiente; ?>

<h1 class="h3 mb-4"><?php echo $editando ? 'Editar recipiente '.htmlspecialchars($recipiente->codigo) : 'Novo recipiente'; ?></h1>

<?php if (validation_errors()): ?>
	<div class="alert alert-danger"><?php echo validation_errors(); ?></div>
<?php endif; ?>

<div class="card">
	<div class="card-body">
		<?php echo form_open($editando ? 'recipientes/atualizar/'.$recipiente->id : 'recipientes/criar'); ?>

			<div class="row g-3">
				<?php if ($editando): ?>
				<div class="col-md-4">
					<label class="form-label">Código</label>
					<input type="text" class="form-control" value="<?php echo htmlspecialchars($recipiente->codigo); ?>" disabled>
				</div>
				<?php endif; ?>
				<div class="col-md-<?php echo $editando ? '4' : '6'; ?>">
					<label class="form-label">Descricao</label>
					<input type="text" name="descricao" class="form-control"
						value="<?php echo htmlspecialchars($editando ? $recipiente->descricao : set_value('descricao')); ?>"
						placeholder="Ex: Recipiente térmico 10L">
				</div>
				<?php if ($editando): ?>
				<div class="col-md-4">
					<label class="form-label">Status</label>
					<select name="status" class="form-select" required>
						<?php foreach (array('estoque', 'em_uso', 'manutencao', 'inativo') as $opcao): ?>
							<option value="<?php echo $opcao; ?>" <?php echo $recipiente->status === $opcao ? 'selected' : ''; ?>><?php echo ucfirst(str_replace('_', ' ', $opcao)); ?></option>
						<?php endforeach; ?>
					</select>
					<div class="form-text">Alterar manualmente para "Manutenção"/"Inativo" fora do fluxo normal de saída/entrada.</div>
				</div>
				<?php endif; ?>
			</div>

			<div class="mt-4">
				<button type="submit" class="btn btn-primary">Salvar</button>
				<a href="<?php echo site_url('recipientes'); ?>" class="btn btn-link">Cancelar</a>
			</div>

		<?php echo form_close(); ?>
	</div>
</div>
