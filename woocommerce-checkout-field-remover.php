<?php
/**
 * Plugin Name: WooCommerce Checkout Field Remove
 * Description: Gerencia quais campos do checkout do WooCommerce devem ser removidos e desativa suas validações.
 * Version: 1.0.1
 * TAG: true
 * Author: Thomas Marcelino
 * Author URI: https://wpmasters.com.br
 * License: GPL2
 */

// Se não for definido, impede o acesso direto
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define o versionamento do código (para CSS/JS) baseado no tempo atual para evitar cache
if ( ! defined( 'WCRF_CODE_VERSION' ) ) {
    define( 'WCRF_CODE_VERSION', time() );
}

// Define a versão principal do plugin. Centralizamos a versão aqui.
if ( ! defined( 'WCRF_PLUGIN_VERSION' ) ) {
    define( 'WCRF_PLUGIN_VERSION', '1.0.1' ); // << Versão do Semantic Versioning
}

// Define o caminho base do plugin.
if ( ! defined( 'WCRF_PLUGIN_DIR' ) ) {
    define( 'WCRF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

/**
 * Classe principal do plugin.
 * A inicialização é feita aqui, carregando as classes de funcionalidade e configurações.
 */
class WCRF_Plugin {

    /**
     * Construtor da classe.
     * Carrega as dependências e hooks.
     *
     * @access public
     */
    public function __construct() {
        $this->load_dependencies();
        $this->setup_hooks();
    }

    /**
     * Carrega os arquivos de dependência.
     *
     * @access private
     */
    private function load_dependencies() {
        // Carrega a classe de configurações da administração
        require_once WCRF_PLUGIN_DIR . 'includes/class-wcrf-settings.php';

        // Carrega a classe com a lógica principal do checkout
        require_once WCRF_PLUGIN_DIR . 'includes/class-wcrf-core.php';
    }

    /**
     * Configura os hooks do WordPress.
     *
     * @access private
     */
    private function setup_hooks() {
        // Inicializa as classes
        if ( is_admin() ) {
            new WCRF_Settings();
        }

        // Inicializa a lógica principal em todas as páginas
        new WCRF_Core();
    }
}

/**
 * Inicia o plugin.
 *
 * @since 1.0.0
 * @return void
 */
function run_wcrf_plugin() {
    // Verifica se o WooCommerce está ativo
    if ( ! class_exists( 'WooCommerce' ) ) {
        // Exibe um aviso no admin se o WooCommerce não estiver ativo.
        add_action( 'admin_notices', 'wcrf_missing_woocommerce_notice' );
        return;
    }
    
    // Inicia o plugin.
    new WCRF_Plugin();
}
add_action( 'plugins_loaded', 'run_wcrf_plugin' );

/**
 * Exibe um aviso se o WooCommerce não estiver instalado/ativo.
 *
 * @since 1.0.0
 * @return void
 */
function wcrf_missing_woocommerce_notice() {
    $class = 'notice notice-error';
    $message = esc_html__( 'WooCommerce Checkout Field Remover requer que o plugin WooCommerce esteja ativo.', 'woocommerce-checkout-field-remover' );
    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
}

/**
 * Carrega o Text Domain para tradução (i18n).
 *
 * @since 1.0.0
 * @return void
 */
function wcrf_load_textdomain() {
    load_plugin_textdomain( 'woocommerce-checkout-field-remover', false, basename( WCRF_PLUGIN_DIR ) . '/languages' );
}
add_action( 'init', 'wcrf_load_textdomain' );

// Fim do arquivo principal.