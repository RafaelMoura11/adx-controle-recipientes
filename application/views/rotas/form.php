<?php $editando = isset($rota) && $rota; ?>

<h1 class="h3 mb-4"><?php echo $editando ? 'Editar rota' : 'Nova rota'; ?></h1>

<?php if (validation_errors()): ?>
	<div class="alert alert-danger"><?php echo validation_errors(); ?></div>
<?php endif; ?>

<div class="card mb-4">
	<div class="card-body">
		<?php echo form_open($editando ? 'rotas/atualizar/'.$rota->id : 'rotas/criar'); ?>

			<div class="row g-3">
				<div class="col-md-6">
					<label class="form-label">Nome da rota</label>
					<input type="text" name="nome" class="form-control" required
						value="<?php echo htmlspecialchars($editando ? $rota->nome : set_value('nome')); ?>">
				</div>
				<div class="col-md-6">
					<label class="form-label">Descrição</label>
					<input type="text" name="descricao" class="form-control"
						value="<?php echo htmlspecialchars($editando ? $rota->descricao : set_value('descricao')); ?>">
				</div>
				<?php if ($editando): ?>
				<div class="col-12">
					<div class="form-check">
						<input type="checkbox" name="ativa" value="1" class="form-check-input" id="ativa" <?php echo $rota->ativa ? 'checked' : ''; ?>>
						<label class="form-check-label" for="ativa">Rota ativa</label>
					</div>
				</div>
				<?php endif; ?>
			</div>

			<div class="mt-4">
				<button type="submit" class="btn btn-primary">Salvar</button>
				<a href="<?php echo site_url('rotas'); ?>" class="btn btn-link">Voltar</a>
			</div>

		<?php echo form_close(); ?>
	</div>
</div>

<?php if ($editando): ?>
<div class="card">
	<div class="card-header">Pontos de entrega</div>
	<div class="table-responsive">
		<table class="table mb-0 align-middle">
			<thead class="table-light">
				<tr>
					<th>Ordem</th>
					<th>Nome</th>
					<th>Endereço</th>
					<th>Situação</th>
					<th class="text-end">Ações</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($pontos as $ponto): ?>
				<tr>
					<td><?php echo (int) $ponto->ordem; ?></td>
					<?php echo form_open('rotas/editar_ponto/'.$ponto->id); ?>
						<td><input type="text" name="nome" value="<?php echo htmlspecialchars($ponto->nome); ?>" class="form-control form-control-sm" required></td>
						<td><input type="text" name="endereco" value="<?php echo htmlspecialchars($ponto->endereco); ?>" class="form-control form-control-sm"></td>
						<td>
							<?php if ($ponto->ativo): ?>
								<span class="badge bg-success">Ativo</span>
							<?php else: ?>
								<span class="badge bg-secondary">Inativo</span>
							<?php endif; ?>
						</td>
						<td class="text-end">
							<button type="submit" class="btn btn-sm btn-outline-secondary">Salvar</button>
					</form>
							<?php if ($ponto->ativo): ?>
								<a href="<?php echo site_url('rotas/desativar_ponto/'.$ponto->id); ?>" class="btn btn-sm btn-outline-danger">Desativar</a>
							<?php else: ?>
								<a href="<?php echo site_url('rotas/ativar_ponto/'.$ponto->id); ?>" class="btn btn-sm btn-outline-success">Ativar</a>
							<?php endif; ?>
						</td>
				</tr>
			<?php endforeach; ?>
			<?php if (empty($pontos)): ?>
				<tr><td colspan="5" class="text-center text-muted py-3">Nenhum ponto de entrega cadastrado ainda.</td></tr>
			<?php endif; ?>
			</tbody>
		</table>
	</div>
	<div class="card-body">
		<h6>Adicionar ponto de entrega</h6>
		<?php echo form_open('rotas/adicionar_ponto/'.$rota->id); ?>
			<div class="row g-2">
				<div class="col-md-4">
					<input type="text" name="nome" class="form-control" placeholder="Nome do ponto" required>
				</div>
				<div class="col-md-6">
					<input type="text" name="endereco" class="form-control" placeholder="Endereço (opcional)">
				</div>
				<div class="col-md-2">
					<button type="submit" class="btn btn-primary w-100">Adicionar</button>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
<?php endif; ?>
