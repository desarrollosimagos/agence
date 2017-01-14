<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use DB;
use App\Quotation;

class PrincipalController extends Controller
{
    //
	public function index(){
		$tipo = array('0','1','2');
		$users = DB::table('cao_usuario')
			->select('cao_usuario.co_usuario', 'cao_usuario.no_usuario')
			->join('permissao_sistema','cao_usuario.co_usuario','=','permissao_sistema.co_usuario')
			->where([
				['permissao_sistema.co_sistema', '=', '1'],	
				['permissao_sistema.in_ativo', '=', 's'],
			])
			->whereIn('permissao_sistema.co_tipo_usuario',$tipo)
            ->get();
		return view('principal.index')
			->with('users', $users);
	}
	
	public function relatorio(Request $request){
		$table = "";
		
		//Fechas
		$mes_ini = (int)$request->input('mes_ini');
		$mes_fin = (int)$request->input('mes_fin');
		$anio_ini = (int)$request->input('anio_ini');
		$anio_fin = (int)$request->input('anio_fin');
		
		//seleccionados
		$seleccionados = $request->input('seleccionados');
		$co_usuario =  explode(",", $seleccionados);
		
		$longitud = count($co_usuario);
		for($i=0; $i<$longitud; $i++)
		{
			$relatorio_result = "";
			$relatorio_result = DB::table('cao_usuario')
				->select(
					DB::raw('MONTH(cao_fatura.data_emissao) as month'),
					DB::raw('YEAR(cao_fatura.data_emissao) as year'),
					'cao_usuario.co_usuario', 
					'cao_usuario.no_usuario',
					'cao_salario.brut_salario',
					'cao_fatura.comissao_cn',
					DB::raw('sum(cao_fatura.valor-((cao_fatura.valor*cao_fatura.total_imp_inc)/100)) as liquida')
					)
				->join('cao_os','cao_os.co_usuario','=','cao_usuario.co_usuario')
				->join('cao_fatura','cao_fatura.co_os','=','cao_os.co_os')
				->join('cao_salario','cao_salario.co_usuario','=','cao_usuario.co_usuario')
				->where([
					['cao_usuario.co_usuario', '=', $co_usuario[$i]],	
				])
				->whereBetween(DB::raw('YEAR(cao_fatura.data_emissao)'), array($anio_ini,$anio_fin))
				->whereBetween(DB::raw('MONTH(cao_fatura.data_emissao)'), array($mes_ini,$mes_fin))
				->groupBy(
					'year',
					'month',
					'cao_usuario.co_usuario', 
					'cao_usuario.no_usuario',
					'cao_salario.brut_salario',
					'cao_fatura.comissao_cn')
				->orderBy(
					'year',
					'month','desc')
				->get();
				$trs = "";
				
				if($relatorio_result->isEmpty()){
				
				}else{
					
					$primera_vuelta = True;
					
					$liquida = 0;
					$salario = 0;
					$comision = 0;
					$lucro = 0;
					$fecha = "";
					
					$fecha_en_recorrido = "";
					
					$numero = count($relatorio_result);
					
					$contador = 0;
					
					$total_liquida=0;
					$total_salario = 0;
					$total_comision = 0;
					$total_lucro = 0;
					
					foreach ($relatorio_result as $lista) {
						$contador = $contador + 1;
						switch($lista->month){
							case '1':
								$mes = 'Janeiro';
								break;
							case '2':
								$mes = 'Fevereiro';
								break;
							case '3':
								$mes = 'Março';
								break;
							case '4':
								$mes = 'Abril';
								break;
							case '5':
								$mes = 'Maio';
								break;
							case '6':
								$mes = 'Junho';
								break;
							case '7':
								$mes = 'Julho';
								break;
							case '8':
								$mes = 'Agosto';
								break;
							case '9':
								$mes = 'Setembro';
								break;
							case '10':
								$mes = 'Outubro';
								break;
							case '11':
								$mes = 'Novembro';
								break;
							case '12':
								$mes = 'Dezembro';
								break;
						}
						
						$fecha_en_recorrido = $mes.' de '.$lista->year;
						
						if($primera_vuelta){
							$fecha = $mes.' de '.$lista->year;
							$salario = $lista->brut_salario;
							$comision = "";
							$lucro = "";
							$liquida = "";	
							$primera_vuelta = False;
							$temp_comision = "";
							$temp_lucro = "";
						}
						
						if($fecha == $fecha_en_recorrido){
							$temp_comision = (((float)$liquida * (float)$lista->comissao_cn)/100);
							
							$liquida = (float)$liquida + (float)$lista->liquida;
							$comision = (float)$comision + (float)$temp_comision;
							
						}
						
						
						
						if(($fecha_en_recorrido <> $fecha) || ($numero==$contador)){
							$trs = $trs . '
							<tr>
								<td width="20%">'.$fecha.'</td>
								<td width="20%" class="text-right">R$ '.number_format((float)$liquida, 2, ',', '.').'</td>
								<td width="20%" class="text-right">R$ '.number_format((float)$salario, 2, ',', '.').'</td>
								<td width="20%" class="text-right">R$ '.number_format((float)$comision, 2, ',', '.').'</td>
								<td width="20%" class="text-right">R$ '.number_format((float)($salario+$comision), 2, ',', '.').'</td>
							</tr>
							';
							$primera_vuelta = True;
							$total_lucro = (float)$total_lucro + (float)($salario+$comision);
							$total_comision = (float)$total_comision + (float)$comision;
							$total_liquida = (float)$total_liquida + (float)$liquida;
							$total_salario = (float)$total_salario + (float)$salario;
						}
						
						
					}

					$table = $table . '
					<div class="table-responsive">
						<table class="table">
							<tr class="success">
								<td colspan="5">'.$relatorio_result[0]->no_usuario.'</td>
							</tr>
							<tbody>
								<th width="20%" class="text-center">Período</th>
								<th width="20%" class="text-center">Receita Líquida</th>
								<th width="20%" class="text-center">Custo Fixo</th>
								<th width="20%" class="text-center">Comissão</th>
								<th width="20%" class="text-center">Lucro</th>
							</tbody>
							'.$trs.'
							<tr>
								<td><b>SALDO</b></td>
								<td width="20%" class="text-right"><b>R$ '.number_format((float)$total_liquida, 2, ',', '.').'</b></td>
								<td width="20%" class="text-right"><b>R$ '.number_format((float)$total_salario, 2, ',', '.').'</b></td>
								<td width="20%" class="text-right"><b>R$ '.number_format((float)$total_comision, 2, ',', '.').'</b></td>
								<td width="20%" class="text-right"><b>R$ '.number_format((float)$total_lucro, 2, ',', '.').'</b></td>
							</tr>
						</table>
					</div>';
				}
		}
        return response()->json(['relatorio' => $table]);
	}
	
	public function grafico(Request $request){
		$mes_ini = (int)$request->input('mes_ini');
		$mes_fin = (int)$request->input('mes_fin');
		$anio_ini = (int)$request->input('anio_ini');
		$anio_fin = (int)$request->input('anio_fin');
		
		//seleccionados
		$seleccionados = $request->input('seleccionados');
		$co_usuario =  explode(",", $seleccionados);
		
		$parametro_get = '/'.$mes_ini.'/'.$anio_ini.'/'.$mes_fin.'/'.$anio_fin.'/'.$seleccionados;
		
		
		$objeo = '<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/
flash/swflash.cab#version=6,0,0,0" WIDTH="600" HEIGHT="350" id="FusionCharts">
<PARAM NAME=movie VALUE="charts/FC_2_3_MSColumnLine_DY_2D.swf">
<PARAM NAME="FlashVars" VALUE="&dataURL=xml'.$parametro_get.'&amp;chartWidth=600&amp;chartHeight=350">
<PARAM NAME=quality VALUE=high>
<PARAM NAME=bgcolor VALUE=#FFFFFF>
<EMBED src="charts/FC_2_3_MSColumnLine_DY_2D.swf" FlashVars="&dataURL=xml'.$parametro_get.'&amp;chartWidth=600&amp;chartHeight=350" quality=high bgcolor=#FFFFFF WIDTH="600" HEIGHT="350" NAME="FusionCharts" TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer"></EMBED>
</OBJECT>';
		return response()->json(['grafico' => $objeo]);
	}
	
	
	public function pizza(Request $request){
		$mes_ini = (int)$request->input('mes_ini');
		$mes_fin = (int)$request->input('mes_fin');
		$anio_ini = (int)$request->input('anio_ini');
		$anio_fin = (int)$request->input('anio_fin');
		
		//seleccionados
		$seleccionados = $request->input('seleccionados');
		$co_usuario =  explode(",", $seleccionados);
		
		$parametro_get = '/'.$mes_ini.'/'.$anio_ini.'/'.$mes_fin.'/'.$anio_fin.'/'.$seleccionados;
		
		
		$objeo = '<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/
flash/swflash.cab#version=6,0,0,0" WIDTH="600" HEIGHT="350" id="FusionCharts">
<PARAM NAME=movie VALUE="charts/FC_2_3_Pie3D.swf">
<PARAM NAME="FlashVars" VALUE="&dataURL=xml2'.$parametro_get.'&amp;chartWidth=600&amp;chartHeight=350">
<PARAM NAME=quality VALUE=high>
<PARAM NAME=bgcolor VALUE=#FFFFFF>
<EMBED src="charts/FC_2_3_Pie3D.swf" FlashVars="&dataURL=xml2'.$parametro_get.'&amp;chartWidth=600&amp;chartHeight=350" quality=high bgcolor=#FFFFFF WIDTH="600" HEIGHT="350" NAME="FusionCharts" TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer"></EMBED>
</OBJECT>';
		return response()->json(['grafico' => $objeo]);
	}
	
	
	
	
	public function xml($mesini, $anioini, $mesfin, $aniofin, $usuarios){
		$mes_ini_t = (int)$mesini;
		$mes_fin_t = (int)$anioini;
		$anio_ini_t = (int)$mesfin;
		$anio_fin_t = (int)$aniofin;
		
		
		
		//seleccionados
		$seleccionados_t = $usuarios;
		$co_usuario =  explode(",", $seleccionados_t);
		
		$longitud = count($co_usuario);
		
		echo '  <graph bgColor="F1f1f1" caption="Performance Comercial" subCaption="Janeiro de 2007 a Maio de 2007" showValues="0" divLineDecimalPrecision="2" formatNumberScale="2" limitsDecimalPrecision="2" PYAxisName="" SYAxisName="" decimalSeparator="," thousandSeparator="." SYAxisMaxValue="90000" PYAxisMaxValue="90000">

  <categories>
  ';
		
		$mes_result = DB::table('cao_usuario')
				->select(
					DB::raw('MONTH(cao_fatura.data_emissao) as month')
					)
				->join('cao_os','cao_os.co_usuario','=','cao_usuario.co_usuario')
				->join('cao_fatura','cao_fatura.co_os','=','cao_os.co_os')
				->whereIn('cao_usuario.co_usuario',$co_usuario)
				->whereBetween(DB::raw('YEAR(cao_fatura.data_emissao)'), array($anio_ini_t,$anio_fin_t))
				->whereBetween(DB::raw('MONTH(cao_fatura.data_emissao)'), array($mes_ini_t,$mes_fin_t))
				->groupBy(
					'month')
				->orderBy(DB::raw('month'),'asc')
				->get();
		
		foreach ($mes_result as $list) {
			
			switch($list->month){
				case '1':
					$mes = 'Janeiro';
					echo '
					<category name="Jan" hoverText="'.$mes.'" /> ';
					break;
				case '2':
					$mes = 'Fevereiro';
					echo '
					<category name="Fev" hoverText="'.$mes.'" /> ';
					break;
				case '3':
					$mes = 'Março';
					echo '
					<category name="Mar" hoverText="'.$mes.'" /> ';
					break;
				case '4':
					$mes = 'Abril';
					echo '
					<category name="Abr" hoverText="'.$mes.'" /> ';
					break;
				case '5':
					$mes = 'Maio';
					echo '
					<category name="Mai" hoverText="'.$mes.'" /> ';
					break;
				case '6':
					$mes = 'Junho';
					echo '
					<category name="Jun" hoverText="'.$mes.'" /> ';
					break;
				case '7':
					$mes = 'Julho';
					echo '
					<category name="Jul" hoverText="'.$mes.'" /> ';
					break;
				case '8':
					$mes = 'Agosto';
					echo '
					<category name="Ago" hoverText="'.$mes.'" /> ';
					break;
				case '9':
					$mes = 'Setembro';
					echo '
					<category name="Set" hoverText="'.$mes.'" /> ';
					break;
				case '10':
					$mes = 'Outubro';
					echo '
					<category name="Out" hoverText="'.$mes.'" /> ';
					break;
				case '11':
					$mes = 'Novembro';
					echo '
					<category name="Nov" hoverText="'.$mes.'" /> ';
					break;
				case '12':
					$mes = 'Dezembro';
					echo '
					<category name="Dez" hoverText="'.$mes.'" /> ';
					break;
			}
			$mes_lista_final = $list->month;
		}
  
	  
  echo '
  </categories>';
		
		$relatorio_result = DB::table('cao_usuario')
				->select(
					DB::raw('MONTH(cao_fatura.data_emissao) as month'),
					'cao_usuario.no_usuario',
					DB::raw('sum(cao_fatura.valor-((cao_fatura.valor*cao_fatura.total_imp_inc)/100)) as liquida')
					)
				->join('cao_os','cao_os.co_usuario','=','cao_usuario.co_usuario')
				->join('cao_fatura','cao_fatura.co_os','=','cao_os.co_os')
				->whereIn('cao_usuario.co_usuario',$co_usuario)
				->whereBetween(DB::raw('YEAR(cao_fatura.data_emissao)'), array($anio_ini_t,$anio_fin_t))
				->whereBetween(DB::raw('MONTH(cao_fatura.data_emissao)'), array($mes_ini_t,$mes_fin_t))
				->groupBy(
					'month',
					'cao_usuario.no_usuario')
				->orderBy(DB::raw('cao_usuario.no_usuario,month'),'asc')
				->get();
		
		
		$primera_vuelta = True;
		$nombre_temp="";
		$cierre = false;
		$numero = count($relatorio_result);
		$contador = 0;
		
		
		
		$mes_anterior = 0;
		
		foreach ($relatorio_result as $clave => $lista) {
			
			$contador = $contador + 1;
			$nombre = $lista->no_usuario;
			
			
			if($nombre==$nombre_temp){
				$c = (int)$lista->month - 1;
				if((int)$mes_anterior == (int)$c){
					echo '
					<set value="'.number_format((float)$lista->liquida, 2, '.', '').'" />';
					$mes_anterior = $lista->month;
					
					if(((int)$clave+1)==$numero){
						
					}else{
						if($relatorio_result[$clave+1]->no_usuario == $lista->no_usuario){
							
						}else{
							for($i=$lista->month;$i<$mes_lista_final;$i++){
							echo '
					<set value="0" />';
							}
						}
					}
					
					
				}else{
					$d = (int)$mes_anterior + 1;
					for($i=$d;$i<$lista->month;$i++){
						echo '
					<set value="0" />';
					}
					echo '
					<set value="'.number_format((float)$lista->liquida, 2, '.', '').'" />';
					$mes_anterior = $lista->month;
					
					if(((int)$clave+1)==$numero){
						
					}else{
						if($relatorio_result[$clave+1]->no_usuario == $lista->no_usuario){
							
						}else{
							for($i=$lista->month;$i<$mes_lista_final;$i++){
							echo '
					<set value="0" />';
							}
						}
					}
					
					
				}
				
			}else{
				if($cierre){
					echo '
					</dataset>';
				}
				$nombre_temp = $lista->no_usuario;
				$rand = dechex(rand(0x000000, 0xFFFFFF));
				echo '
				<dataset seriesName="'.$lista->no_usuario.'" color="#'.$rand.'" numberPrefix="R$ ">';
				if(($lista->month == '1')){
					echo '
					<set value="'.number_format((float)$lista->liquida, 2, '.', '').'" />';
					
					if(((int)$clave+1)==$numero){
						
					}else{
						if($relatorio_result[$clave+1]->no_usuario == $lista->no_usuario){
							
						}else{
							for($i=$lista->month;$i<$mes_lista_final;$i++){
							echo '
					<set value="0" />';
							}
						}
					}
					
				}else{
					for($i=$mes_ini_t;$i<$lista->month;$i++){
						echo '
					<set value="0" />';
					}
					echo '
					<set value="'.number_format((float)$lista->liquida, 2, '.', '').'" />';
					
					if(((int)$clave+1)==$numero){
						
					}else{
						if($relatorio_result[$clave+1]->no_usuario == $lista->no_usuario){
							
						}else{
							for($i=$lista->month;$i<$mes_lista_final;$i++){
							echo '
					<set value="0" />';
							}
						}
					}
				}
				
				$mes_anterior = $lista->month;
				$cierre = true;
			}
			if($contador==$numero){
				echo '
				</dataset>';
			}
			
			
		}
		
   
		$costo_fijo_medio = DB::table('cao_usuario')
				->select(
					DB::raw('AVG(cao_salario.brut_salario) as salario')
					)
				->join('cao_os','cao_os.co_usuario','=','cao_usuario.co_usuario')
				->join('cao_fatura','cao_fatura.co_os','=','cao_os.co_os')
				->join('cao_salario','cao_salario.co_usuario','=','cao_usuario.co_usuario')
				->whereIn('cao_usuario.co_usuario',$co_usuario)
				->whereBetween(DB::raw('YEAR(cao_fatura.data_emissao)'), array($anio_ini_t,$anio_fin_t))
				->whereBetween(DB::raw('MONTH(cao_fatura.data_emissao)'), array($mes_ini_t,$mes_fin_t))
				->get();
		
		echo '
		<dataset lineThickness="3" seriesName="Custo Fixo Médio" numberPrefix="R$ " parentYAxis="S" color="FF0000" anchorBorderColor="FF8000">';
		foreach ($mes_result as $list) {
			echo '
			<set value="'.$costo_fijo_medio[0]->salario.'" /> ';
		}
  		echo '
		</dataset>
	</graph>'; 
	}
	
	public function xml2($mesini, $anioini, $mesfin, $aniofin, $usuarios){
		$mes_ini_t = (int)$mesini;
		$mes_fin_t = (int)$anioini;
		$anio_ini_t = (int)$mesfin;
		$anio_fin_t = (int)$aniofin;
		
		
		
		//seleccionados
		$seleccionados_t = $usuarios;
		$co_usuario =  explode(",", $seleccionados_t);
		
		$longitud = count($co_usuario);
		
		$pizza_result = DB::table('cao_usuario')
				->select(
					'cao_usuario.no_usuario',
					DB::raw('sum(cao_fatura.valor-((cao_fatura.valor*cao_fatura.total_imp_inc)/100)) as liquida')
					)
				->join('cao_os','cao_os.co_usuario','=','cao_usuario.co_usuario')
				->join('cao_fatura','cao_fatura.co_os','=','cao_os.co_os')
				->whereIn('cao_usuario.co_usuario',$co_usuario)
				->whereBetween(DB::raw('YEAR(cao_fatura.data_emissao)'), array($anio_ini_t,$anio_fin_t))
				->whereBetween(DB::raw('MONTH(cao_fatura.data_emissao)'), array($mes_ini_t,$mes_fin_t))
				->groupBy(
					'cao_usuario.no_usuario')
				->orderBy(DB::raw('cao_usuario.no_usuario'),'asc')
				->get();
		
		  echo '<graph caption="Participação na Receita" bgColor="F1f1f1" decimalPrecision="1" showPercentageValues="1" showNames="1" numberPrefix="" showValues="1" showPercentageInLabel="1" pieYScale="45" pieBorderAlpha="40" pieFillAlpha="70" pieSliceDepth="15" pieRadius="100">';
			
			$total = 0;
  			foreach($pizza_result as $clave => $lista){
				$total = $total + $lista->liquida;
			}
			
			foreach($pizza_result as $clave => $lista){
				$porcentaje = ((int)$lista->liquida*100)/(int)$total;
				$rand = dechex(rand(0x000000, 0xFFFFFF));
				echo '<set value="'.(int)$porcentaje.'" name="'.$lista->no_usuario.'" color="'.$rand.'" />';
			}
		 

  		echo '</graph>';
	}
	
}
