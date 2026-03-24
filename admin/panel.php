<?php
include '../templates/header_admin.php'; 
?>

<main id="main-paneladmin">

<?php
echo generarSelect($conexion,'categorias', 'nombre', 'categoriasAdmin');
?>
<br>
<a href="proyecto_nuevo.php" style="text-decoration:none; color:black"><buttom class="btn-save">Nuevo proyecto</buttom></a>
<br><br>
<?php 


if(!isset($_GET["categoriasAdmin"]) || $_GET["categoriasAdmin"] == "todas"){
        $proyectos = proyectos($conexion);
    }else
        $proyectos = proyectos($conexion, idCategoria($conexion, $_GET["categoriasAdmin"]));


    $respuesta ="";
    foreach($proyectos as $p){
            $respuesta .= '<tr>
            <td>' . $p["id"] . '</td>
            <td>' . $p["titulo"] . '</td>
            <td>' . nombreCategoria($conexion,$p["categoria_id"]) . '</td>
            <td>' . $p["descripcion"] . '</td>
            <td>' . tecnologiasProyecto($conexion,$p["id"]) . '</td>
            <td class=table-actions><a href=proyecto_editar.php?id='.$p["id"].'><button style=color:white class=btn-edit>Editar</button></a><a href=proyecto_borrar.php?id='.$p["id"].'><button style=color:white class=btn-delete>Borrar</button></a></td>
            </tr>';
        }
        ?>

<table class=table-modern>
<tr>
    <th>ID</th>
    <th>Título</th>
    <th>Categoría</th>
    <th>Descripción</th>
    <th>Tecnologías</th>
    <th>Acciones</th>
</tr>    
<?= $respuesta ?></table>

</main>
<?php include '../templates/footer.php'; ?>