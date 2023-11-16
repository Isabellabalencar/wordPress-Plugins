<?php
/**
 * Plugin Name: Histórico de Registros
 * Description: Adiciona um comando WP-CLI para imprimir um relatório de histórico de registros.
 * Version: 1.0
 * Author: Seu Nome
 */

if ( defined( 'WP_CLI' ) && WP_CLI ) {
  
    function historico_registros_mostrar() {
        // Obtenha as últimas entradas do histórico de registros (substitua isso com sua lógica real).
        $historico_entries = get_last_historico_entries();

        // Imprima o relatório.
        foreach ( $historico_entries as $entry ) {
            WP_CLI::line( $entry );
        }
    }

    // Registre o comando.
    WP_CLI::add_command( 'historico-registros mostrar', 'historico_registros_mostrar' );
}


