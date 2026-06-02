<?php
require_once 'Persona.php';
// Clase para ADOPTANTES (Sin login)
class Adoptante extends Persona { 
  // Persona, clase padre
  // Adoptante, clase que hereda de persona
  public function __construct($id, $nombres, $apellidos, $email) {
    parent::__construct($id, $nombres, $apellidos, $email);
    // Crea un nuevo  Adoptante usando el constructor de persona
  }
}
?>