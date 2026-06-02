<?php

// http://docs.phpunit.de/en/12.5/assertions.html

require_once 'Persona.php';
use PHPUnit\Framework\TestCase;

class PersonaTest extends TestCase {

    private Persona $persona;

    protected function setUp(): void {
        $this->persona = new Persona(
            1,
            'Juan',
            'García López',
            'juan@example.com',
            '12345678A',
            '1990-05-15',
            'Calle Mayor 10',
            'Zaragoza',
            '50001',
            '612345678'
        );
    }

    public function testGetId(): void {
        $this->assertEquals(1, $this->persona->getId());
    }

    public function testGetNombreCompleto(): void {
        $this->assertEquals('Juan García López', $this->persona->getNombreCompleto());
    }

    public function testGetEmail(): void {
        $this->assertEquals('juan@example.com', $this->persona->getEmail());
    }

    public function testGetDni(): void {
        $this->assertEquals('12345678A', $this->persona->getDni());
    }

    public function testGetFechaNacimiento(): void {
        $this->assertEquals('1990-05-15', $this->persona->getFechaNacimiento());
    }

    public function testGetDireccion(): void {
        $this->assertEquals('Calle Mayor 10', $this->persona->getDireccion());
    }

    public function testGetPoblacion(): void {
        $this->assertEquals('Zaragoza', $this->persona->getPoblacion());
    }

    public function testGetCp(): void {
        $this->assertEquals('50001', $this->persona->getCp());
    }

    public function testGetTelefono(): void {
        $this->assertEquals('612345678', $this->persona->getTelefono());
    }

    public function testOptionalFieldsAreNullByDefault(): void {
        $personaMinima = new Persona(2, 'Ana', 'Martínez', 'ana@example.com');

        $this->assertNull($personaMinima->getDni());
        $this->assertNull($personaMinima->getFechaNacimiento());
        $this->assertNull($personaMinima->getDireccion());
        $this->assertNull($personaMinima->getPoblacion());
        $this->assertNull($personaMinima->getCp());
        $this->assertNull($personaMinima->getTelefono());
    }
}