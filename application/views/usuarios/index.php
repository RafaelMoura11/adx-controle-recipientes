<div class="d-flex justify-content-between align-items-center mb-3">
	<h1 class="h3 mb-0">Usuarios</h1>
	<a href="<?php echo site_url('usuarios/novo'); ?>" class="btn btn-primary">
		<i class="bi bi-plus-lg"></i> Novo usuario
	</a>
</div>

<div class="card">
	<div class="table-responsive">
		<table class="table table-hover mb-0 align-middle">
			<thead class="table-light">
				<tr>
					<th>Nome</th>
					<th>E-mail</th>
					<th>Tipo</th>
					<th>Situacao</th>
					<th class="text-end">Acoes</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($usuarios as $usuario): ?>
				<tr>
					<td><?php echo htmlspecialchars($usuario->nome); ?></td>
					<td><?php echo htmlspecialchars($usuario->email); ?></td>
					<td><span class="badge bg-secondary text-uppercase"><?php echo htmlspecialchars($usuario->tipo_usuario); ?></span></td>
					<td>
						<?php if ($usuario->situacao === 'ativo'): ?>
							<span class="badge bg-success">Ativo</span>
						<?php else: ?>
							<span class="badge bg-danger">Bloqueado</span>
						<?php endif; ?>
					</td>
					<td class="text-end">
						<a href="<?php echo site_url('usuarios/editar/'.$usuario->id); ?>" class="btn btn-sm btn-outline-secondary">Editar</a>
						<?php if ($usuario->situacao === 'ativo'): ?>
							<a href="<?php echo site_url('usuarios/bloquear/'.$usuario->id); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bloquear este usuario?');">Bloquear</a>
						<?php else: ?>
							<a href="<?php echo site_url('usuarios/desbloquear/'.$usuario->id); ?>" class="btn btn-sm btn-outline-success">Desbloquear</a>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
