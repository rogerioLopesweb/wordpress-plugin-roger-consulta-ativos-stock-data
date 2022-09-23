<?php
#funcao verifica o que foi configurado no admin Menu>Configurações>Roger Ativos
#Na pagina de configuração do plugin tem duas opçoes de base para consulta
#Cada opção te sua chave
function roger_consulta_api_ativo($simbol){
   //pega os cadastrado no admin em Configurações Roger Ativos
   $options = get_option('roger_stock_plugin_options');
   $api_key = $options['roger_stock_api_key']; //Chave da APi 
   //retorna os dados da consulta
   return roger_consulta_api_base_alphavantage_stock($api_key, $simbol);
}
#Consulta a base api alphavantage stock
function roger_consulta_api_base_alphavantage_stock($api_key, $simbol){
   //EndPoint: url da base alphavantege busca os dados da empresa  do ativo
   $url = "https://www.alphavantage.co/query?function=OVERVIEW&symbol=".$simbol;
   $url .= "&apikey=".$api_key;
   //cria um array de retorno vazio
   $dados =  array('status' => "OK", 
   	'simbolo' => "---", 
   	'nome' => "----",
   	'data_consulta' => "----", 
   	'valor_abertura' => "0", 
   	'valor_auto' => "0", 
   	'valor_baixo' => "0", 
   	'valor_fechamento' => "0", 
   	'volume' => "0", 
   	'valor_variacao' => "0", 
   	'valor_variacao_porcentual' => "0",
   	'situacao_final' => "+"
   );
   $curl = curl_init();
   curl_setopt_array($curl, array(
  		CURLOPT_URL => $url,
  		CURLOPT_RETURNTRANSFER => true,
  		CURLOPT_ENCODING => "",
  		CURLOPT_MAXREDIRS => 10,
  		CURLOPT_TIMEOUT => 30,
  		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  		CURLOPT_CUSTOMREQUEST => "GET",
  ));
  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);
  //Verifica se deu erro
  if ($err) {
  // retorna o erro
  return array('status' => "Falha", 'Erro' => $err);
 }else{
     //Converte o json em array
	 $result = json_decode($response, true);
	//se a consulta na api retornar erro, aborta e não exibe o card 
	 if (count($result) == 0) {
	    return array('status' => "Falha", 'Erro' => 'Verifique as configurações do plugin');
	 }
	 //carrega as variaveis
	 $nome = $result['Name'] . " ". $result['AssetType'];
   //EndPoint: url da base alphavantege busca os dados de contacao diaria a ultima data hora valida
   $url = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=".$simbol;
   $url .= "&apikey=".$api_key;
	$curl = curl_init();
	   curl_setopt_array($curl, array(
	  		CURLOPT_URL => $url,
	  		CURLOPT_RETURNTRANSFER => true,
	  		CURLOPT_ENCODING => "",
	  		CURLOPT_MAXREDIRS => 10,
	  		CURLOPT_TIMEOUT => 30,
	  		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  		CURLOPT_CUSTOMREQUEST => "GET",
	  ));
	  $response = curl_exec($curl);
	  $err = curl_error($curl);
	  curl_close($curl);
	  //Verifica se deu erro
	  if ($err) {
	  // retorna o erro
	  return array('status' => "Falha", 'Erro' => $err);
	 }else{
       $result = json_decode($response, true);
       //se a consulta na api retornar erro, aborta e não exibe o card 
	   if (array_key_exists('Error Message', $result)) {
         return array('status' => "Falha", 'Erro' => 'Verifique as configurações do plugin');
        }
     //pega a ultima data de consulta na bolsa
     $data_consulta = $result['Meta Data']["3. Last Refreshed"];
     //com base nada data pega os ultimos valores 
     $valoresArrray = $result['Time Series (Daily)'];
     $valores = array();
      //valida se pegou a data corretamente
      if (!is_array($valoresArrray)) {
          return array('status' => "Falha", 'Erro' => 'Verifique as configurações do plugin');
      }
	 foreach($valoresArrray[$data_consulta] as $row) {
	 	$valores[] = $row;
      }
      //pega a data de fechamento anterior
     $data_consulta_fechamento_anterior ="";
     $count = 0;
    foreach ($valoresArrray as $key => $item) {
	 	 if($key != $data_consulta && $count ==0){
            $data_consulta_fechamento_anterior = $key;
            $count = 1;
	 	 }
     }
     //pega o valor do fechamento dia anterior
      $valoresFechamentoAnterior = array();
      foreach($valoresArrray[$data_consulta_fechamento_anterior ] as $row) {
      $valoresFechamentoAnterior[] = $row;
      }
    $closeFechamentoDiaAnterior = $valoresFechamentoAnterior[3]; // valor da cota fechado no dia anterior
	 $open = $valores[0]; // valor da cota na abertura
	 $high = $valores[1]; // valor mais auto da conta no dia
	 $low  = $valores[2]; // valor mais baixo da cota no dia
	 $close = $valores[3]; // valor da cota fechado no dia
	 $volume = $valores[4]; // valume de negociacoes no dia
	 $valor_variacao  =  $close - $closeFechamentoDiaAnterior; //calcula o valor de diferenca, da data atual com data de fechamento anterior
	 $valor_porcentagem =  ($valor_variacao / $closeFechamentoDiaAnterior) * 100; // calcula o valor de direnca em porcentagem
	 if($closeFechamentoDiaAnterior < $close){
	 	$situacao_final = "-"; 
	 }else{
	 	$situacao_final = "+"; 
	 }
     //carrado o array para retorno
     $dados['simbolo'] = $simbol;
     $dados['nome'] = $nome;
     $dados['data_consulta'] = $data_consulta;
     $dados['valor_abertura'] = $open;
     $dados['valor_auto'] = $high;
     $dados['valor_baixo'] = $low;
     $dados['valor_fechamento'] = $close;
     $dados['volume'] = $volume;
     $dados['valor_variacao'] = $valor_variacao;
     $dados['valor_variacao_porcentual'] = $valor_porcentagem;
     $dados['situacao_final'] = $situacao_final;
	 }
   return $dados;
 }
}
/*
Segue abaixo a documentacao e apis com chaves utilizadas neste plugin
Base Alphavantage
-https://www.alphavantage.co/documentation/
-https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=AAPL&apikey=XPTO
-https://www.alphavantage.co/query?function=OVERVIEW&symbol=AAPL&apikey=XPTO
*/
