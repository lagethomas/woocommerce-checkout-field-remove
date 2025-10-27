<?php
/**
 * Lógica principal para remoção de campos do checkout do WooCommerce.
 *
 * @package WCRF
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Impede acesso direto ao arquivo.
}

/**
 * Classe responsável pela funcionalidade principal do plugin.
 */
class WCRF_Core {

    /**
     * @var array Campos de Checkout do WooCommerce que podem ser removidos.
     */
    private $removable_fields = array(
        // Campos de Cobrança
        'billing_first_name'    => array( 'label' => 'Nome (Cobrança)', 'group' => 'billing' ),
        'billing_last_name'     => array( 'label' => 'Sobrenome (Cobrança)', 'group' => 'billing' ),
        'billing_company'       => array( 'label' => 'Empresa (Cobrança)', 'group' => 'billing' ),
        'billing_email'         => array( 'label' => 'Email (Cobrança)', 'group' => 'billing' ),
        'billing_phone'         => array( 'label' => 'Telefone (Cobrança)', 'group' => 'billing' ),
        'billing_country'       => array( 'label' => 'País (Cobrança)', 'group' => 'billing' ),
        'billing_state'         => array( 'label' => 'Estado/Província (Cobrança)', 'group' => 'billing' ),
        'billing_city'          => array( 'label' => 'Cidade (Cobrança)', 'group' => 'billing' ),
        'billing_postcode'      => array( 'label' => 'CEP/Código Postal (Cobrança)', 'group' => 'billing' ),
        'billing_address_1'     => array( 'label' => 'Endereço Linha 1 (Cobrança)', 'group' => 'billing' ),
        'billing_address_2'     => array( 'label' => 'Endereço Linha 2 (Cobrança)', 'group' => 'billing' ),

        // Campos de Entrega
        'shipping_first_name'   => array( 'label' => 'Nome (Entrega)', 'group' => 'shipping' ),
        'shipping_last_name'    => array( 'label' => 'Sobrenome (Entrega)', 'group' => 'shipping' ),
        'shipping_company'      => array( 'label' => 'Empresa (Entrega)', 'group' => 'shipping' ),
        'shipping_country'      => array( 'label' => 'País (Entrega)', 'group' => 'shipping' ),
        'shipping_state'        => array( 'label' => 'Estado/Província (Entrega)', 'group' => 'shipping' ),
        'shipping_city'         => array( 'label' => 'Cidade (Entrega)', 'group' => 'shipping' ),
        'shipping_postcode'     => array( 'label' => 'CEP/Código Postal (Entrega)', 'group' => 'shipping' ),
        'shipping_address_1'    => array( 'label' => 'Endereço Linha 1 (Entrega)', 'group' => 'shipping' ),
        'shipping_address_2'    => array( 'label' => 'Endereço Linha 2 (Entrega)', 'group' => 'shipping' ),
        
        // Outros campos (Se o campo existir no checkout, como Notas do Pedido)
        'order_comments'        => array( 'label' => 'Notas do Pedido', 'group' => 'order' ),
    );

    /**
     * Construtor da classe.
     * Adiciona os hooks para a remoção dos campos.
     *
     * @since 1.0.0
     */
    public function __construct() {
        // Aplica as remoções e validações
        add_action( 'init', array( $this, 'wcrf_setup_checkout_hooks' ) );
    }

    /**
     * Configura os filtros do WooCommerce se o plugin estiver ativo para operar.
     *
     * @since 1.0.0
     * @return void
     */
    public function wcrf_setup_checkout_hooks() {
        $settings = get_option( 'wcrf_settings', array() );
        
        // Verifica se há campos marcados para remoção. Se não houver, não precisamos de filtros.
        if ( ! empty( $settings ) ) {
            add_filter( 'woocommerce_billing_fields', array( $this, 'wcrf_remove_field_validation' ) );
            add_filter( 'woocommerce_shipping_fields', array( $this, 'wcrf_remove_field_validation' ) );
            add_filter( 'woocommerce_checkout_fields', array( $this, 'wcrf_remove_checkout_fields' ) );
        }
    }

    /**
     * Remove a validação dos campos se eles estiverem marcados para remoção.
     * (Esta função é aplicada aos filtros woocommerce_billing_fields e woocommerce_shipping_fields)
     *
     * @param array $fields Campos de endereço (billing ou shipping).
     * @return array Campos modificados.
     * @since 1.0.0
     */
    public function wcrf_remove_field_validation( $fields ) {
        $options = get_option( 'wcrf_settings', array() );
        
        foreach ( $this->removable_fields as $field_key => $details ) {
            // Verifica se o campo está marcado para remoção e se o campo existe no grupo atual
            if ( isset( $options[ $field_key ] ) && $options[ $field_key ] === 'on' && isset( $fields[ $field_key ] ) ) {
                // Remove a validação (obrigatório 'required') do campo
                if ( isset( $fields[ $field_key ]['validate'] ) ) {
                    unset( $fields[ $field_key ]['validate'] );
                }
                
                // Remove o atributo 'required' (para garantir a remoção visual no HTML)
                if ( isset( $fields[ $field_key ]['required'] ) ) {
                    $fields[ $field_key ]['required'] = false;
                }
            }
        }
        
        return $fields;
    }

    /**
     * Remove os campos do checkout se estiverem marcados para remoção.
     * (Esta função é aplicada ao filtro woocommerce_checkout_fields)
     *
     * @param array $fields Todos os campos do checkout agrupados.
     * @return array Campos modificados.
     * @since 1.0.0
     */
    public function wcrf_remove_checkout_fields( $fields ) {
        $options = get_option( 'wcrf_settings', array() );
        
        foreach ( $this->removable_fields as $field_key => $details ) {
            $group = $details['group'];
            $field_name = $field_key;
            
            // Se o campo estiver marcado para remoção E o grupo de campos existir
            if ( isset( $options[ $field_key ] ) && $options[ $field_key ] === 'on' ) {
                // Para a maioria dos campos de cobrança/entrega
                if ( isset( $fields[ $group ][ $field_name ] ) ) {
                    unset( $fields[ $group ][ $field_name ] );
                }
                // Para 'Notas do Pedido' (order_comments) que está em 'order'
                if ( 'order' === $group && isset( $fields[ $group ][ $field_name ] ) ) {
                     unset( $fields[ $group ][ $field_name ] );
                }
            }
        }
        
        return $fields;
    }

    /**
     * Retorna a lista de campos que podem ser removidos (usado na página de configurações).
     *
     * @since 1.0.0
     * @return array
     */
    public function wcrf_get_removable_fields() {
        return $this->removable_fields;
    }
}