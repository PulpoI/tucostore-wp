<?php

// Función para cambiar el estado de "Activado" en las variaciones de productos por categoría
// Función para cambiar el estado de "Activado" en las variaciones de productos por categoría
function cambiar_estado_activado_variaciones_por_categoria($activar_variaciones, $atributo_modificar, $categoria_id)
{
    // Obtener todas las variaciones de productos en la categoría especificada
    $products = new WP_Query(
        array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $categoria_id,
                ),
            ),
            'meta_query' => array(
                array(
                    'key' => '_product_attributes',
                    'compare' => 'EXISTS',
                ),
                array(
                    'key' => '_children',
                    'compare' => 'NOT EXISTS',
                ),
            ),
        )
    );

    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();
            $product_id = get_the_ID();

            // Obtener las variaciones del producto
            $variations = get_posts(
                array(
                    'post_type' => 'product_variation',
                    'post_status' => 'publish',
                    'numberposts' => -1,
                    'post_parent' => $product_id,
                )
            );

            if ($variations) {
                foreach ($variations as $variation) {
                    $variation_data = new WC_Product_Variation($variation->ID);

                    // Obtener los atributos de la variación
                    $variation_attributes = $variation_data->get_variation_attributes();

                    // Verificar si la variación tiene los atributos especificados
                    if (in_array($atributo_modificar, $variation_attributes)) {
                        // Cambiar el estado de "Activado" de la variación según la opción elegida por el usuario
                        $variation_data->set_manage_stock($activar_variaciones);
                        if ($activar_variaciones) {
                            // Si se activa el checkbox, establecer stock gestionado a verdadero y stock a 1
                            $variation_data->set_manage_stock(true);
                            $variation_data->set_stock_quantity(10);
                        } else {
                            // Si se desactiva el checkbox, establecer stock gestionado a falso y stock a 0
                            $variation_data->set_manage_stock(false);
                            $variation_data->set_stock_quantity(0);
                        }
                        $variation_data->save();
                    }
                }
            }
        }

        // Restaurar datos globales de WordPress
        wp_reset_postdata();
    }
}
