<?php
/**
 * @package Plugin1
 */
/*
Plugin Name: plugin
Plugin URI: http://wordpress.org/plugins/plugin1/
Description: Crie um plugin que adicione um shortcode ou widget customizado ao site. Esse shortcode/widget deverá mostrar um botão na página que, quando clicado, adicionará um registro de data e hora no banco de dados WordPress. A tabela utilizada para guardar esse registro fica à sua escolha.
Author: Isabella Alencar
*/

// Função para carregar scripts no frontend
function load_custom_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('custom-script', plugins_url('/js/custom-script.js', __FILE__), array('jquery'), null, true);
    wp_localize_script('custom-script', 'custom_ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('custom_button_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'load_custom_scripts');

// Função para criar a tabela no ativação do plugin
function create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_button_logs';

    // Verifica se a tabela já existe
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            date_time datetime NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        // Executa o SQL
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    // Agora, verificamos novamente se a tabela foi criada
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
        update_option('custom_button_table_created', true);
    }
}
register_activation_hook(__FILE__, 'create_table');

// Função para lidar com a solicitação AJAX
function button_add_to_database() {
    check_ajax_referer('custom_button_nonce', 'nonce');

    // Verifica se a tabela foi criada
    if (get_option('custom_button_table_created')) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_button_logs';
        $date_time = current_time('mysql');

        $wpdb->insert(
            $table_name,
            array(
                'date_time' => $date_time
            )
        );

        echo 'Registro adicionado com sucesso!';
    } else {
        echo 'Erro: A tabela não foi criada.';
    }

    wp_die();
}
add_action('wp_ajax_custom_button_add_to_database', 'button_add_to_database');

// Função para criar o shortcode
function shortcode_button() {
    ob_start();
    ?>
    <!-- Criação do botão -->
    <a id="custom-button" style="color: green;" href="#">Clique Aqui</a>
    <?php
    // Limpa a memória cache
    return ob_get_clean();
}
add_shortcode('custom_button', 'shortcode_button');
