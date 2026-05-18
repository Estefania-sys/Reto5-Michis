-- Eliminar tablas si existen (en orden inverso a su creación para evitar errores de FK)
DROP TABLE IF EXISTS Historial_Medico CASCADE;
DROP TABLE IF EXISTS Adopciones CASCADE;
DROP TABLE IF EXISTS Vacunas CASCADE;
DROP TABLE IF EXISTS Gatos CASCADE;
DROP TABLE IF EXISTS Usuarios CASCADE;

-- usar SERIAL para IDs simples
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
    genero VARCHAR(10),
    raza VARCHAR(50),
    capa_patron VARCHAR(50),
    pelo_largo VARCHAR(50),
    character_tags TEXT[],
    esterilizado BOOLEAN,
    notas_cuidador TEXT,
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

-- 3 Adoptantes
('Carlos', 'Sánchez Villa', 'carlos.sv@email.com', NULL, 'adoptante'),
('Laura', 'Gómez Fer', 'laura.gomez@email.com', NULL, 'adoptante'),
('Diego', 'Torres Marín', 'diego.tm@email.com', NULL, 'adoptante'),
('Ana Belén', 'Cano Saura', 'ana.belen@email.com', NULL, 'adoptante');

INSERT INTO Gatos (nombre, fecha_nacimiento, genero, raza, capa_patron, pelo_largo, character_tags, esterilizado, notas_cuidador, estado, foto_url) VALUES
('Luna', '2022-05-10', 'Hembra', 'Común Europeo', 'Atigrada', 'Corto', ARRAY['Cariñosa','Juguetona'], true, 'Muy cariñosa y juguetona, ideal para hogares que le gustan los mimos.', 'disponible', 'Imagenes/Gatos/1_luna'),
('Simba', '2021-08-15', 'Macho', 'Tabby', 'Atigrado', 'Corto', ARRAY['Leal','Tímido'], true, 'Un poco tímido al principio, pero muy leal con quienes confía.', 'disponible', 'Imagenes/Gatos/2_simba'),
('Oliver', '2023-01-20', 'Macho', 'Persa', 'Sólida', 'Largo', ARRAY['Dormilón','Tranquilo'], false, 'Le encanta dormir al sol y busca un hogar tranquilo.', 'en tratamiento', 'Imagenes/Gatos/3_oliver'),
('Mia', '2020-11-30', 'Hembra', 'Siamés', 'Point', 'Corto', ARRAY['Vocal','Cariñosa'], true, 'Maúlla mucho para pedir mimos y es muy inteligente.', 'adoptado', 'Imagenes/Gatos/4_mia'),
('Bella', '2023-06-12', 'Hembra', 'Gato Doméstico', 'Carey', 'Corto', ARRAY['Juguetona','Activa'], false, 'Gatito con mucha energía y ganas de jugar.', 'disponible', 'Imagenes/Gatos/5_bella'),
('Leo', '2019-03-05', 'Macho', 'Maine Coon', 'Moteada', 'Largo', ARRAY['Noble','Tranquilo'], true, 'Un gigante noble y tranquilo que disfruta de la compañía humana.', 'disponible', 'Imagenes/Gatos/6_leo'),
('Chloe', '2022-09-21', 'Hembra', 'Ragdoll', 'Bicolor', 'Largo', ARRAY['Suave','Dulce'], true, 'Pelo muy suave y ojos azules. Le encanta acariciarse.', 'disponible', 'Imagenes/Gatos/7_chloe'),
('Jack', '2018-07-14', 'Macho', 'Gato Doméstico', 'Atigrado', 'Corto', ARRAY['Calmado','Afectuoso'], true, 'Rescatado de la calle, busca calma y cariño.', 'disponible', 'Imagenes/Gatos/8_jack'),
('Kitty', '2023-04-02', 'Hembra', 'Bengala', 'Manchado', 'Corto', ARRAY['Activa','Enérgica'], false, 'Muy activa, necesita espacio y juguetes para saltar.', 'en tratamiento', 'Imagenes/Gatos/9_kitty'),
('Loki', '2021-12-10', 'Macho', 'Sphynx', 'Liso', 'Corto', ARRAY['Sociable','Cariñoso'], true, 'Gato sin pelo, muy sociable y cariñoso con las personas.', 'disponible', 'Imagenes/Gatos/10_loki'),
('Lucy', '2022-02-28', 'Hembra', 'Ruso Azul', 'Sólida', 'Corto', ARRAY['Elegante','Reservada'], true, 'Elegante y silenciosa, con una presencia muy especial.', 'adoptado', 'Imagenes/Gatos/11_lucy'),
('Donnatella', '2020-05-15', 'Hembra', 'Gato Doméstico', 'Bicolor', 'Largo', ARRAY['Amistoso','Paciente'], true, 'Le encanta que lo cepillen y disfruta de las caricias.', 'disponible', 'Imagenes/Gatos/12_donnatella'),
('Freya', '2017-07-16', 'Hembra', 'Común Europeo', 'Atigrada', 'Corto', ARRAY['Valiente','Leal'], true, 'Valiente y siempre atenta. Con algo de carácter pero muy leal.', 'disponible', 'Imagenes/Gatos/13_freya'),
('Sophie', '2019-10-10', 'Hembra', 'Angora', 'Blanca', 'Largo', ARRAY['Regia','Carismática'], true, 'Una reina que busca su trono y mucho mimo.', 'disponible', 'Imagenes/Gatos/14_sophie'),
('Milo', '2024-01-05', 'Macho', 'Común Europeo', 'Atigrado', 'Corto', ARRAY['Curioso','Tierno'], false, 'Bebé rescatado hace una semana, muy curioso y cariñoso.', 'disponible', 'Imagenes/Gatos/15_milo');

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