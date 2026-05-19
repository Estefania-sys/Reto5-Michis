<?php
require_once '../Clases/Admin.php';
Admin::iniciar();
Admin::requerirAdmin();

require_once '../Clases/Conexion.php';
require_once '../Clases/Gato.php';

$pdo = (new Conexion())->getConnection();

$id_gato = isset($_GET['id']) ? intval($_GET['id']) : 0;
$gato = Gato::obtenerPorId($pdo, $id_gato);

if (!$gato) {
    header('Location: admin-index.php');
    exit;
}

$mensaje = "";
$clase_mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? $gato['nombre'];
    $raza = $_POST['raza'] ?? $gato['raza'];
    $genero = $_POST['genero'] ?? $gato['genero'];
    $capa_patron = $_POST['capa_patron'] ?? $gato['capa_patron'];
    $pelo_largo = $_POST['pelo_largo'] ?? $gato['pelo_largo'];
    $esterilizado = isset($_POST['esterilizado']) ? 1 : 0;
    $estado = $_POST['estado'] ?? $gato['estado'];
    $notas_cuidador = $_POST['notas_cuidador'] ?? $gato['notas_cuidador'];
    $numero_microchip = $_POST['numero_microchip'] ?? $gato['numero_microchip'];
    $peso_kg = $_POST['peso_kg'] ?? $gato['peso_kg'];
    $tamano = $_POST['tamano'] ?? $gato['tamano'];

    $sql = "UPDATE Gatos SET 
            nombre = :nombre,
            raza = :raza,
            genero = :genero,
            capa_patron = :capa_patron,
            pelo_largo = :pelo_largo,
            esterilizado = :esterilizado,
            estado = :estado,
            notas_cuidador = :notas_cuidador,
            numero_microchip = :numero_microchip,
            peso_kg = :peso_kg,
            tamano = :tamano
            WHERE id_gato = :id";

    $stmt = $pdo->prepare($sql);
    $resultado = $stmt->execute([
        ':nombre' => $nombre,
        ':raza' => $raza,
        ':genero' => $genero,
        ':capa_patron' => $capa_patron,
        ':pelo_largo' => $pelo_largo,
        ':esterilizado' => $esterilizado,
        ':estado' => $estado,
        ':notas_cuidador' => $notas_cuidador,
        ':numero_microchip' => $numero_microchip,
        ':peso_kg' => $peso_kg,
        ':tamano' => $tamano,
        ':id' => $id_gato
    ]);

    if ($resultado) {
        $mensaje = "¡Información del gato actualizada exitosamente!";
        $clase_mensaje = "mensaje-exito";
        $gato = Gato::obtenerPorId($pdo, $id_gato);
    } else {
        $mensaje = "Hubo un error al actualizar la información.";
        $clase_mensaje = "mensaje-error";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Gato - <?php echo htmlspecialchars($gato['nombre']); ?></title>
    <link rel="stylesheet" href="/Reto5-Michis/style.css">
</head>
<body>
    <?php Admin::renderizarHeader(); ?>

    <div class="admin-panel">
        <h1>📝 Editar Información de <?php echo htmlspecialchars($gato['nombre']); ?></h1>

        <?php if ($mensaje): ?>
            <p class="<?php echo $clase_mensaje; ?>"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif; ?>

        <form method="POST" class="edit-form">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($gato['nombre']); ?>" required>
            </div>

            <div class="form-group">
                <label for="raza">Raza:</label>
                <input type="text" id="raza" name="raza" value="<?php echo htmlspecialchars($gato['raza'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="genero">Género:</label>
                <select id="genero" name="genero">
                    <option value="">Selecciona...</option>
                    <option value="Macho" <?php echo $gato['genero'] === 'Macho' ? 'selected' : ''; ?>>Macho</option>
                    <option value="Hembra" <?php echo $gato['genero'] === 'Hembra' ? 'selected' : ''; ?>>Hembra</option>
                </select>
            </div>

            <div class="form-group">
                <label for="capa_patron">Patrón de color:</label>
                <input type="text" id="capa_patron" name="capa_patron" value="<?php echo htmlspecialchars($gato['capa_patron'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="pelo_largo">Tipo de pelo:</label>
                <select id="pelo_largo" name="pelo_largo">
                    <option value="">Selecciona...</option>
                    <option value="Corto" <?php echo $gato['pelo_largo'] === 'Corto' ? 'selected' : ''; ?>>Corto</option>
                    <option value="Largo" <?php echo $gato['pelo_largo'] === 'Largo' ? 'selected' : ''; ?>>Largo</option>
                </select>
            </div>

            <div class="form-group">
                <label for="esterilizado">
                    <input type="checkbox" id="esterilizado" name="esterilizado" <?php echo $gato['esterilizado'] ? 'checked' : ''; ?>>
                    Esterilizado
                </label>
            </div>

            <div class="form-group">
                <label for="estado">Estado:</label>
                <select id="estado" name="estado">
                    <option value="disponible" <?php echo $gato['estado'] === 'disponible' ? 'selected' : ''; ?>>Disponible</option>
                    <option value="en tratamiento" <?php echo $gato['estado'] === 'en tratamiento' ? 'selected' : ''; ?>>En tratamiento</option>
                    <option value="adoptado" <?php echo $gato['estado'] === 'adoptado' ? 'selected' : ''; ?>>Adoptado</option>
                </select>
            </div>

            <div class="form-group">
                <label for="numero_microchip">Número de microchip:</label>
                <input type="text" id="numero_microchip" name="numero_microchip" value="<?php echo htmlspecialchars($gato['numero_microchip'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="peso_kg">Peso (kg):</label>
                <input type="number" id="peso_kg" name="peso_kg" step="0.1" value="<?php echo htmlspecialchars($gato['peso_kg'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="tamano">Tamaño:</label>
                <select id="tamano" name="tamano">
                    <option value="">Selecciona...</option>
                    <option value="Pequeño" <?php echo $gato['tamano'] === 'Pequeño' ? 'selected' : ''; ?>>Pequeño</option>
                    <option value="Mediano" <?php echo $gato['tamano'] === 'Mediano' ? 'selected' : ''; ?>>Mediano</option>
                    <option value="Grande" <?php echo $gato['tamano'] === 'Grande' ? 'selected' : ''; ?>>Grande</option>
                </select>
            </div>

            <div class="form-group">
                <label for="notas_cuidador">Notas del cuidador:</label>
                <textarea id="notas_cuidador" name="notas_cuidador"><?php echo htmlspecialchars($gato['notas_cuidador'] ?? ''); ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Guardar cambios</button>
                <a href="../catalogo.php" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <?php include '../navbar/footer.php' ?>
</body>
</html>
