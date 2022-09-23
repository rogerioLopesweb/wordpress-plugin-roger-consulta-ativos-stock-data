<?php
#Registra no menu admin Em Configurações 
function roger_stock_add_menu_settings_page() {
    add_options_page( 'Roger Ativos ', 'Roger Ativos', 'manage_options', 'roger-config-api-plugin', 'roger_stock_render_plugin_settings_page' );
}
add_action( 'admin_menu', 'roger_stock_add_menu_settings_page' );
#pagina de configuração no admin
function roger_stock_render_plugin_settings_page() {
    ?>
    <h2>ROGER API STOCK DATA CONFIGURAÇÕES</h2>
    <hr>
    <form action="options.php" method="post">
        <?php 
       // roger_stock_plugin_section_text();
        settings_fields( 'roger_stock_plugin_options' );
        do_settings_sections( 'roger_stock_plugin_options' ); ?>
        <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Salvar' ); ?>" />
        <br>  <br>  <br>
    </form>

    <?php
     //Chama o card
     roger_card_stock_ativo();
}
#registra os campos do no options
function roger_stock_settings() {
    register_setting( 'roger_stock_plugin_options', 'roger_stock_plugin_options', 'roger_stock_plugin_options_validate' );
    add_settings_section( 'api_settings', 'Defina a base de consulta e cadastre a chave da API ', 'roger_stock_plugin_section_text', 'roger_stock_plugin_options' );
    add_settings_field( 'roger_stock_plugin_setting_api_key', 'Informe a chave', 'roger_stock_plugin_setting_api_key', 'roger_stock_plugin_options', 'api_settings' );
    add_settings_field( 'roger_stock_plugin_setting_cad_view', 'Visualização do card', 'roger_stock_plugin_setting_combo_view', 'roger_stock_plugin_options', 'api_settings' );
}
add_action( 'admin_init', 'roger_stock_settings' );
#Monta o texto para exibicao na sessao, este texto tem a explicacao de como congurar o plugin
function  roger_stock_plugin_section_text() {
    $texto = "<h4>Alphavantage Stock Base API</h4>";
    $texto .= '<p><b>(i)</b> Para ter acesso a API da Alphavantage é necessário criar uma chave em <a href="https://www.alphavantage.co/support/#api-key" target="_blank">https://www.alphavantage.co/support/#api-key</a>.</p>';
    $texto .= "<p><b>(ii)</b> Para este teste da roger foi gerada a chave abaixo que atualmente não tem custo pois os dados são gerados diariamente.</p>";
    $texto .= "<h3>Chave Base Alphavantage: XPTO</h3>";
    $texto .= "<h6>Alguns Ativos: AAPL, TSLA, AMC, AMD, CEI, GME, COMP, AMZN, API</h6>";
    $texto .= "Observações<br>";
    $texto .= "*Em caso de lentidão ou oscilação da API o card não carrega. <br>";
    $texto .= "*Quando coloca um ativo inesistente para a base de pesquisa o card não carrega. <br>";
    $texto .= '<h3>Copie o shortecode [roger_card_ativo] e cole na página desejada</h3>';
    echo $texto;
    echo "<hr>";
}
#Campo que o usuario irá informar a chave da Api
function roger_stock_plugin_setting_api_key() {
    $options = get_option( 'roger_stock_plugin_options' );
    echo "<input id='roger_stock_plugin_setting_api_key' name='roger_stock_plugin_options[roger_stock_api_key]' type='text' value='" . esc_attr( $options['roger_stock_api_key'] ) . "' required />";
}
#Combo que o usuário poderá informar se vai usar shorcode ou carregar o card depois da tag body
function roger_stock_plugin_setting_combo_view() {
    $options = get_option( 'roger_stock_plugin_options' );
    echo "<select id='roger_stock_plugin_setting_view' name='roger_stock_plugin_options[roger_card_view]' required>";
    echo "<option value='roger_tag_body_view' ". selected( $options['roger_card_view'], 'roger_tag_body_view' ) ." >Exibir depois da tag body</option>";
    echo "<option value='roger_shortcode_view' ". selected( $options['roger_card_view'], 'roger_shortcode_view' ) ." >Usar shorcode</option>";
    echo "</select>";
}
