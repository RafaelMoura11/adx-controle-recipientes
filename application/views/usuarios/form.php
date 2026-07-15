<?php $editando = isset($usuario) && $usuario; ?>

<h1 class="h3 mb-4"><?php echo $editando ? 'Editar usuario' : 'Novo usuario'; ?></h1>

<?php if (! empty($erro)): ?>
	<div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div>
<?php endif; ?>

<?php if (validation_errors()): ?>
	<div class="alert alert-danger"><?php echo validation_errors(); ?></div>
<?php endif; ?>

<div class="card">
	<div class="card-body">
		<?php echo form_open($editando ? 'usuarios/atualizar/'.$usuario->id : 'usuarios/criar'); ?>

			<div class="row g-3">
				<div class="col-md-6">
					<label class="form-label">Nome</label>
					<input type="text" name="nome" class="form-control" required
						value="<?php echo htmlspecialchars($editando ? $usuario->nome : set_value('nome')); ?>">
				</div>
				<div class="col-md-6">
					<label class="form-label">E-mail</label>
					<input type="email" name="email" class="form-control" required
						value="<?php echo htmlspecialchars($editando ? $usuario->email : set_value('email')); ?>">
				</div>
				<div class="col-md-4">
					<label class="form-label">Tipo de usuário</label>
					<?php $tipo_atual = $editando ? $usuario->tipo_usuario : set_value('tipo_usuario'); ?>
					<select name="tipo_usuario" class="form-select" required>
						<option value="">Selecione</option>
						<option value="administrador" <?php echo $tipo_atual === 'administrador' ? 'selected' : ''; ?>>Administrador</option>
						<option value="operador" <?php echo $tipo_atual === 'operador' ? 'selected' : ''; ?>>Operador</option>
						<option value="motorista" <?php echo $tipo_atual === 'motorista' ? 'selected' : ''; ?>>Motorista</option>
					</select>
				</div>
				<div class="col-md-4">
					<label class="form-label">Telefone</label>
					<input type="text" name="telefone" class="form-control"
						value="<?php echo htmlspecialchars($editando ? $usuario->telefone : set_value('telefone')); ?>">
				</div>
				<div class="col-md-4">
					<label class="form-label">CNH (motorista)</label>
					<input type="text" name="cnh" class="form-control"
						value="<?php echo htmlspecialchars($editando ? $usuario->cnh : set_value('cnh')); ?>">
				</div>
				<div class="col-md-6">
					<label class="form-label">Senha <?php echo $editando ? '(deixe em branco para manter)' : ''; ?></label>
					<input type="password" name="senha" class="form-control" <?php echo $editando ? '' : 'required'; ?> minlength="6">
				</div>
			</div>

			<div class="mt-4">
				<button type="submit" class="btn btn-primary">Salvar</button>
				<a href="<?php echo site_url('usuarios'); ?>" class="btn btn-link">Cancelar</a>
			</div>

		<?php echo form_close(); ?>
	</div>
</div>
