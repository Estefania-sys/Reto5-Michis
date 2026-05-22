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
    <title class="traductor" data-es="Panel de Control - Adopciones" data-ca="Panell de Control - Adopcions"></title>
    <link rel="stylesheet" href="/Reto5-Michis/style.css">
</head>
<body>
    <?php include '../navbar/headeradmin.php'; ?>

    <div class="admin-panel">
        <h1 class="traductor" data-es="📋 Panel de Control - Solicitudes de Adopción" data-ca="📋 Panell de Control - Sol·licituds d'Adopció"></h1>

        <?php if(count($adopciones) > 0): ?>
            <section class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th class="traductor" data-es="Nombre Completo" data-ca="Nom Complet"></th>
                            <th class="traductor" data-es="Email" data-ca="Email"></th>
                            <th class="traductor" data-es="DNI" data-ca="DNI"></th>
                            <th class="traductor" data-es="Teléfono" data-ca="Telèfon"></th>
                            <th class="traductor" data-es="Ciudad / CP" data-ca="Ciutat / CP"></th>
                            <th class="traductor" data-es="Gato" data-ca="Gat"></th>
                            <th class="traductor" data-es="Microchip" data-ca="Microxip"></th>
                            <th class="traductor" data-es="Peso" data-ca="Pes"></th>
                            <th class="traductor" data-es="Tamaño" data-ca="Mida"></th>
                            <th class="traductor" data-es="Fecha de Adopción" data-ca="Data d'Adopció"></th>
                            <th class="traductor" data-es="Cita 1" data-ca="Cita 1"></th>
                            <th class="traductor" data-es="Cita 2" data-ca="Cita 2"></th>
                            <th class="traductor" data-es="Observaciones" data-ca="Observacions"></th>
                            <th class="traductor" data-es="Acciones" data-ca="Accions"></th>
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
                                <td>
                                    <?php 
                                        $tamano = $adopcion['tamano'] ?? '';
                                        if ($tamano === 'Pequeño') {
                                            echo '<span class="traductor" data-es="Pequeño" data-ca="Petit"></span>';
                                        } elseif ($tamano === 'Mediano') {
                                            echo '<span class="traductor" data-es="Mediano" data-ca="Mitjà"></span>';
                                        } elseif ($tamano === 'Grande') {
                                            echo '<span class="traductor" data-es="Grande" data-ca="Gran"></span>';
                                        } else {
                                            echo htmlspecialchars($tamano);
                                        }
                                    ?>
                                </td>
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
                                        <a href="/Reto5-Michis/detalle-gato.php?id=<?php echo $adopcion['id_gato']; ?>" class="btn-small btn-view traductor" data-es="Ver Gato" data-ca="Veure Gat"></a>
                                    </section>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        <?php else: ?>
            <div class="empty-message">
                <p class="traductor" data-es="No hay solicitudes de adopción registradas aún." data-ca="No hi ha sol·licituds d'adopció registrades encara."></p>
            </div>
        <?php endif; ?>
    </div>

    <?php include '../navbar/footer.php' ?>
</body>
</html>