<?php
class Adopcion {
  private $id_adopcion;
  private $id_usuario;
  private $id_gato;
  private $fecha;
  private $observaciones;

  public function __construct($id_adopcion, $id_usuario, $id_gato, $fecha, $observaciones) {
    $this->id_adopcion = $id_adopcion;
    $this->id_usuario = $id_usuario;
    $this->id_gato = $id_gato;
    $this->fecha = $fecha;
    $this->observaciones = $observaciones;
  }

  public function getIdAdopcion() { return $this->id_adopcion; }
}
?>