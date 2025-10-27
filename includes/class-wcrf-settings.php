<?php
/**
 * Gerencia a página de configurações do plugin WooCommerce Checkout Field Remover.
 *
 * @package WCRF
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Impede acesso direto ao arquivo.
}

/**
 * Classe responsável pela página de configurações e ativos (assets) do plugin.
 */
class WCRF_Settings {

    /**
     * Chave da opção no banco de dados.
     *
     * @var string
     */
    private $option_name = 'wcrf_settings';

    /**
     * Slug da página de administração.
     *
     * @var string
     */
    private $page_slug = 'wcrf-checkout-remover';

    /**
     * Construtor da classe.
     *
     * @since 1.0.0
     */
    public function __construct() {
        // Ação para adicionar o item de menu.
        add_action( 'admin_menu', array( $this, 'wcrf_add_admin_menu' ) );

        // Ação para enfileirar scripts e estilos na página de administração.
        add_action( 'admin_enqueue_scripts', array( $this, 'wcrf_enqueue_admin_assets' ) );

        // Ação para salvar as configurações (usando o hook admin_post para segurança).
        add_action( 'admin_post_wcrf_save_settings', array( $this, 'wcrf_handle_form_submission' ) );
    }

    /**
     * Adiciona o menu de configurações ao WooCommerce.
     *
     * @since 1.0.0
     * @return void
     */
    public function wcrf_add_admin_menu() {
        // Adiciona a página como submenu do WooCommerce
        add_submenu_page(
            'woocommerce',
            esc_html__( 'Remover Campos do Checkout', 'woocommerce-checkout-field-remover' ), // Título da Página
            esc_html__( 'Remover Campos Checkout', 'woocommerce-checkout-field-remover' ), // Título do Menu
            'manage_options', // Capacidade requerida
            $this->page_slug,
            array( $this, 'wcrf_settings_page_html' ) // Função de callback que gera o HTML
        );
    }

    /**
     * Enfileira os scripts e estilos da administração.
     *
     * @param string $hook O slug da página de administração atual.
     * @since 1.0.0
     * @return void
     */
    public function wcrf_enqueue_admin_assets( $hook ) {
        $expected_hook = 'woocommerce_page_' . $this->page_slug;
        if ( $expected_hook !== $hook ) {
            return;
        }

        $base_url = plugin_dir_url( WCRF_PLUGIN_DIR . 'woocommerce-checkout-field-remover.php' );

        // Enfileira o CSS moderno da administração
        wp_enqueue_style(
            'wcrf-admin-style',
            $base_url . 'assets/css/wcrf-admin.css',
            array(),
            WCRF_CODE_VERSION // Versão dinâmica para evitar cache
        );

        // Enfileira o Vanilla JS
        wp_enqueue_script(
            'wcrf-admin-script',
            $base_url . 'assets/js/wcrf-admin.js',
            array(),
            WCRF_CODE_VERSION, // Versão dinâmica para evitar cache
            true // Coloca no rodapé (footer)
        );
    }

    /**
     * Lida com o envio do formulário de configurações (sanitização e salvamento).
     *
     * @since 1.0.0
     * @return void
     */
    public function wcrf_handle_form_submission() {
        // 1. Verificação de Capabilities (segurança)
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Você não tem permissão para acessar esta página.', 'woocommerce-checkout-field-remover' ) );
        }

        // 2. Verificação de Nonce (segurança)
        $nonce_field = 'wcrf_save_settings_nonce_field';
        $nonce_action = 'wcrf_save_settings_nonce_action';

        if ( ! isset( $_POST[ $nonce_field ] ) || ! wp_verify_nonce( sanitize_key( $_POST[ $nonce_field ] ), $nonce_action ) ) {
            wp_die( esc_html__( 'Ação de segurança inválida. Por favor, tente novamente.', 'woocommerce-checkout-field-remover' ) );
        }

        // 3. Processamento e Sanitização dos Dados
        $new_options = array();
        
        // Instancia a classe principal para obter a lista de campos válidos.
        $core = new WCRF_Core(); 
        $removable_fields = $core->wcrf_get_removable_fields();

        foreach ( $removable_fields as $key => $details ) {
            // Verifica se o checkbox foi marcado (se existir no POST) e salva 'on', caso contrário, não salva a chave.
            if ( isset( $_POST[ $key ] ) && 'on' === sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) ) {
                $new_options[ $key ] = 'on';
            }
        }
        
        // 4. Salva as configurações.
        update_option( $this->option_name, $new_options );

        // 5. Redireciona o usuário de volta com uma mensagem de sucesso.
        $redirect_url = add_query_arg( array(
            'page' => $this->page_slug,
            'settings-updated' => 'true'
        ), admin_url( 'admin.php' ) );

        wp_redirect( esc_url_raw( $redirect_url ) );
        exit;
    }

    /**
     * Gera o HTML da página de configurações.
     *
     * @since 1.0.0
     * @return void
     */
    public function wcrf_settings_page_html() {
        if ( ! class_exists( 'WCRF_Core' ) ) {
             echo '<div class="notice notice-error"><p>' . esc_html__( 'Erro: A classe principal do plugin não foi carregada.', 'woocommerce-checkout-field-remover' ) . '</p></div>';
             return;
        }
        
        $core = new WCRF_Core();
        $fields = $core->wcrf_get_removable_fields();
        $options = get_option( $this->option_name, array() );
        
        // Versão do plugin para o badge
        $plugin_version = defined( 'WCRF_PLUGIN_VERSION' ) ? WCRF_PLUGIN_VERSION : 'N/A';
        ?>
        <div class="wrap wcrf-admin-page">
            <h1 class="wp-heading-inline"><?php esc_html_e( 'Configurações: Remover Campos do Checkout do WooCommerce', 'woocommerce-checkout-field-remover' ); ?></h1>
            <span class="wcrf-badge">v<?php echo esc_html( $plugin_version ); ?></span>
            
            <?php 
            if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] === 'true' ) {
                echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Configurações salvas com sucesso!', 'woocommerce-checkout-field-remover' ) . '</p></div>';
            }
            ?>

            <p class="description">
                <?php esc_html_e( 'Use os switches abaixo para remover permanentemente os campos selecionados do formulário de checkout e desativar a validação de obrigatoriedade. (Os campos removidos não aparecerão para o cliente ou para você no pedido).', 'woocommerce-checkout-field-remover' ); ?>
            </p>

            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                <input type="hidden" name="action" value="wcrf_save_settings">
                <?php wp_nonce_field( 'wcrf_save_settings_nonce_action', 'wcrf_save_settings_nonce_field' ); ?>

                <div class="wcrf-cards-container">
                    <?php 
                    $current_group = '';
                    foreach ( $fields as $key => $details ) : 
                        $is_checked = isset( $options[ $key ] ) && $options[ $key ] === 'on';

                        // Agrupamento por tipo (Billing, Shipping, Order)
                        if ( $current_group !== $details['group'] ) {
                            if ( $current_group !== '' ) {
                                // Fecha o grupo anterior se não for o primeiro
                                echo '</div>'; 
                            }
                            $current_group = $details['group'];
                            $group_title = $this->wcrf_get_group_title( $current_group );
                            
                            // Inicia novo grupo com título
                            echo '<h2 class="wcrf-group-title">' . esc_html( $group_title ) . '</h2>';
                            echo '<div class="wcrf-fields-grid">';
                        }
                    ?>
                        <div class="wcrf-card <?php echo esc_attr( $details['group'] ); ?>">
                            <div class="wcrf-card-content">
                                <label for="<?php echo esc_attr( $key ); ?>" class="wcrf-card-label">
                                    <?php echo esc_html( $details['label'] ); ?>
                                </label>
                                <label class="wcrf-switch">
                                    <input type="checkbox" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" <?php checked( $is_checked ); ?>>
                                    <span class="wcrf-slider round"></span>
                                </label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php 
                // Fecha o último grupo aberto
                if ( $current_group !== '' ) {
                    echo '</div>';
                }
                ?>
                
                <?php submit_button( esc_html__( 'Salvar Alterações', 'woocommerce-checkout-field-remover' ), 'primary large wcrf-save-button' ); ?>
            </form>
            
            <?php $this->wcrf_display_admin_ad(); ?>

        </div>
        <?php
    }

    /**
     * Retorna o título de exibição para um grupo de campos.
     *
     * @since 1.0.0
     * @param string $group O slug do grupo (billing, shipping, order).
     * @return string O título traduzido.
     */
    private function wcrf_get_group_title( $group ) {
        switch ( $group ) {
            case 'billing':
                return esc_html__( 'Campos de Cobrança', 'woocommerce-checkout-field-remover' );
            case 'shipping':
                return esc_html__( 'Campos de Entrega', 'woocommerce-checkout-field-remover' );
            case 'order':
                return esc_html__( 'Outros Campos do Pedido', 'woocommerce-checkout-field-remover' );
            default:
                return esc_html__( 'Campos Gerais', 'woocommerce-checkout-field-remover' );
        }
    }

    /**
     * Exibe a propaganda do site wpmasters.com.br na página de administração.
     *
     * @since 1.0.0
     * @return void
     */
    private function wcrf_display_admin_ad() {
        ?>
        <div class="wcrf-ad-box">
            <h3 class="wcrf-ad-title"><?php esc_html_e( 'Desenvolvimento Profissional em WordPress e WooCommerce', 'woocommerce-checkout-field-remover' ); ?></h3>
            <p class="wcrf-ad-content">
                <?php esc_html_e( 'Precisa de funcionalidades personalizadas, otimização de performance ou assistência em código avançado? Conte com a experiência de Thomas Marcelino e a WP Masters.', 'woocommerce-checkout-field-remover' ); ?>
            </p>
            <a href="https://wpmasters.com.br" target="_blank" class="wcrf-ad-button">
                <?php esc_html_e( 'Visite wpmasters.com.br', 'woocommerce-checkout-field-remover' ); ?>
            </a>
        </div>
        <?php
    }
}