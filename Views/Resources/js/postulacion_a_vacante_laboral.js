let currentStep = 0;
const formSteps = document.querySelectorAll('.form-step');
const previewSection = document.getElementById('preview');

function showStep(step) {
  formSteps.forEach((formStep, index) => {
    formStep.classList.toggle('form-step-active', index === step);
  });
}

function nextStep() {
  if (currentStep < formSteps.length - 1) {
    currentStep++;
    showStep(currentStep);
  }
}

function prevStep() {
  if (currentStep > 0) {
    currentStep--;
    showStep(currentStep);
  }
}

function showPreview() {
  formSteps[currentStep].style.display = 'none';
  previewSection.style.display = 'block';

  document.getElementById('preview_puesto_postula').innerText = document.getElementById('puesto_postula').value;
  document.getElementById('preview_nombre_completo').innerText = document.getElementById('nombre_completo').value;
  document.getElementById('preview_edad').innerText = document.getElementById('edad').value;
  document.getElementById('preview_sexo').innerText = document.getElementById('sexo').value;
  document.getElementById('preview_correo').innerText = document.getElementById('correo').value;
  document.getElementById('preview_telefono').innerText = document.getElementById('telefono').value;
  document.getElementById('preview_direccion').innerText = document.getElementById('direccion').value + ', ' +
    document.getElementById('colonia').value + ', ' +
    document.getElementById('ciudad').value + ', ' +
    document.getElementById('estado').value + ', ' +
    document.getElementById('codigo_postal').value;
  document.getElementById('preview_institucion_academica').innerText = document.getElementById('institucion_academica').value;
  document.getElementById('preview_grado_estudio').innerText = document.getElementById('grado_estudio').value;
  document.getElementById('preview_nombre_carrera').innerText = document.getElementById('nombre_carrera').value;
  document.getElementById('preview_experiencia_laboral').innerText = document.getElementById('experiencia_laboral').value;
  document.getElementById('preview_experiencia_puesto').innerText = document.getElementById('experiencia_puesto').value;
  document.getElementById('preview_documentacion').innerText = Array.from(document.querySelectorAll('input[type=checkbox]:checked')).map(checkbox => checkbox.value).join(', ');
  document.getElementById('preview_tipo_licencia').innerText = document.getElementById('tipo_licencia').value;
  document.getElementById('preview_pariente').innerText = document.querySelector('input[name="pariente"]:checked').value;
  document.getElementById('preview_nombre_pariente').innerText = document.getElementById('nombre_pariente').value;
}

function editForm() {
  previewSection.style.display = 'none';
  formSteps[currentStep].style.display = 'block';
}

function submitForm() {
  alert('Formulario enviado con éxito!');
  document.getElementById('form2').reset();
  currentStep = 0;
  showStep(currentStep);
  previewSection.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', () => {
  showStep(currentStep);
  Dropzone.autoDiscover = false;

  const myDropzone = new Dropzone('#my-dropzone', {
    url: '/upload',
    maxFiles: 1,
    addRemoveLinks: true,
    dictDefaultMessage: 'Arrastra y suelta tu archivo aquí o haz clic para seleccionarlo',
    init: function () {
      this.on('maxfilesexceeded', function(file) {
        this.removeAllFiles();
        this.addFile(file);
      });
    }
  });
});
