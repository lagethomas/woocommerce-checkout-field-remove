# WooCommerce Checkout Field Remover

**Colaborador(es):** Thomas Marcelino
**Requer WooCommerce:** 3.0 ou superior
**Requer PHP:** 7.4 ou superior
**Versão Estável:** 1.0.0

## Descrição

O **WooCommerce Checkout Field Remover** é um plugin leve e focado que permite aos administradores de loja remover seletivamente campos padrão de endereço (Cobrança e Entrega) do formulário de checkout do WooCommerce.

O principal benefício é a capacidade de **desativar a validação de obrigatoriedade** (required) e **remover o campo do formulário** com um simples *switch*, simplificando o processo de checkout para clientes que não precisam de certos dados (como em casos de produtos virtuais ou específicos).

### Características

* **Controle de Campos:** Remova campos como País, Estado, Cidade, CEP, e Endereço (Linha 1 e 2) tanto de Cobrança quanto de Entrega.
* **Segurança e Performance:** Implementado seguindo os padrões de codificação do WordPress (WP Coding Standards).
* **Página de Configuração Moderna:** Interface intuitiva na administração do WooCommerce.

## Instalação

1.  Faça upload da pasta `woocommerce-checkout-field-remover` para o diretório `/wp-content/plugins/`.
2.  Ative o plugin através do menu 'Plugins' no WordPress.
3.  Acesse as configurações em **WooCommerce > Remover Campos Checkout** para selecionar os campos a serem removidos.

## Changelog

### 1.0.0 - 2025-10-26

* Lançamento inicial.
* Funcionalidade de remoção e desativação de validação de campos de checkout (Cobrança e Entrega).
* Página de administração com *switches* de controle.
* Uso de Vanilla JS e prefixação de código (`wcrf_`).