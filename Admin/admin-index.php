<?php
require_once '../Clases/Admin.php';
Admin::iniciar();
Admin::requerirAdmin();

require_once '../Clases/Conexion.php';

$pdo = (new Conexion())->getConnection();

// Obtener todas las adopciones con información de usuario y gato
$query = "
    SELECT
        u.id_usuario,
        u.nombres,
        u.apellidos,
        u.email,
        u.dni,
        u.fecha_nacimiento AS usuario_fecha_nacimiento,
        u.direccion,
        u.poblacion,
        u.cp,
        u.telefono,
        g.id_gato,
        g.nombre AS gato_nombre,
        g.numero_microchip,
        g.peso_kg,
        g.tamano,
        a.id_adopcion,
        a.fecha_adopcion,
        a.observaciones,
        a.cita1_ok,
        a.cita2_ok
    FROM Adopciones a
    JOIN Usuarios u ON a.id_usuario = u.id_usuario
    JOIN Gatos g ON a.id_gato = g.id_gato
    ORDER BY a.fecha_adopcion DESC
";

$stmt = $pdo->prepare($query);
$stmt->execute();
$adopciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Adopciones</title>
    <link rel="stylesheet" href="/Reto5-Michis/style.css">
</head>
<body>
    <?php Admin::renderizarHeader(); ?>

    <div class="admin-panel">
        <h1>📋 Panel de Control - Solicitudes de Adopción</h1>

        <?php if(count($adopciones) > 0): ?>
            <section class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Nombre Completo</th>
                            <th>Email</th>
                            <th>DNI</th>
                            <th>Teléfono</th>
                            <th>Ciudad / CP</th>
                            <th>Gato</th>
                            <th>Microchip</th>
                            <th>Peso</th>
                            <th>Tamaño</th>
                            <th>Fecha de Adopción</th>
                            <th>Cita 1</th>
                            <th>Cita 2</th>
                            <th>Observaciones</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($adopciones as $adopcion): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($adopcion['nombres'] . ' ' . $adopcion['apellidos']); ?></strong>
                                </td>
                                <td>
                                    <a href="mailto:<?php echo htmlspecialchars($adopcion['email']); ?>">
                                        <?php echo htmlspecialchars($adopcion['email']); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($adopcion['dni'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($adopcion['telefono'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars(trim(($adopcion['poblacion'] ?? '') . ' / ' . ($adopcion['cp'] ?? ''))); ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($adopcion['gato_nombre']); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars($adopcion['numero_microchip'] ?? ''); ?></td>
                                <td><?php echo !empty($adopcion['peso_kg']) ? htmlspecialchars($adopcion['peso_kg'] . ' kg') : ''; ?></td>
                                <td><?php echo htmlspecialchars($adopcion['tamano'] ?? ''); ?></td>
                                <td>
                                    <?php echo date('d/m/Y', strtotime($adopcion['fecha_adopcion'])); ?>
                                </td>
                                <td>
                                    <section class="cita-status">
                                        <?php if($adopcion['cita1_ok']): ?>
                                            <span class="cita-ok">✓</span>
                                        <?php else: ?>
                                            <span class="cita-no">✗</span>
                                        <?php endif; ?>
                                    </section>
                                </td>
                                <td>
                                    <section class="cita-status">
                                        <?php if($adopcion['cita2_ok']): ?>
                                            <span class="cita-ok">✓</span>
                                        <?php else: ?>
                                            <span class="cita-no">✗</span>
                                        <?php endif; ?>
                                    </section>
                                </td>
                                <td>
                                    <small><?php echo htmlspecialchars(substr($adopcion['observaciones'] ?? '', 0, 50) . (strlen($adopcion['observaciones'] ?? '') > 50 ? '...' : '')); ?></small>
                                </td>
                                <td>
                                    <section class="action-buttons">
                                        <a href="/Reto5-Michis/detalle-gato.php?id=<?php echo $adopcion['id_gato']; ?>" class="btn-small btn-view">Ver Gato</a>
                                    </section>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        <?php else: ?>
            <div class="empty-message">
                <p>No hay solicitudes de adopción registradas aún.</p>
            </div>
        <?php endif; ?>
    </div>

    <?php include '../navbar/footer.php' ?>
</body>
</html>