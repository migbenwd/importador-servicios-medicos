<?php

/*
Plugin Name: Importador de JSON
Description: Importa publicaciones desde un archivo JSON.
Version: 1.0
Author: Tu Nombre
*/

// Función para leer el archivo JSON y crear las publicaciones
function importar_publicaciones_json() {
	
	$ruta_plugin = dirname(__FILE__); // Obtiene la ruta de la carpeta del plugin
    	$archivo_json = $ruta_plugin . '\response-deliabarraza.json'; // Reemplaza con la ruta completa de tu archivo
	$datos_json = file_get_contents($archivo_json);
    	$data_respuesta = json_decode($datos_json, true);
    	$publicaciones = $data_respuesta['data']['studies'];
	
	$total_estudios = count($publicaciones);

	// print_r($publicaciones);

	echo "archivo_json: " . $archivo_json;
	echo "<br>";
	echo "total estudios: " . $total_estudios;
	echo "<br>";
	
    if (is_array($publicaciones)) {
        $contador = 0;
        foreach ($publicaciones as $publicacion) {
            if ($contador >= 5) {
                break;
            }
			
			
            // Crear la publicación
            $nueva_publicacion = array(
                'post_title'   => $publicacion['studie'],
                'post_content' => $publicacion['studie'],
                'post_status'   => 'publish',
                'post_type'     => 'post',
            );

            // Asignar categoría (si existe en tu instalación de WordPress)
            if (isset($publicacion['category'])) {
                $categoria = get_term_by('name', $publicacion['category'], 'category');
                if ($categoria) {
                    $nueva_publicacion['post_category'] = array($categoria->term_id);
                }
            }

            wp_insert_post($nueva_publicacion);
			
			
			
			echo "ESTUDIO: " . $publicacion['studie'] . "<br>" ; 
			
            $contador++;
        }
    }
}

// Agregar un menú en el administrador para ejecutar la importación
function menu_importacion() {
    add_menu_page(
        'Importar Publicaciones JSON',
        'Importar JSON',
        'manage_options',
        'importar-json',
        'pagina_importacion'
    );
}
add_action('admin_menu', 'menu_importacion');

// Página de configuración para ejecutar la importación
function pagina_importacion() {
    if (isset($_POST['importar'])) {
        importar_publicaciones_json();
        echo '<div class="updated"><p>Publicaciones importadas correctamente.</p></div>';
    }
	
		// $ruta_plugin_2 = dirname(__FILE__); // Obtiene la ruta de la carpeta del plugin
		// echo $ruta_plugin_2;

    ?>
    <div class="wrap">
        <h1>Importar Publicaciones JSON</h1>
        <form method="post">
            <input type="submit" name="importar" value="Importar" class="button button-primary">
        </form>
    </div>
    <?php
}

?>
