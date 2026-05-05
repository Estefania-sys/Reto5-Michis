<?php
// Clase para ADOPTANTES (Sin login)
class Cliente extends Persona {
  public function __construct($id, $nombres, $apellidos, $email) {
    parent::__construct($id, $nombres, $apellidos, $email);
  }
}
?>