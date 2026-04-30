<?php
class Persona {
  private $id;
  private $nombres;
  private $apellidos;
  private $email;

  public function __construct($id, $nombres, $apellidos, $email) {
    $this->id = $id;
    $this->nombres = $nombres;
    $this->apellidos = $apellidos;
    $this->email = $email;
  }

  // Getters
  public function getId() { return $this->id; }
  public function getNombres() { return $this->nombres; }
  public function getApellidos() { return $this->apellidos; }
  public function getEmail() { return $this->email; }

  // Ejemplo de método de lógica
  public function getNombreCompleto() {
    return $this->nombres . " " . $this->apellidos;
  }
}
?>