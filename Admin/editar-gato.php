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
    $datos_actualizados = [
        'nombre'           => $_POST['nombre'] ?? $gato['nombre'],
        'raza'             => $_POST['raza'] ?? $gato['raza'],
        'genero'           => $_POST['genero'] ?? $gato['genero'],
        'capa_patron'      => $_POST['capa_patron'] ?? $gato['capa_patron'],
        'pelo_largo'       => $_POST['pelo_largo'] ?? $gato['pelo_largo'],
        'esterilizado'     => isset($_POST['esterilizado']) ? 1 : 0,
        'estado'           => $_POST['estado'] ?? $gato['estado'],
        'notas_cuidador'   => $_POST['notas_cuidador'] ?? $gato['notas_cuidador'],
        'numero_microchip' => $_POST['numero_microchip'] ?? $gato['numero_microchip'],
        'peso_kg'          => $_POST['peso_kg'] ?? $gato['peso_kg'],
        'tamano'           => $_POST['tamano'] ?? $gato['tamano']
    ];

    // Llamamos al nuevo método de la clase Gato
    $resultado = Gato::actualizar($pdo, $id_gato, $datos_actualizados);

    if ($resultado) {
        $mensaje = "exito";
        $clase_mensaje = "mensaje-exito";
        $gato = Gato::obtenerPorId($pdo, $id_gato);
    } else {
        $mensaje = "error";
        $clase_mensaje = "mensaje-error";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title class="traductor" data-es="Editar Gato - <?php echo htmlspecialchars($gato['nombre']); ?>" data-ca="Editar Gat - <?php echo htmlspecialchars($gato['nombre']); ?>"></title>
    <link rel="stylesheet" href="/Reto5-Michis/style.css">
</head>
<body>
    <?php include '../navbar/headeradmin.php'; ?>

    <div class="admin-panel">
        <h1>
            <span class="traductor" data-es="📝 Editar Información de " data-ca="📝 Editar Informació de "></span><?php echo htmlspecialchars($gato['nombre']); ?>
        </h1>

        <?php if ($mensaje === "exito"): ?>
            <p class="<?php echo $clase_mensaje; ?> traductor" data-es="¡Información del gato actualizada exitosamente!" data-ca="¡Informació del gat actualitzada correctament!"></p>
        <?php elseif ($mensaje === "error"): ?>
            <p class="<?php echo $clase_mensaje; ?> traductor" data-es="Hubo un error al actualizar la información." data-ca="Hi va haver un error al actualizar la informació."></p>
        <?php endif; ?>

        <form method="POST" class="edit-form">
            <div class="form-group">
                <label for="nombre" class="traductor" data-es="Nombre:" data-ca="Nom:"></label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($gato['nombre']); ?>" required>
            </div>

            <div class="form-group">
                <label for="raza" class="traductor" data-es="Raza:" data-ca="Raça:"></label>
                <input type="text" id="raza" name="raza" value="<?php echo htmlspecialchars($gato['raza'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="genero" class="traductor" data-es="Género:" data-ca="Gènere:"></label>
                <select id="genero" name="genero">
                    <option value="" class="traductor" data-es="Selecciona..." data-ca="Selecciona..."></option>
                    <option value="Macho" class="traductor" data-es="Macho" data-ca="Mascle" <?php echo $gato['genero'] === 'Macho' ? 'selected' : ''; ?>></option>
                    <option value="Hembra" class="traductor" data-es="Hembra" data-ca="Femella" <?php echo $gato['genero'] === 'Hembra' ? 'selected' : ''; ?>></option>
                </select>
            </div>

            <div class="form-group">
                <label for="capa_patron" class="traductor" data-es="Patrón de color:" data-ca="Patró de color:"></label>
                <input type="text" id="capa_patron" name="capa_patron" value="<?php echo htmlspecialchars($gato['capa_patron'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="pelo_largo" class="traductor" data-es="Tipo de pelo:" data-ca="Tipus de pèl:"></label>
                <select id="pelo_largo" name="pelo_largo">
                    <option value="" class="traductor" data-es="Selecciona..." data-ca="Selecciona..."></option>
                    <option value="Corto" class="traductor" data-es="Corto" data-ca="Curt" <?php echo $gato['pelo_largo'] === 'Corto' ? 'selected' : ''; ?>></option>
                    <option value="Largo" class="traductor" data-es="Largo" data-ca="Llarg" <?php echo $gato['pelo_largo'] === 'Largo' ? 'selected' : ''; ?>></option>
                </select>
            </div>

            <div class="form-group">
                <label for="esterilizado" class="traductor" data-es="Esterilizado" data-ca="Esterilitzat">
                    <input type="checkbox" id="esterilizado" name="esterilizado" <?php echo $gato['esterilizado'] ? 'checked' : ''; ?>>
                    <span class="traductor" data-es="Esterilizado" data-ca="Esterilitzat"></span>
                </label>
            </div>

            <div class="form-group">
                <label for="estado" class="traductor" data-es="Estado:" data-ca="Estat:"></label>
                <select id="estado" name="estado">
                    <option value="disponible" class="traductor" data-es="Disponible" data-ca="Disponible" <?php echo $gato['estado'] === 'disponible' ? 'selected' : ''; ?>></option>
                    <option value="en tratamiento" class="traductor" data-es="En tratamiento" data-ca="En tractament" <?php echo $gato['estado'] === 'en tratamiento' ? 'selected' : ''; ?>></option>
                    <option value="adoptado" class="traductor" data-es="Adoptado" data-ca="Adoptat" <?php echo $gato['estado'] === 'adoptado' ? 'selected' : ''; ?>></option>
                </select>
            </div>

            <div class="form-group">
                <label for="numero_microchip" class="traductor" data-es="Número de microchip:" data-ca="Número de microxip:"></label>
                <input type="text" id="numero_microchip" name="numero_microchip" value="<?php echo htmlspecialchars($gato['numero_microchip'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="peso_kg" class="traductor" data-es="Peso (kg):" data-ca="Pes (kg):"></label>
                <input type="number" id="peso_kg" name="peso_kg" step="0.1" value="<?php echo htmlspecialchars($gato['peso_kg'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="tamano" class="traductor" data-es="Tamaño:" data-ca="Mida:"></label>
                <select id="tamano" name="tamano">
                    <option value="" class="traductor" data-es="Selecciona..." data-ca="Selecciona..."></option>
                    <option value="Pequeño" class="traductor" data-es="Pequeño" data-ca="Petit" <?php echo $gato['tamano'] === 'Pequeño' ? 'selected' : ''; ?>></option>
                    <option value="Mediano" class="traductor" data-es="Mediano" data-ca="Mitjà" <?php echo $gato['tamano'] === 'Mediano' ? 'selected' : ''; ?>></option>
                    <option value="Grande" class="traductor" data-es="Grande" data-ca="Gran" <?php echo $gato['tamano'] === 'Grande' ? 'selected' : ''; ?>></option>
                </select>
            </div>

            <div class="form-group">
                <label for="notas_cuidador" class="traductor" data-es="Notas del cuidador:" data-ca="Notes del cuidador:"></label>
                <textarea id="notas_cuidador" name="notas_cuidador"><?php echo htmlspecialchars($gato['notas_cuidador'] ?? ''); ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary traductor" data-es="Guardar cambios" data-ca="Desar canvis"></button>
                <a href="../catalogo.php" class="btn-secondary traductor" data-es="Cancelar" data-ca="Cancel·lar"></a>
            </div>
        </form>
    </div>

    <?php include '../navbar/footer.php' ?>
</body>
</html>