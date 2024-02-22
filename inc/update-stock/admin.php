<?php


include_once get_stylesheet_directory() . '/inc/update-stock/actualizar_stock.php';


// Agregar el formulario al panel de administración para cambiar el estado de "Activado" por categoría
add_action('admin_menu', 'agregar_pagina_cambiar_estado_activado_por_categoria');

function agregar_pagina_cambiar_estado_activado_por_categoria()
{
    // Obtener todas las categorías principales (categorías padre)
    $categories = get_terms(
        array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'parent' => 0, // Obtener solo las categorías principales
        )
    );

    // Agregar un formulario para cambiar el estado de "Activado" para cada categoría padre
    foreach ($categories as $category) {
        add_submenu_page(
            'edit.php?post_type=product',
            'Cambiar Estado Activado - ' . $category->name,
            'Cambiar Estado Activado - ' . $category->name,
            'manage_options',
            'cambiar_estado_activado_page_' . $category->term_id,
            function () use ($category) {
                mostrar_formulario_cambiar_estado_activado_por_categoria($category);
            }
        );
    }
}

function mostrar_formulario_cambiar_estado_activado_por_categoria($category)
{
    ?>
    <div class="wrap">
        <h1>Cambiar Estado Activado -
            <?php echo $category->name; ?>
        </h1>
        <form method="post">
            <input type="hidden" name="categoria_id" value="<?php echo $category->term_id; ?>">
            <label for="activar_variaciones">Activar Variaciones:</label>
            <input type="checkbox" name="activar_variaciones" id="activar_variaciones" value="1">
            <label for="atributo_modificar">Atributo a Modificar:</label>
            <input type="text" name="atributo_modificar" id="atributo_modificar" required>
            <input type="submit" name="cambiar_estado_activado_submit" class="button button-primary"
                value="Cambiar Estado Activado">
        </form>
    </div>
    <?php
}

// Procesar el formulario para cambiar el estado de "Activado" por categoría
add_action('admin_init', 'procesar_formulario_cambiar_estado_activado_por_categoria');

function procesar_formulario_cambiar_estado_activado_por_categoria()
{
    if (isset($_POST['cambiar_estado_activado_submit']) && current_user_can('manage_options')) {
        // Verificar la seguridad del formulario

        // Obtener los valores del formulario
        $activar_variaciones = isset($_POST['activar_variaciones']) ? true : false;
        $atributo_modificar = isset($_POST['atributo_modificar']) ? sanitize_text_field($_POST['atributo_modificar']) : '';
        $categoria_id = isset($_POST['categoria_id']) ? intval($_POST['categoria_id']) : 0;

        // Llamar a la función para cambiar el estado de "Activado" con los valores proporcionados
        if (!empty($atributo_modificar) && $categoria_id !== 0) {
            cambiar_estado_activado_variaciones_por_categoria($activar_variaciones, $atributo_modificar, $categoria_id);

            // Mensaje de éxito o redirección
            echo '<div class="updated"><p>El estado de "Activado" de las variaciones se ha cambiado correctamente.</p></div>';
        } else {
            // Mensaje de error
            echo '<div class="error"><p>Por favor, complete todos los campos y seleccione una categoría.</p></div>';
        }
    }
}
