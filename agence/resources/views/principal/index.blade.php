@extends('app')

@section('seccion_lib_style')
{!! Html::style('bower_components/lou-multi-select/css/multi-select.css') !!}
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12">
   			<div class="card">
				<ul class="nav nav-tabs" role="tablist">
					<li class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Por Consultor</a></li>
					<li ><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Por Cliente</a></li>
				</ul>

				<!-- Tab panes -->
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="home">
						<div class="container-fluid">
							<div class="row">
								<div class="col-xs-12">
									<form class="form-inline">
										<div class="form-group">
											<label for="consultores">Períodos</label>
											<select id="mes_ini" class="form-control">
											  <option value="1">Jan</option>
											  <option value="2">Fev</option>
											  <option value="3">Mar</option>
											  <option value="4">Abr</option>
											  <option value="5">Mai</option>
											  <option value="6">Jun</option>
											  <option value="7">Jul</option>
											  <option value="8">Ago</option>
											  <option value="9">Sep</option>
											  <option value="10">Out</option>
											  <option value="11">Nov</option>
											  <option value="12">Dez</option>
											</select> / 
											<select id="anio_ini" class="form-control">
											  <option value="2003">2003</option>
											  <option value="2004">2004</option>
											  <option value="2005">2005</option>
											  <option value="2006">2006</option>
											  <option value="2007">2007</option>
											</select>
											a 
											<select id="mes_fin" class="form-control">
											  <option value="1">Jan</option>
											  <option value="2">Fev</option>
											  <option value="3">Mar</option>
											  <option value="4">Abr</option>
											  <option value="5">Mai</option>
											  <option value="6">Jun</option>
											  <option value="7">Jul</option>
											  <option value="8">Ago</option>
											  <option value="9">Sep</option>
											  <option value="10">Out</option>
											  <option value="11">Nov</option>
											  <option value="12">Dez</option>
											</select> / 
											<select id="anio_fin" class="form-control">
											  <option value="2003">2003</option>
											  <option value="2004">2004</option>
											  <option value="2005">2005</option>
											  <option value="2006">2006</option>
											  <option value="2007">2007</option>
											</select> 
										</div>
									</form>
									<form>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-10">
													<label for="consultores">Consultores Disponibles</label>
													<select multiple="multiple" id="seleccionados" name="seleccionados[]" class="form-control">
														@foreach ($users as $key => $user)
															<option value="{{ $user->co_usuario }}">{{ $user->no_usuario }}</option>
														@endforeach
													</select>
												</div>
											</div>
										</div>
										<div class="form-group">
											<button type="button" id="relatorio" class="btn btn-warning">Relatorío</button>
											<button type="button" id="grafico" class="btn btn-success">Gráfico</button>
											<button type="button" id="pizza" class="btn btn-info">Pizza</button>
										</div>
									</form>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12" id="resultados">
									
								</div>
							</div>
						</div>               	
         			</div>
                                        
					<div role="tabpanel" class="tab-pane" id="profile">
						

					</div>
					
				</div>
			</div>
  		</div>
	</div>
</div>
@endsection

@section('seccion_lib_script')
{!! Html::script('bower_components/lou-multi-select/js/jquery.multi-select.js') !!}
@endsection

@section('seccion_script')
		$( "#relatorio" ).click(function() {
			mes_ini = $("#mes_ini").val();
			mes_fin = $("#mes_fin").val();
			anio_ini = $("#anio_ini").val();
			anio_fin = $("#anio_fin").val();
			
			var selected = '';
			$('#seleccionados option:checked').each(function(){
				selected += $(this).val() + ','; 
			});
			fin = selected.length - 1; 
			selected = selected.substr( 0, fin );
			
			$.get( "/relatorio", { 'seleccionados': selected,'mes_ini':mes_ini,'mes_fin':mes_fin,'anio_ini':anio_ini,'anio_fin':anio_fin } )
				.done(function( data ) {
				$("#resultados").html(data['relatorio']);
			});
			
		});
		
		$( "#grafico" ).click(function() {
		  
			mes_ini = $("#mes_ini").val();
			mes_fin = $("#mes_fin").val();
			anio_ini = $("#anio_ini").val();
			anio_fin = $("#anio_fin").val();
			
			var selected = '';
			$('#seleccionados option:checked').each(function(){
				selected += $(this).val() + ','; 
			});
			fin = selected.length - 1; 
			selected = selected.substr( 0, fin );
			
			$.get( "/grafico", { 'seleccionados': selected,'mes_ini':mes_ini,'mes_fin':mes_fin,'anio_ini':anio_ini,'anio_fin':anio_fin } )
				.done(function( data ) {
				$("#resultados").html(data['grafico']);
			});

		});
		
		$( "#pizza" ).click(function() {
		  	mes_ini = $("#mes_ini").val();
			mes_fin = $("#mes_fin").val();
			anio_ini = $("#anio_ini").val();
			anio_fin = $("#anio_fin").val();
			
			var selected = '';
			$('#seleccionados option:checked').each(function(){
				selected += $(this).val() + ','; 
			});
			fin = selected.length - 1; 
			selected = selected.substr( 0, fin );
			
			$.get( "/pizza", { 'seleccionados': selected,'mes_ini':mes_ini,'mes_fin':mes_fin,'anio_ini':anio_ini,'anio_fin':anio_fin } )
				.done(function( data ) {
				$("#resultados").html(data['grafico']);
			});
		});

		$(document).ready(function($) {
			$('#seleccionados').multiSelect()
		});
@endsection

