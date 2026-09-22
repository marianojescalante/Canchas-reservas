<?php
$archivo = 'reservas.json';
$reservas = [];
if (file_exists($archivo)) {
    $contenido = file_get_contents($archivo);
    $datos = json_decode($contenido, true);
    if (is_array($datos)) {
        $reservas = $datos;
    }
}
$mensaje = "";
if ($_POST) {
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $telefono = htmlspecialchars(trim($_POST['telefono']));
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $cancha = $_POST['cancha'];
    $duracion = $_POST['duracion'];
    $choca = false;
    foreach ($reservas as $r) {
        if ($r['cancha'] == $cancha && $r['fecha'] == $fecha && $r['hora'] == $hora) { $choca = true; break; }
    }
    if ($choca) {
        $mensaje = "Esa cancha ya está reservada a esa hora";
    } else {
        $reservas[] = ['nombre'=>$nombre,'telefono'=>$telefono,'fecha'=>$fecha,'hora'=>$hora,'cancha'=>$cancha,'duracion'=>$duracion,'estado'=>'PENDIENTE'];
        file_put_contents($archivo, json_encode($reservas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
        $mensaje = "¡Cancha reservada correctamente!";
    }
}
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Reserva Cancha</title><meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body{font-family:system-ui;background:#0a5a20;padding:20px;color:#222}
.card{background:white;padding:20px;border-radius:15px;max-width:520px;margin:auto;box-shadow:0 4px 15px rgba(0,0,0,0.3)}
input,select{width:100%;padding:12px;margin:6px 0 12px 0;border-radius:8px;border:1px solid #ccc;box-sizing:border-box}
button{background:#0a5a20;color:white;padding:14px;width:100%;border:none;border-radius:8px;font-size:18px;font-weight:bold;cursor:pointer}
.reserva{background:#f0f0f0;padding:10px;border-radius:8px;margin-top:8px}
</style></head><body><div class="card">
<h2>Reserva Tu Cancha</h2>
<?php if($mensaje) echo "<p style='background:#d4edda;padding:10px;text-align:center;border-radius:8px'><b>$mensaje</b></p>"; ?>
<form method="POST">
Nombre<input type="text" name="nombre" required>
WhatsApp<input type="text" name="telefono" required>
Cancha<select name="cancha"><option>Cancha 1 - Fútbol 5</option><option>Cancha 2 - Fútbol 5</option><option>Cancha 3 - Fútbol 7</option><option>Cancha 4 - Fútbol 11</option></select>
Fecha<input type="date" name="fecha" required min="<?= date('Y-m-d') ?>">
Hora<input type="time" name="hora" required>
Duración<select name="duracion"><option>1 hora - $8000</option><option>2 horas - $15000</option><option>3 horas - $21000</option></select>
<button type="submit">¡RESERVAR!</button>
</form>
<h3>Reservas:</h3>
<?php foreach(array_reverse($reservas) as $r){ echo "<div class='reserva'><b>{$r['cancha']}</b> - {$r['fecha']} {$r['hora']}<br>{$r['nombre']} - {$r['telefono']}<br>{$r['duracion']}</div>"; } ?>
</div></body></html>