
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Laravel</title>

	{!! Html::style('bower_components/bootstrap/dist/css/bootstrap.min.css') !!}
	{!! Html::style('bower_components/bootstrap-material-design/dist/css/material.min.css') !!}
	{!! Html::style('bower_components/bootstrap-material-design/dist/css/material.min.css') !!}
	{!! Html::style('bower_components/bootstrap-material-design/dist/css/ripples.min.css') !!}
	
	
	
	@yield('seccion_lib_style')
	<!-- Fonts -->
	

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#"><img src="images/logo2.png"></a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/') }}">Agence</a></li>
					<li><a href="{{ url('/') }}">Projetos</a></li>
					<li><a href="{{ url('/') }}">Administrativo</a></li>
					<li><a href="{{ url('/') }}">Comercial</a></li>
					<li><a href="{{ url('/') }}">Financeiro</a></li>
					<li><a href="{{ url('/') }}">Usuário</a></li>
				</ul>

				<ul class="nav navbar-nav navbar-right">
					<li><a href="{{ url('/auth/login') }}">Salir</a></li>
				</ul>
			</div>
		</div>
	</nav>

	@yield('content')

	<!-- Scripts -->
	{!! Html::script('bower_components/jquery/dist/jquery.min.js') !!}
	{!! Html::script('bower_components/bootstrap/dist/js/bootstrap.min.js') !!}
	{!! Html::script('bower_components/bootstrap-material-design/dist/js/ripples.min.js') !!}
	{!! Html::script('bower_components/bootstrap-material-design/dist/js/material.min.js') !!}
	
	@yield('seccion_lib_script')
	
	<script type="text/javascript">
		$(document).on('ready',function(){
			$.material.init();
			
			
		});
		@yield('seccion_script')
	</script>
	
	
	
	
</body>
</html>