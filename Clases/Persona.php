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

    public function getId() { return $this->id; }
    public function getNombreCompleto() { return $this->nombres . " " . $this->apellidos; }
    public function getEmail() { return $this->email; }
}
?>