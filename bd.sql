-- Eliminar tablas si existen (en orden inverso a su creación para evitar errores de FK)
DROP TABLE IF EXISTS Historial_Medico CASCADE;
DROP TABLE IF EXISTS Adopciones CASCADE;
DROP TABLE IF EXISTS Vacunas CASCADE;
DROP TABLE IF EXISTS Gatos CASCADE;
DROP TABLE IF EXISTS Usuarios CASCADE;

-- Extensión para UUID si se desea, o usar SERIAL para IDs simples
CREATE TABLE Usuarios (
    id_usuario SERIAL PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255),
    rol VARCHAR(50) -- 'admin', 'voluntario', 'adoptante'
);

CREATE TABLE Gatos (
    id_gato SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    fecha_nacimiento DATE,
    edad INTEGER,
    genero VARCHAR(10),
    raza VARCHAR(50),
    esterilizado BOOLEAN,
    descripcion TEXT,
    estado VARCHAR(20), -- 'disponible', 'adoptado', 'en tratamiento'
    foto_url VARCHAR(255)
);

CREATE TABLE Adopciones (
    id_adopcion SERIAL PRIMARY KEY,
    id_usuario INTEGER REFERENCES Usuarios(id_usuario),
    id_gato INTEGER REFERENCES Gatos(id_gato),
    fecha_adopcion DATE DEFAULT CURRENT_DATE,
    observaciones TEXT
);

CREATE TABLE Vacunas (
    id_vacuna SERIAL PRIMARY KEY,
    nombre_vacuna VARCHAR(100) NOT NULL,
    fecha_vacuna DATE
);

CREATE TABLE Historial_Medico (
    id_historial SERIAL PRIMARY KEY,
    id_gato INTEGER REFERENCES Gatos(id_gato),
    id_vacuna INTEGER REFERENCES Vacunas(id_vacuna),
    fecha_revision DATE
);
-----------------------------------------------------------
INSERT INTO Usuarios (nombres, apellidos, email, password, rol) VALUES
-- 1 Administrador
('Elena', 'Rodríguez Martínez', 'admin@refugio.com', 'admin_hash_123', 'admin'),

-- 2 Trabajadoras (Voluntarias)
('Marta', 'García López', 'marta.vol@refugio.com', 'marta_pass_456', 'voluntario'),
-- Nota: Uso 'voluntario' siguiendo tu comentario previo, pero puedes cambiarlo a 'trabajadora'
('Sofía', 'Pérez Ruiz', 'sofia.vol@refugio.com', 'sofia_pass_789', 'voluntario'),

-- 4 Clientes (Sin password)
('Carlos', 'Sánchez Villa', 'carlos.sv@email.com', NULL, 'adoptante'),
('Laura', 'Gómez Fer', 'laura.gomez@email.com', NULL, 'adoptante'),
('Diego', 'Torres Marín', 'diego.tm@email.com', NULL, 'adoptante'),
('Ana Belén', 'Cano Saura', 'ana.belen@email.com', NULL, 'adoptante');

INSERT INTO Gatos (nombre, fecha_nacimiento, edad, genero, raza, esterilizado, descripcion, estado, foto_url) VALUES
('Luna', '2022-05-10', 2, 'Hembra', 'Común Europeo', true, 'Muy cariñosa y juguetona.', 'disponible', 'Imagenes/Luna.jpg'),
('Simba', '2021-08-15', 3, 'Macho', 'Tabby', true, 'Un poco tímido al principio, pero muy leal.', 'disponible', 'Imagenes/Simba.jpg'),
('Oliver', '2023-01-20', 1, 'Macho', 'Persa', false, 'Le encanta dormir al sol.', 'en tratamiento', 'Imagenes/Oliver.jpg'),
('Mia', '2020-11-30', 3, 'Hembra', 'Siamés', true, 'Maúlla mucho para pedir mimos.', 'adoptado', 'Imagenes/Mia.jpg'),
('Bella', '2023-06-12', 0, 'Hembra', 'Mestizo', false, 'Cachorrita con mucha energía.', 'disponible', 'Imagenes/Bella.jpg'),
('Leo', '2019-03-05', 5, 'Macho', 'Maine Coon', true, 'Un gigante noble y tranquilo.', 'disponible', 'Imagenes/Leo.jpg'),
('Chloe', '2022-09-21', 1, 'Hembra', 'Ragdoll', true, 'Pelo muy suave y ojos azules.', 'disponible', 'Imagenes/Chloe.jpg'),
('Jack', '2018-07-14', 6, 'Macho', 'Común Europeo', true, 'Rescatado de la calle, busca calma.', 'disponible', 'Imagenes/Jack.jpg'),
('Kitty', '2023-04-02', 1, 'Hembra', 'Bengala', false, 'Muy activa, necesita espacio para saltar.', 'en tratamiento', 'Imagenes/Kitty.jpg'),
('Loki', '2021-12-10', 2, 'Macho', 'Sphynx', true, 'Gato sin pelo, muy sociable y caluroso.', 'disponible', 'Imagenes/Loki.jpg'),
('Lucy', '2022-02-28', 2, 'Hembra', 'Ruso Azul', true, 'Elegante y silenciosa.', 'adoptado', 'Imagenes/Lucy.jpg'),
('Charlie', '2020-05-15', 4, 'Macho', 'Bosque de Noruega', true, 'Le encanta que lo cepillen.', 'disponible', 'Imagenes/Charlie.jpg'),
('Rocky', '2023-02-10', 1, 'Macho', 'Mestizo', true, 'Valiente y siempre atento.', 'disponible', 'Imagenes/Rocky.jpg'),
('Sophie', '2019-10-10', 4, 'Hembra', 'Angora', true, 'Una reina que busca su trono.', 'disponible', 'Imagenes/Sophie.jpg'),
('Milo', '2024-01-05', 0, 'Macho', 'Común Europeo', false, 'Bebé rescatado hace una semana.', 'disponible', 'Imagenes/Milo.jpg');

-- Insertar vacunas básicas
INSERT INTO Vacunas (nombre_vacuna, fecha_vacuna) VALUES 
('Trivalente', '2024-01-01'),
('Leucemia', '2024-01-01'),
('Rabia', '2024-01-01');

-- Relacionar a Luna (ID 1) con una vacuna en el Historial
INSERT INTO Historial_Medico (id_gato, id_vacuna, fecha_revision) VALUES 
(1, 1, '2024-03-15');

-- Un registro de adopción (Carlos Ruiz adopta a Mia)
INSERT INTO Adopciones (id_usuario, id_gato, fecha_adopcion, observaciones) VALUES 
(4, 4, '2024-04-20', 'Familia con experiencia previa en gatos siameses.');

ALTER TABLE Adopciones 
ADD COLUMN cita1_ok BOOLEAN DEFAULT FALSE,
ADD COLUMN cita2_ok BOOLEAN DEFAULT FALSE;