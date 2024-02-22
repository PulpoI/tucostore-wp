<?php


// add_action('after_setup_theme', 'actualizar_precios_productos_variables');
function actualizar_precios_productos_por_categoria($nuevo_precio, $atributo_modificar, $categoria_id)
{
    // Verificar que los parámetros no estén vacíos
    if (empty($nuevo_precio) || empty($atributo_modificar) || empty($categoria_id)) {
        return; // Salir de la función si los parámetros están vacíos
    }

    // Obtener todos los productos de la categoría especificada
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
                'relation' => 'AND',
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
            $product_title = get_the_title();

            echo 'Producto: ' . $product_title . '<br>';

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

                    // Verificar si la variación tiene el atributo deseado
                    $variation_attributes = $variation_data->get_variation_attributes();
                    if (in_array($atributo_modificar, $variation_attributes)) {
                        // Actualizar el precio solo para la variación con el atributo deseado
                        $variation_data->set_regular_price($nuevo_precio);
                        $variation_data->set_price($nuevo_precio);
                        $variation_data->save();
                    }
                }
            }
        }

        // Restaurar datos globales de WordPress
        wp_reset_postdata();

        // Limpiar la caché de WooCommerce para reflejar los cambios en el frontend
        wp_cache_flush();
    }
}
