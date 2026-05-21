<?php
// Clase para ADOPTANTES (Sin login)
class Cliente extends Persona { 
  // Persona, clase padre
  // Cliente, clase que hereda de persona
  public function __construct($id, $nombres, $apellidos, $email) {
    parent::__construct($id, $nombres, $apellidos, $email);
    // Crea un nuevo  cliente usando el constructor de persona
  }
}
?>