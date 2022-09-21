<?php
#shordecode do card formulario
function roger_card_ativo_view_func() {    
  //carrega o formulario de busca
  roger_form_stock_ativo();
  //Carrega o card do ativo
  roger_card_stock_ativo();

}
add_shortcode('roger_card_ativo', 'roger_card_ativo_view_func');
add_shortcode('roger-card-ativo', 'roger_card_ativo_view_func');
