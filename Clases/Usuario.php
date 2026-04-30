<?php
// Clase para los que tienen LOGIN (Admin y Trabajadoras)
class Usuario extends Persona {
  private $password;
  private $rol;

  public function __construct($id, $nombres, $apellidos, $email, $password, $rol) {
    parent::__construct($id, $nombres, $apellidos, $email);
    $this->password = $password;
    $this->rol = $rol;
  }

  public function getRol() { return $this->rol; }
  public function getPassword() { return $this->password; }
}

// Clase para ADOPTANTES (Sin login)
class Cliente extends Persona {
  public function __construct($id, $nombres, $apellidos, $email) {
    parent::__construct($id, $nombres, $apellidos, $email);
  }
}
?>