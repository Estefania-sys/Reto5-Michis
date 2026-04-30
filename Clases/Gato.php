<?php
class Gato {
  private $id;
  private $nombre;
  private $fecha_nacimiento;
  private $edad;
  private $genero;
  private $raza;
  private $esterilizado;
  private $descripcion;
  private $estado;
  private $foto_url;

  public function __construct($id, $nombre, $fecha_nacimiento, $edad, $genero, $raza, $esterilizado, $descripcion, $estado, $foto_url) {
    $this->id = $id;
    $this->nombre = $nombre;
    $this->fecha_nacimiento = $fecha_nacimiento;
    $this->edad = $edad;
    $this->genero = $genero;
    $this->raza = $raza;
    $this->esterilizado = $esterilizado;
    $this->descripcion = $descripcion;
    $this->estado = $estado;
    $this->foto_url = $foto_url;
  }

  // Getters necesarios para mostrar la info en la Web o generar JSON
  public function getId() { return $this->id; }
  public function getNombre() { return $this->nombre; }
  public function getEstado() { return $this->estado; }

  // Método para el requisito (f): Generar JSON
  public function expose() {
    return get_object_vars($this);
  }
}
?>