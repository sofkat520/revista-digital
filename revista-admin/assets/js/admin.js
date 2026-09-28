/* DDP Noticias · utilidades del panel */
(function () {
  'use strict';

  /* Copiar al portapapeles (botones "Copiar URL" de la galería). */
  document.addEventListener('click', function (ev) {
    const boton = ev.target.closest('[data-copiar]');
    if (!boton) return;

    const texto = boton.getAttribute('data-copiar');
    const original = boton.innerHTML;

    const confirmar = () => {
      boton.innerHTML = '<i class="fa-solid fa-check"></i> Copiado';
      boton.classList.add('btn-success');
      setTimeout(() => {
        boton.innerHTML = original;
        boton.classList.remove('btn-success');
      }, 1600);
    };

    if (navigator.clipboard && window.isSecureContext) {
      navigator.clipboard.writeText(texto).then(confirmar);
    } else {
      const campo = document.createElement('textarea');
      campo.value = texto;
      campo.setAttribute('readonly', '');
      campo.style.position = 'absolute';
      campo.style.left = '-9999px';
      document.body.appendChild(campo);
      campo.select();
      document.execCommand('copy');
      document.body.removeChild(campo);
      confirmar();
    }
  });

  /* Confirmación antes de borrar. */
  document.addEventListener('submit', function (ev) {
    const form = ev.target.closest('[data-confirmar]');
    if (!form) return;
    if (!window.confirm(form.getAttribute('data-confirmar'))) {
      ev.preventDefault();
    }
  });

  /* Vista previa de la imagen elegida antes de subirla. */
  document.querySelectorAll('[data-previsualizar]').forEach(function (input) {
    const destino = document.querySelector(input.getAttribute('data-previsualizar'));
    if (!destino) return;
    input.addEventListener('change', function () {
      const archivo = input.files && input.files[0];
      if (!archivo) return;
      destino.src = URL.createObjectURL(archivo);
      destino.classList.remove('d-none');
    });
  });
})();
