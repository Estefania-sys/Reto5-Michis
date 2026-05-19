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
    rol VARCHAR(50), -- 'admin', 'voluntario', 'adoptante'
    dni VARCHAR(20),
    fecha_nacimiento DATE,
    direccion VARCHAR(255),
    poblacion VARCHAR(100),
    cp VARCHAR(10),
    telefono VARCHAR(25)
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
    foto_url VARCHAR(255),
    numero_microchip VARCHAR(25),
    peso_kg NUMERIC(4,1),
    tamano VARCHAR(20)
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
INSERT INTO Usuarios (nombres, apellidos, email, password, rol, dni, fecha_nacimiento, direccion, poblacion, cp, telefono) VALUES
-- 1 Administrador
('Elena', 'Rodríguez Martínez', 'admin@refugio.com', 'admin_hash_123', 'admin', '12345678A', '1985-04-12', 'Calle del Refugio 12', 'Valencia', '46001', '963000111'),

-- 2 Trabajadoras (Voluntarias)
('Marta', 'García López', 'marta.vol@refugio.com', 'marta_pass_456', 'voluntario', '23456789B', '1990-08-22', 'Avenida de los Gatos 8', 'Valencia', '46002', '612345678'),

-- 3 Adoptantes
('Carlos', 'Sánchez Villa', 'carlos.sv@email.com', NULL, 'adoptante', '34567890C', '1988-02-14', 'Calle Naranjos 21', 'Valencia', '46003', '651234567'),
('Laura', 'Gómez Fer', 'laura.gomez@email.com', NULL, 'adoptante', '45678901D', '1992-11-03', 'Plaza Mayor 5', 'Valencia', '46004', '698765432'),
('Diego', 'Torres Marín', 'diego.tm@email.com', NULL, 'adoptante', '56789012E', '1985-06-18', 'Camino Real 44', 'Valencia', '46005', '622334455'),
('Ana Belén', 'Cano Saura', 'ana.belen@email.com', NULL, 'adoptante', '67890123F', '1995-01-30', 'Calle Luna 18', 'Valencia', '46006', '633221144');

INSERT INTO Gatos (nombre, fecha_nacimiento, genero, raza, capa_patron, pelo_largo, character_tags, esterilizado, notas_cuidador, estado, foto_url, numero_microchip, peso_kg, tamano) VALUES
('Luna', '2022-05-10', 'Hembra', 'Común Europeo', 'Atigrada', 'Corto', ARRAY['Cariñosa','Juguetona'], true, 'Muy cariñosa y juguetona, ideal para hogares que le gustan los mimos.', 'disponible', 'Imagenes/Gatos/1_luna', '982000100001234', 4.2, 'Mediano'),
('Simba', '2021-08-15', 'Macho', 'Tabby', 'Atigrado', 'Corto', ARRAY['Leal','Tímido'], true, 'Un poco tímido al principio, pero muy leal con quienes confía.', 'disponible', 'Imagenes/Gatos/2_simba', '982000100001235', 5.4, 'Grande'),
('Oliver', '2023-01-20', 'Macho', 'Persa', 'Sólida', 'Largo', ARRAY['Dormilón','Tranquilo'], false, 'Le encanta dormir al sol y busca un hogar tranquilo.', 'en tratamiento', 'Imagenes/Gatos/3_oliver', '982000100001236', 3.7, 'Mediano'),
('Mia', '2020-11-30', 'Hembra', 'Siamés', 'Point', 'Corto', ARRAY['Vocal','Cariñosa'], true, 'Maúlla mucho para pedir mimos y es muy inteligente.', 'adoptado', 'Imagenes/Gatos/4_mia', '982000100001237', 2.9, 'Pequeño'),
('Bella', '2023-06-12', 'Hembra', 'Gato Doméstico', 'Carey', 'Corto', ARRAY['Juguetona','Activa'], false, 'Gatito con mucha energía y ganas de jugar.', 'disponible', 'Imagenes/Gatos/5_bella', '982000100001238', 2.6, 'Pequeño'),
('Leo', '2019-03-05', 'Macho', 'Maine Coon', 'Moteada', 'Largo', ARRAY['Noble','Tranquilo'], true, 'Un gigante noble y tranquilo que disfruta de la compañía humana.', 'disponible', 'Imagenes/Gatos/6_leo', '982000100001239', 6.8, 'Grande'),
('Chloe', '2022-09-21', 'Hembra', 'Ragdoll', 'Bicolor', 'Largo', ARRAY['Suave','Dulce'], true, 'Pelo muy suave y ojos azules. Le encanta acariciarse.', 'disponible', 'Imagenes/Gatos/7_chloe', '982000100001240', 4.9, 'Mediano'),
('Jack', '2018-07-14', 'Macho', 'Gato Doméstico', 'Atigrado', 'Corto', ARRAY['Calmado','Afectuoso'], true, 'Rescatado de la calle, busca calma y cariño.', 'disponible', 'Imagenes/Gatos/8_jack', '982000100001241', 5.8, 'Mediano'),
('Kitty', '2023-04-02', 'Hembra', 'Bengala', 'Manchado', 'Corto', ARRAY['Activa','Enérgica'], false, 'Muy activa, necesita espacio y juguetes para saltar.', 'en tratamiento', 'Imagenes/Gatos/9_kitty', '982000100001242', 2.7, 'Pequeño'),
('Loki', '2021-12-10', 'Macho', 'Sphynx', 'Liso', 'Corto', ARRAY['Sociable','Cariñoso'], true, 'Gato sin pelo, muy sociable y cariñoso con las personas.', 'disponible', 'Imagenes/Gatos/10_loki', '982000100001243', 3.2, 'Mediano'),
('Lucy', '2022-02-28', 'Hembra', 'Ruso Azul', 'Sólida', 'Corto', ARRAY['Elegante','Reservada'], true, 'Elegante y silenciosa, con una presencia muy especial.', 'adoptado', 'Imagenes/Gatos/11_lucy', '982000100001244', 4.5, 'Mediano'),
('Donnatella', '2020-05-15', 'Hembra', 'Gato Doméstico', 'Bicolor', 'Largo', ARRAY['Amistoso','Paciente'], true, 'Le encanta que lo cepillen y disfruta de las caricias.', 'disponible', 'Imagenes/Gatos/12_donnatella', '982000100001245', 5.0, 'Mediano'),
('Freya', '2017-07-16', 'Hembra', 'Común Europeo', 'Atigrada', 'Corto', ARRAY['Valiente','Leal'], true, 'Valiente y siempre atenta. Con algo de carácter pero muy leal.', 'disponible', 'Imagenes/Gatos/13_freya', '982000100001246', 5.2, 'Mediano'),
('Sophie', '2019-10-10', 'Hembra', 'Angora', 'Blanca', 'Largo', ARRAY['Regia','Carismática'], true, 'Una reina que busca su trono y mucho mimo.', 'disponible', 'Imagenes/Gatos/14_sophie', '982000100001247', 4.8, 'Mediano'),
('Milo', '2024-01-05', 'Macho', 'Común Europeo', 'Atigrado', 'Corto', ARRAY['Curioso','Tierno'], false, 'Bebé rescatado hace una semana, muy curioso y cariñoso.', 'disponible', 'Imagenes/Gatos/15_milo', '982000100001248', 1.8, 'Pequeño');

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