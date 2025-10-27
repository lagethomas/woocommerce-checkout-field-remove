<?php
/**
 * Script de desinstalação para WooCommerce Checkout Field Remover.
 * Limpa todas as opções e dados do plugin ao ser desinstalado.
 *
 * @package WCRF
 * @since 1.0.0
 */

// Se uninstall não for chamado pelo WordPress, sair.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Opções a serem removidas.
$option_name = 'wcrf_settings';

// Se a opção existir, remove.
delete_option( $option_name );

// Se o plugin usasse Custom Post Types ou Metadados, a limpeza seria mais complexa.
// Para um plugin simples de opções, apenas a opção é removida.