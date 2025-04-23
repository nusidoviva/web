<?php
// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "resguardo");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Petición AJAX por ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT titulo, contenido FROM manual WHERE id = $id";
    $res = $conexion->query($sql);

    if ($res->num_rows > 0) {
        $fila = $res->fetch_assoc();
        echo "<h2>" . htmlspecialchars($fila['titulo']) . "</h2>";
        echo "<div style='white-space: pre-wrap;'>" . nl2br(htmlspecialchars($fila['contenido'])) . "</div>";
    } else {
        echo "Contenido no encontrado.";
    }
    exit;
}

// Petición AJAX por búsqueda
if (isset($_GET['buscar'])) {
    $busqueda = $conexion->real_escape_string($_GET['buscar']);
    $sql = "SELECT id, titulo FROM manual WHERE titulo LIKE '%$busqueda%' OR contenido LIKE '%$busqueda%'";
    $res = $conexion->query($sql);

    if ($res->num_rows > 0) {
        while ($fila = $res->fetch_assoc()) {
            echo "<a href='#' class='indice-link' data-id='{$fila['id']}'>{$fila['titulo']}</a><br>";
        }
    } else {
        echo "No se encontraron coincidencias.";
    }
    exit;
}

// Obtener índice principal
$result = $conexion->query("SELECT id, titulo FROM manual ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Manual del Usuario - Resguardo Nuzido</title>


<style>
/* Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;

}

/* General */
body {
    font-family: 'Roboto', sans-serif;
    background: linear-gradient(to right, #a86342, #a86342);
    padding: 40px 20px;
    color: #333;
    line-height: 1.6;
    background-color: #a86342;
}

/* Encabezado */
h1 {
    text-align: center;
    color:rgb(223, 137, 100);
    margin-bottom: 30px;
}

/* Buscador y Enlace Inicio */
.buscador-inicio {
    display: flex;
    justify-content: center;
    align-items: center; /* Alineación vertical */
    gap: 10px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

input[type="text"] {
    padding: 12px 16px;
    width: 60%;
    max-width: 400px;
    border: 2px solid #a86342;
    border-radius: 8px;
    font-size: 16px;
    outline: none;
    transition: 0.3s;
}

input[type="text"]:focus {
    border-color: #a86342;
}

/* Botón */
button {
    padding: 12px 24px;
    background-color:rgb(202, 88, 35);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s ease;
}

button:hover {
    background-color: #a86342;
}

/* Enlace Inicio */
.inicio-link {
    color: rgb(228, 152, 117);
    text-decoration: none;
    font-weight: bold;
    font-size: 16px;
}

.inicio-link:hover {
    text-decoration: underline;
}

/* Contenedor del índice */
.indice {
    background:rgba(218, 110, 60, 0.9);
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(218, 99, 99, 0.6);
    margin-bottom: 30px;
}

.indice h2 {
    color:rgb(0, 0, 0);
    border-bottom: 2px solid #e0f2f1;
    margin-bottom: 15px;
    padding-bottom: 5px;
}

/* Enlaces del índice */
.indice-link {
    display: block;
    padding: 10px;
    margin: 8px 0;
    background:rgb(233, 174, 146);
    border-radius: 6px;
    text-decoration: none;
    color:rgb(0, 0, 0);
    font-weight: bold;
    transition: 0.3s ease;
}

.indice-link:hover {
    background-color:rgba(224, 165, 165, 0.89);
}

/* Contenedor de contenido */
#contenido {
    background:rgb(207, 109, 63);
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

#contenido h2 {
    color:rgb(0, 0, 0);
    margin-bottom: 10px;
}

#contenido p {
    color:rgb(0, 0, 0);
}

/* Responsive */
@media (max-width: 600px) {
    input[type="text"] {
        width: 100%;
    }

    button {
        width: 100%;
    }
}

</style>

</head>

<body>

<h1>M&nbsp;A&nbsp;N&nbsp;U&nbsp;A&nbsp;L&nbsp;&nbsp;&nbsp;D&nbsp;E&nbsp;L&nbsp;&nbsp;&nbsp;U&nbsp;S&nbsp;U&nbsp;A&nbsp;R&nbsp;I&nbsp;O</h1>

<div class="buscador-inicio">
    <form id="formBuscar">
        <input type="text" id="buscarTexto" placeholder="Buscar en el manual...">
        <button type="submit">Buscar</button>
    </form>
    <a href="index.html" class="inicio-link">INICIO</a>
</div>

<div id="contenido">
    <p><em>Haz clic en un título del índice o realiza una búsqueda para ver el contenido.</em></p>
</div>

<br>

<div class="indice" id="indice">
    <h2>INDICE</h2>
    <?php while ($fila = $result->fetch_assoc()): ?>
        <a href="#" class="indice-link" data-id="<?= $fila['id'] ?>"><?= htmlspecialchars($fila['titulo']) ?></a>
    <?php endwhile; ?>
</div>

<script>
function cargarContenidoPorId(id) {
    fetch(window.location.pathname + `?id=${id}`)
        .then(res => res.text())
        .then(data => {
            document.getElementById('contenido').innerHTML = data;
        });
}

document.querySelectorAll('.indice-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const id = this.getAttribute('data-id');
        cargarContenidoPorId(id);
    });
});

document.getElementById('formBuscar').addEventListener('submit', function(e) {
    e.preventDefault();
    const texto = document.getElementById('buscarTexto').value;

    fetch(window.location.pathname + `?buscar=${encodeURIComponent(texto)}`)
        .then(res => res.text())
        .then(data => {
            document.getElementById('indice').innerHTML = `<h2>Resultados</h2>${data}`;
            document.getElementById('contenido').innerHTML = `<p><em>Selecciona un título para ver el contenido.</em></p>`;

            document.querySelectorAll('.indice-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.getAttribute('data-id');
                    cargarContenidoPorId(id);
                });
            });
        });
});
</script>

</body>
</html>

<?php $conexion->close(); ?>