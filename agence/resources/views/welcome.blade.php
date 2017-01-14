@extends('app')

@section('seccion_lib_style')
{!! Html::style('bower_components/bootstrap-select/dist/css/bootstrap-select.min.css') !!}
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
									<form>
										<div class="form-group">
											<label for="consultores">Períodos</label>
											<div class="row">
												<div class="col-xs-2">
													<select class="form-control">
													  <option>Jan</option>
													  <option>Fev</option>
													  <option>Mar</option>
													  <option>Abr</option>
													  <option>Mai</option>
													  <option>Jun</option>
													  <option>Jul</option>
													  <option>Ago</option>
													  <option>Sep</option>
													  <option>Out</option>
													  <option>Nov</option>
													  <option>Dez</option>
													</select>
												</div>
												<div class="col-xs-1">
													 /
												</div>
												<div class="col-xs-2"> 
													<select class="form-control">
													  <option>2003</option>
													  <option>2004</option>
													  <option>2005</option>
													  <option>2006</option>
													  <option>2007</option>
													</select> 
												</div>
												<div class="col-xs-1">
													 a
												</div>
												<div class="col-xs-2">
													<select class="form-control">
													  <option>Jan</option>
													  <option>Fev</option>
													  <option>Mar</option>
													  <option>Abr</option>
													  <option>Mai</option>
													  <option>Jun</option>
													  <option>Jul</option>
													  <option>Ago</option>
													  <option>Sep</option>
													  <option>Out</option>
													  <option>Nov</option>
													  <option>Dez</option>
													</select>
												</div>
												<div class="col-xs-1">
													 /
												</div>
												<div class="col-xs-2"> 
													<select class="form-control">
													  <option>2003</option>
													  <option>2004</option>
													  <option>2005</option>
													  <option>2006</option>
													  <option>2007</option>
													</select>
												</div>
											</div>
										</div>
										<div class="form-group">
											
											<div class="row">
												<div class="col-xs-10">
													<label for="consultores">Consultores Disponibles</label>
													<select name="from[]" class="multiselect form-control" size="8" multiple="multiple" data-right="#multiselect_to_1" data-right-all="#right_All_1" data-right-selected="#right_Selected_1" data-left-all="#left_All_1" data-left-selected="#left_Selected_1">
														<option value="1">Item 1</option>
														<option value="2">Item 5</option>
														<option value="2">Item 2</option>
														<option value="2">Item 4</option>
														<option value="3">Item 3</option>
													</select>
												</div>

												<div class="col-xs-2">
													<button type="button" id="right_All_1" class="btn btn-block"><i class="glyphicon glyphicon-forward"></i></button>
													<button type="button" id="right_Selected_1" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
													<button type="button" id="left_Selected_1" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
													<button type="button" id="left_All_1" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
												</div>

												<div class="col-xs-10">
													<label for="consultores">Consultores Seleccionados</label>
													<select name="to[]" id="multiselect_to_1" class="form-control" size="8" multiple="multiple"></select>
												</div>
											</div>
										</div>
										<div class="form-group">
											<button type="button" class="btn btn-warning">Relatorío</button>
											<button type="button" class="btn btn-success">Gráfico</button>
											<button type="button" class="btn btn-info">Pizza</button>
										</div>
									</form>
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
{!! Html::script('bower_components/multiselect/dist/js/multiselect.min.js') !!}
@endsection

@section('seccion_script')
jQuery(document).ready(function($) {
    $('.multiselect').multiselect();
});
@endsection

