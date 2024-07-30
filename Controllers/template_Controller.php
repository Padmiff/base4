<?php
class ControllerTemplate
{
    /**
     * Incluye la plantilla principal de la vista.
     * 
     * Este método carga el archivo `template.php` desde la carpeta `Views`, 
     * que contiene la estructura base de la aplicación, incluyendo los componentes comunes como el header, footer, etc.
     */
    public function controllerTemplate()
    {
        include "Views/template.php";
    }
}
