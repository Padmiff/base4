
document.addEventListener('DOMContentLoaded', () => {
  const dropdowns = document.querySelectorAll('.dropdown-toggle');
  dropdowns.forEach(dropdown => {
    dropdown.addEventListener('click', (e) => {
      e.preventDefault();
      const menu = dropdown.nextElementSibling;
      menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    });
  });

  // Cargar los departamentos al iniciar la página
  loadDepartamentos();
});

function loadDepartamentos() {
  var xhr = new XMLHttpRequest();
  xhr.open('GET', 'Views/Resources//php/departamentos.php', true);
  xhr.onload = function() {
    if (xhr.status === 200) {
      try {
        var departamentos = JSON.parse(xhr.responseText);
        var selectDepartamento = document.getElementById('departamento');

        departamentos.forEach(function(depto) {
          var option = document.createElement('option');
          option.value = depto.idDepto;
          option.textContent = depto.nombre;
          selectDepartamento.appendChild(option);
        });
      } catch (e) {
        console.error('Error al parsear JSON:', e);
      }
    } else {
      console.log('Error al obtener los departamentos:', xhr.status);
    }
  };
  xhr.onerror = function() {
    console.error('Error de red');
  };
  xhr.send();
}

function submitForm() {
  // Obtener los valores del formulario
  var N_empleado = document.getElementById('N_empleado').value.trim();
  var departamento = document.getElementById('departamento').value;
  var Nom_colab = document.getElementById('Nom_colab').value.trim();
  var apellido_paterno = document.getElementById('apellido_paterno').value.trim();
  var apellido_materno = document.getElementById('apellido_materno').value.trim();
  var correo = document.getElementById('correo').value.trim();
  var numero = document.getElementById('numero').value.trim();
  var aviso = document.getElementById('aviso').checked ? 'Aceptado' : 'No aceptado'; // Convertir a texto

  // Crear un objeto FormData para enviar los datos del formulario
  var formData = new FormData();
  formData.append('N_empleado', N_empleado);
  formData.append('departamento', departamento);
  formData.append('Nom_colab', Nom_colab);
  formData.append('apellido_paterno', apellido_paterno);
  formData.append('apellido_materno', apellido_materno);
  formData.append('correo', correo);
  formData.append('numero', numero);
  formData.append('aviso', aviso);

  // Añadir correos adicionales desde el almacenamiento local
  const extraEmails = JSON.parse(localStorage.getItem('extraEmails')) || [];
  formData.append('extraEmails', JSON.stringify(extraEmails));

  // Realizar la solicitud AJAX para enviar los datos al script PHP
  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'Views/Resources/php/insertar_colaborador.php', true);
  xhr.onload = function() {
    if (xhr.status === 200) {
      alert('Colaborador agregado correctamente.');
      // Aquí puedes realizar cualquier acción adicional después de insertar el colaborador
      // Por ejemplo, redireccionar a otra página o limpiar el formulario

      // Luego de insertar el colaborador, enviar los correos adicionales si existen
      sendExtraEmails(formData);
    } else {
      alert('Error al agregar colaborador. Código de estado: ' + xhr.status);
    }
  };
  xhr.onerror = function() {
    alert('Error de red al intentar agregar colaborador.');
  };

  // Enviar los datos del formulario
  xhr.send(formData);
}

function sendExtraEmails(formData) {
  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'Views/Resources/php/enviar.php', true);
  xhr.onload = function() {
    if (xhr.status === 200) {
      alert('Formulario enviado con éxito.');
    } else {
      alert('Error al enviar el formulario.');
    }
  };
  xhr.onerror = function() {
    alert('Error de red al intentar enviar el formulario.');
  };

  // Enviar datos del formulario junto con correos adicionales
  xhr.send(formData);
}

function showPreview() {
  // Obtener los valores del formulario
  const N_empleado = document.getElementById('N_empleado').value;
  const departamento = document.getElementById('departamento').value;
  const Nom_colab = document.getElementById('Nom_colab').value;
  const apellido_paterno = document.getElementById('apellido_paterno').value;
  const apellido_materno = document.getElementById('apellido_materno').value;
  const correo = document.getElementById('correo').value;
  const numero = document.getElementById('numero').value;

  // Establecer los valores en la vista previa
  document.getElementById('preview_N_empleado').textContent = N_empleado;
  document.getElementById('preview_departamento').textContent = departamento;
  document.getElementById('preview_Nom_colab').textContent = Nom_colab;
  document.getElementById('preview_apellido_paterno').textContent = apellido_paterno;
  document.getElementById('preview_apellido_materno').textContent = apellido_materno;
  document.getElementById('preview_correo').textContent = correo;
  document.getElementById('preview_numero').textContent = numero;

  // Mostrar la vista previa y ocultar el formulario
  document.getElementById('form1').style.display = 'none';
  document.getElementById('preview').style.display = 'block';
}

function editForm() {
  // Mostrar el formulario y ocultar la vista previa
  document.getElementById('form1').style.display = 'block';
  document.getElementById('preview').style.display = 'none';
}

function openEmailPopup() {
  window.open('Views/Pages/Forms/add_emails.html', 'Agregar Correos Adicionales', 'width=600,height=400');
}

function addEmailField(email = '') {
  const container = document.getElementById('emailsContainer');
  const emailField = document.createElement('div');
  emailField.classList.add('form-group');
  emailField.innerHTML = `
    <div class="input-group mb-3">
      <input type="email" class="form-control" name="extraEmail[]" value="${email}" placeholder="Correo adicional" required>
      <div class="input-group-append">
        <button class="btn btn-outline-secondary" type="button" onclick="editEmail(this)">Editar</button>
        <button class="btn btn-outline-danger" type="button" onclick="removeEmailField(this)">Eliminar</button>
      </div>
    </div>
  `;
  container.appendChild(emailField);
}

function editEmail(button) {
  const emailField = button.closest('.input-group').querySelector('input[type="email"]');
  emailField.readOnly = false;
  emailField.focus();
  button.textContent = 'Guardar';
  button.onclick = function() {
    emailField.readOnly = true;
    button.textContent = 'Editar';
    button.onclick = function() {
      editEmail(button);
    };
  };
}

function removeEmailField(button) {
  const emailField = button.closest('.form-group');
  emailField.remove();
}

function saveEmails(event) {
  event.preventDefault();
  const emails = [];
  const emailFields = document.querySelectorAll('input[name="extraEmail[]"]');
  emailFields.forEach(field => emails.push(field.value));
  localStorage.setItem('extraEmails', JSON.stringify(emails));
  alert('Correos guardados correctamente');
  window.close();
}

function loadEmails() {
  const emails = JSON.parse(localStorage.getItem('extraEmails')) || [];
  emails.forEach(email => addEmailField(email));
}

// Función para realizar la consulta y llenar los campos del formulario
function verificarColaborador() {
  var numColaborador = document.getElementById('N_empleado').value;
  
  // Realizar la petición AJAX para obtener datos del colaborador
  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'Views/Resources/php/buscar_colaborador.php', true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && xhr.status == 200) {
      var response = JSON.parse(xhr.responseText);
      if (response.success) {
        document.getElementById('departamento').value = response.data.departamento;
        document.getElementById('Nom_colab').value = response.data.nombrecolaborador;
        document.getElementById('apellido_paterno').value = response.data.apellidopaterno;
        document.getElementById('apellido_materno').value = response.data.apellidomaterno;
        document.getElementById('correo').value = response.data.emailcolaborador;
        document.getElementById('numero').value = response.data.numero_telefono;
      } else {
        alert('No se encontraron datos para el número de colaborador proporcionado.');
      }
    }
  };
  xhr.send('N_colaborador=' + numColaborador);
}



