# WooCommerce Checkout Field Remover

| Status | Informações |
| :--- | :--- |
| **Versão Atual** | 1.0.0 |
| **Licença** | GPL2 |
| **Requer WooCommerce** | 3.0+ |
| **Requer PHP** | 7.4+ |
| **Desenvolvedor** | Thomas Marcelino |

## 🚀 Objetivo

O **WooCommerce Checkout Field Remover** é um plugin focado em otimizar e simplificar o processo de checkout do WooCommerce. Ele permite que o administrador da loja remova seletivamente campos de endereço (Cobrança e Entrega) e outros campos do formulário de checkout.

Ao remover um campo, o plugin garante duas ações cruciais:
1.  **Remove o campo** da visualização do checkout.
2.  **Desativa a validação de obrigatoriedade** (required) associada a ele.

Isso é ideal para lojas que vendem produtos virtuais, serviços, ou que utilizam métodos de entrega que não dependem de dados geográficos completos.

## ✨ Características Principais

* **Controle Granular:** Habilite/desabilite a remoção de campos individuais (Nome, Sobrenome, Empresa, Telefone, CEP, Endereços, Notas do Pedido, etc.).
* **Interface Moderna:** Página de administração amigável e intuitiva com layout em **Cards (3 por linha)** e switches modernos para controle.
* **Performance e Segurança:** Desenvolvido seguindo os Padrões de Codificação do WordPress (WP Coding Standards).
* **Vanilla JS:** Não utiliza jQuery no frontend ou backend, priorizando o Vanilla JavaScript para otimização de ativos.
* **Localização (i18n):** Pronto para tradução.

## 📦 Instalação e Uso

### 1. Instalação Manual

1.  Baixe o arquivo ZIP da última versão do repositório.
2.  Descompacte a pasta `woocommerce-checkout-field-remover`.
3.  Faça upload da pasta para o diretório `/wp-content/plugins/` da sua instalação WordPress.
4.  Ative o plugin no menu 'Plugins' do seu painel de administração.

### 2. Configuração

1.  No painel de administração, navegue até **WooCommerce > Remover Campos Checkout**.
2.  Você encontrará uma lista completa de campos de Cobrança, Entrega e Pedido, exibidos em um layout de cards.
3.  Use os **switches** para ativar a remoção dos campos desejados.
4.  Clique em **Salvar Alterações**.

Os campos desativados serão imediatamente removidos e não serão mais exigidos no checkout da sua loja.

<img width="995" height="913" alt="image" src="https://github.com/user-attachments/assets/03c2ef61-93a1-4483-aace-6ddd9ab053de" />

## 🛠️ Estrutura do Plugin

O plugin é desenvolvido em PHP Orientado a Objetos (OOP), seguindo o padrão de arquitetura sugerido para plugins WordPress.

Feito com ❤️ por **[WP Masters](https://wpmasters.com.br)**
