<?php
class Persona {
    private $id;
    private $nombres;
    private $apellidos;
    private $email;
    private $dni;
    private $fecha_nacimiento;
    private $direccion;
    private $poblacion;
    private $cp;
    private $telefono;

    public function __construct($id, $nombres, $apellidos, $email, $dni = null, $fecha_nacimiento = null, $direccion = null, $poblacion = null, $cp = null, $telefono = null) {
        $this->id = $id;
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->email = $email;
        $this->dni = $dni;
        $this->fecha_nacimiento = $fecha_nacimiento;
        $this->direccion = $direccion;
        $this->poblacion = $poblacion;
        $this->cp = $cp;
        $this->telefono = $telefono;
    }

    public function getId() { return $this->id; }
    public function getNombreCompleto() { return $this->nombres . " " . $this->apellidos; }
    public function getEmail() { return $this->email; }
    public function getDni() { return $this->dni; }
    public function getFechaNacimiento() { return $this->fecha_nacimiento; }
    public function getDireccion() { return $this->direccion; }
    public function getPoblacion() { return $this->poblacion; }
    public function getCp() { return $this->cp; }
    public function getTelefono() { return $this->telefono; }
}
?>