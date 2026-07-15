<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login - Controle de Recipientes</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark d-flex align-items-center" style="min-height: 100vh;">
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-5 col-lg-4">
			<div class="card shadow">
				<div class="card-body p-4">
					<h1 class="h4 text-center mb-4">
						<i class="bi bi-box-seam"></i><br>
						Controle de Recipientes
					</h1>

					<?php if (! empty($erro)): ?>
						<div class="alert alert-danger py-2"><?php echo htmlspecialchars($erro); ?></div>
					<?php endif; ?>

					<?php if (validation_errors()): ?>
						<div class="alert alert-danger py-2"><?php echo validation_errors(); ?></div>
					<?php endif; ?>

					<?php echo form_open('login'); ?>
						<div class="mb-3">
							<label class="form-label">E-mail</label>
							<input type="email" name="email" class="form-control" value="<?php echo set_value('email'); ?>" required autofocus>
						</div>
						<div class="mb-3">
							<label class="form-label">Senha</label>
							<input type="password" name="senha" class="form-control" required>
						</div>
						<button type="submit" class="btn btn-primary w-100">Entrar</button>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
