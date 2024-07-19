// Configuración de Dropzone para la carga de archivos
Dropzone.options.dataDropzone = {
    paramName: "file",
    maxFilesize: 5,
    maxFiles: null,
    acceptedFiles: ".jpg,.png,.jpeg,.gif",
    addRemoveLinks: true,
    dictDefaultMessage: "Arrastra archivos aquí o haz clic para cargar",
    dictRemoveFile: "Eliminar",
    dictFileTooBig: "El archivo es demasiado grande ({{filesize}} MB). Tamaño máximo permitido: {{maxFilesize}} MB.",
    init: function () {
        this.on("addedfile", function (file) {
            // Lógica adicional al añadir un archivo si es necesario
        });
        this.on("removedfile", function (file) {
            // Lógica para eliminar un archivo si es necesario
        });
        this.on("thumbnail", function (file, dataUrl) {
            // Mostrar miniatura del archivo
            $('.dz-preview').last().find('img').attr({src: dataUrl});
        });
    }
};
