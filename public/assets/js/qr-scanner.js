(function () {
	'use strict';

	var modalEl = document.getElementById('modal-qr-scanner');
	if (!modalEl || typeof Html5Qrcode === 'undefined') {
		return;
	}

	var modal = new bootstrap.Modal(modalEl);
	var html5QrCode = null;
	var targetTextarea = null;

	function extrairCodigo(textoLido) {
		var texto = textoLido.trim();

		// Se o QR contiver uma URL (ex: http://.../recipientes/detalhe/REC-000001),
		// usa apenas o ultimo segmento do caminho como codigo.
		if (/^https?:\/\//i.test(texto)) {
			var partes = texto.split('/').filter(Boolean);
			texto = partes[partes.length - 1];
		}

		return texto.toUpperCase();
	}

	function adicionarCodigo(textarea, codigo) {
		var valorAtual = textarea.value.trim();
		var codigosExistentes = valorAtual.length
			? valorAtual.split(/[\s,;]+/).filter(Boolean)
			: [];

		if (codigosExistentes.indexOf(codigo) === -1) {
			codigosExistentes.push(codigo);
		}

		textarea.value = codigosExistentes.join(', ');
	}

	function pararScanner() {
		if (html5QrCode) {
			html5QrCode.stop().catch(function () {}).finally(function () {
				html5QrCode.clear();
				html5QrCode = null;
			});
		}
	}

	document.querySelectorAll('.btn-scan-qr').forEach(function (botao) {
		botao.addEventListener('click', function () {
			var nomeAlvo = botao.getAttribute('data-target');
			targetTextarea = document.querySelector('[name="' + nomeAlvo + '"]');

			if (!targetTextarea) {
				return;
			}

			modal.show();

			html5QrCode = new Html5Qrcode('qr-scanner-reader');
			html5QrCode.start(
				{ facingMode: 'environment' },
				{ fps: 10, qrbox: { width: 220, height: 220 } },
				function (textoDecodificado) {
					adicionarCodigo(targetTextarea, extrairCodigo(textoDecodificado));
					pararScanner();
					modal.hide();
				},
				function () {
					// erro de leitura em um frame especifico: ignorado, tenta o proximo frame.
				}
			).catch(function () {
				modal.hide();
				alert('Nao foi possivel acessar a camera. Verifique as permissoes do navegador ou digite o codigo manualmente.');
			});
		});
	});

	modalEl.addEventListener('hidden.bs.modal', pararScanner);
})();
