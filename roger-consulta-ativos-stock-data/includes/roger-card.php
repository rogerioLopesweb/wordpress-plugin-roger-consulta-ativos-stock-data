<?php
#funcao que gera o card com base nos dados da api
function roger_card_stock_ativo()
{
  //pega o valor via get
  $simbol = $_GET['r-ativo'] ? : "AAPL";
  //recebe os dados da api
  $dados = roger_consulta_api_ativo(strtoupper($simbol));
  //exibe na tela somente se o status for OK
  if($dados["status"] == "OK"){
    //carrega as variaveis
	  $roger_stock_ativo_titulo = $dados["nome"]; //"Aplle Inc. Common Stock";
	  $roger_stock_ativo_sigla = $dados['simbolo'];// "AAPL";
	  $roger_stock_status = "Listed";
    $roger_stock_numero = "100";
    $roger_stock_ativo_contacao = number_format($dados['valor_fechamento'],2);
    $roger_stock_ativo_variacao = number_format($dados['valor_variacao'],2);//"-1.67";
    $roger_stock_ativo_variacao_porcentual = number_format($dados['valor_variacao_porcentual'],2);//"-1.1%";
    $data = new DateTime($dados['data_consulta']);
    $roger_stock_fechamento  = "CLOSED AT 4:00 PM ET ON ". strtoupper($data->format('M'));
    $roger_stock_ativo_data = $data->format('d, Y');//"16, 2022";
    $situacao_final = $dados['situacao_final'];
    //verifica se a cotacao fechou no positivo ou negativo
    if($situacao_final == "+")
    {
      $str_variacao_class = "card-roger-stock-ativo-variacao-postivo";
    }else{
      $str_variacao_class = "card-roger-stock-ativo-variacao-negativo";
      $situacao_final = "";
    }
	?>
  	<style type="text/css">
      .card-roger-stock{
       background: rgb(92,14,130);
       background: linear-gradient(90deg, rgba(92,14,130,1) 17%, rgba(48,43,121,1) 51%, rgba(43,100,121,1) 83%);
        width: 100%;
        display: block;
        padding-top: 20px;
        padding-left: 10px;
        padding-right: 10px;
        padding-bottom: 20px;
      }
  		.card-roger-stock-body div{
        font-family: "nudista-web",Helvetica,Arial,sans-serif;
        max-width: 500px;
        line-height: 35px;
        margin: 0 auto;
        text-shadow: -1px 0px 2px rgba(30,26,46,0.43);
        display: block;
  		}
  		.card-roger-stock-titulo{
        color:#fff;
        font-size: 22px;
        font-weight: 600;
        text-align: left;
        display: block;
  		}
  		.card-sigla{
        color:#fff;
        font-size: 22px;
        font-weight: 100;
  		}
  		.card-roger-stock-status, .card-roger-stock-status a{
        color:#fff !important;
        font-size: 13px;
        font-weight: 100;
        text-align: left;
        text-decoration: none;
  		}
      .card-roger-stock-status a:hover{
        text-decoration: underline;
      }
  		.card-roger-stock-status-span{
        color:#fff;
        font-size: 13px;
        font-weight: none;
        padding-left: 3px;
  		}
  		.card-roger-stock-ativo-contacao{
        color:#fff;
        font-size: 19px;
        font-weight: 600;
        text-align: right;
        display: block;
  		}
      .card-roger-stock-ativo-variacao-postivo{
        color:#5B914F;
        font-size: 15px;
        font-weight: 600;
      }
  		.card-roger-stock-ativo-variacao-negativo{
        color:#db312f;
        font-size: 15px;
        font-weight: 600;
        padding: 0px;
  		}
  		.card-roger-stock-ativo-data, .card-roger-stock-fechamento{
        color:#fff;
        font-size: 9px;
        text-align: right;
        display: block;
        padding: 0px;
        line-height: 11px !important;
  		}
  	</style>
  <div class="card-roger-stock"> 
  	<div class="card-roger-stock-body"> 
  		<div class="card-roger-stock-titulo"><?=$roger_stock_ativo_titulo;?> <span class="card-sigla">(<?=$roger_stock_ativo_sigla;?>)</span></div>
  		<div class="card-roger-stock-status"><a href="https://www.nasdaq.com/market-activity/stocks/screener?exchange=nasdaq" target="_blank"> Nasdaq <span class="card-roger-stock-status-span"><?=$roger_stock_status;?></span></a> <a href="https://www.nasdaq.com/market-activity/quotes/nasdaq-ndx-index" target="_blank">Nasdaq <span class="card-roger-stock-status-span"><?=$roger_stock_numero;?></span></a></div>
  		<div class="card-roger-stock-ativo-contacao">$<?=$roger_stock_ativo_contacao;?> <span class="<?=$str_variacao_class;?>"><?=$situacao_final;?><?=$roger_stock_ativo_variacao;?>(<?=$situacao_final;?><?=$roger_stock_ativo_variacao_porcentual;?>%)</span></div>
  		<div class="card-roger-stock-fechamento"><?=$roger_stock_fechamento;?></div>
      <div class="card-roger-stock-ativo-data"><?=$roger_stock_ativo_data;?></div>
  	</div>
  </div>
<?php
  }
}

function roger_form_stock_ativo(){
   //pega o valor via get
   $simbol = $_GET['r-ativo'] ? : "AAPL";
   ?>
   <style type="text/css">
      .card-roger-formstock{
       background: rgb(92,14,130);
       background: linear-gradient(90deg, rgba(92,14,130,1) 17%, rgba(48,43,121,1) 51%, rgba(43,100,121,1) 83%);
        width: 100%;
        display: block;
        padding-top: 20px;
        padding-left: 10px;
        padding-right: 10px;
        padding-bottom: 20px;
      }
  		.card-form-roger-stock-body div{
        font-family: "nudista-web",Helvetica,Arial,sans-serif;
        max-width: 500px;
        margin: 0 auto;
        text-shadow: -1px 0px 2px rgba(30,26,46,0.43);
        display: block;
  		}
      .card-form-roger-stock-body a{
        font-family: "nudista-web",Helvetica,Arial,sans-serif;
        text-shadow: -1px 0px 2px rgba(30,26,46,0.43);
  		}
      .card-form-roger-stock-ativos, a{
        font-size: 11px;
        color:#fff !important;
      }
      .frm-roger-stock div{
        margin: 0 auto;
        width: 500px;
      }
      #frm-roger-stock input {border-radius:10px; border-width:2px; border-style:solid; padding:8px; font-size:10px; border-color:#e6e7fa; box-shadow: 0px 0px 5px 0px rgba(64,64,64,.29);  }
      #frm-roger-stock input:focus { outline:none; } 
      .frm-roger-button {
        background-color:#1b79ab;
        border-radius:10px;
        border:1px solid #29668f;
        display:inline-block;
        cursor:pointer;
        color:#ffffff;
        font-family:Arial;
        font-size:10px;
        padding:9px 13px;
        text-decoration:none;
        text-shadow:0px 1px 0px #3d768a;
        margin-left: -30px;
      }
      .frm-roger-button:hover {
        background-color:#408c99;
      }
      .frm-roger-button:active {
        position:relative;
        top:1px;
      }
  </style>
  <div class="card-roger-formstock">
    <div class="card-form-roger-stock-body">
      <div class="frm-roger-stock">
        <form name="frm-roger-stock" id="frm-roger-stock" method="GET">
          <input name="r-ativo" id="r-ativo" value="<?=strtoupper($simbol);?>" placeholder="Buscar ativo" require />
          <button type="submit" class="frm-roger-button">Buscar</button>
          <div class="card-form-roger-stock-ativos">
            <br>Ativos: 
            <a href="?r-ativo=AAPL">AAPL</a>, 
            <a href="?r-ativo=TSLA">TSLA</a>, 
            <a href="?r-ativo=AMC">AMC</a>, 
            <a href="?r-ativo=AMD">AMD</a>, 
            <a href="?r-ativo=CEI">CEI</a>, 
            <a href="?r-ativo=GME">GME</a>, 
            <a href="?r-ativo=COMP">COMP</a>, 
            <a href="?r-ativo=AMZN">AMZN</a>, 
            <a href="?r-ativo=API">API</a>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php
}
