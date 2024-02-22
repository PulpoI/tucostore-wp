<?php

/*
*
Funcion para cambiar precios de productos por categoria
Inicio
*
*/
// Incluir el archivo con la función de actualización de precios
require_once get_stylesheet_directory() . '/inc/update-prices/actualizar_precios.php';

// Agregar el formulario al panel de administración para cada categoría padre
add_action('admin_menu', 'agregar_pagina_actualizar_precios_por_categoria');

function agregar_pagina_actualizar_precios_por_categoria()
{
    // Obtener todas las categorías principales (categorías padre)
    $categories = get_terms(
        array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'parent' => 0, // Obtener solo las categorías principales
        )
    );

    // Agregar un formulario para cada categoría padre
    foreach ($categories as $category) {
        add_submenu_page(
            'edit.php?post_type=product',
            'Actualizar Precios - ' . $category->name,
            'Actualizar Precios - ' . $category->name,
            'manage_options',
            'actualizar_precios_page_' . $category->term_id,
            function () use ($category) {
                mostrar_formulario_actualizar_precios_por_categoria($category);
            }
        );
    }
}

function mostrar_formulario_actualizar_precios_por_categoria($category)
{
    ?>
    <div class="wrap">
        <h1>Actualizar Precios -
            <?php echo $category->name; ?>
        </h1>
        <form method="post">
            <input type="hidden" name="categoria_id" value="<?php echo $category->term_id; ?>">
            <label for="nuevo_precio">Nuevo Precio:</label>
            <input type="text" name="nuevo_precio" id="nuevo_precio" required>
            <label for="atributo_modificar">Atributo a Modificar:</label>
            <input type="text" name="atributo_modificar" id="atributo_modificar" required>
            <input type="submit" name="actualizar_precios_submit" class="button button-primary" value="Actualizar Precios">
        </form>
    </div>
    <?php
}

// Procesar el formulario para cada categoría padre
add_action('admin_init', 'procesar_formulario_actualizar_precios_por_categoria');

function procesar_formulario_actualizar_precios_por_categoria()
{
    if (isset($_POST['actualizar_precios_submit']) && current_user_can('manage_options')) {
        // Verificar la seguridad del formulario

        // Obtener los valores del formulario
        $nuevo_precio = isset($_POST['nuevo_precio']) ? sanitize_text_field($_POST['nuevo_precio']) : '';
        $atributo_modificar = isset($_POST['atributo_modificar']) ? sanitize_text_field($_POST['atributo_modificar']) : '';
        $categoria_id = isset($_POST['categoria_id']) ? intval($_POST['categoria_id']) : 0;

        // Llamar a la función para actualizar los precios con los valores proporcionados
        if (!empty($nuevo_precio) && !empty($atributo_modificar) && $categoria_id !== 0) {
            actualizar_precios_productos_por_categoria($nuevo_precio, $atributo_modificar, $categoria_id);

            // Mensaje de éxito o redirección
            echo '<div class="updated"><p>Los precios se han actualizado correctamente.</p></div>';
        } else {
            // Mensaje de error
            echo '<div class="error"><p>Por favor, complete todos los campos y seleccione una categoría.</p></div>';
        }
    }
}
/*
*
Funcion para cambiar precios de productos por categoria
Fin
*
*/