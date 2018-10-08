<!DOCTYPE html>
<html>
	<head>
		<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<link href="{{ URL::asset('fullcalendar/fullcalendar.css') }}" rel="stylesheet"></script>
		<link href="{{ URL::asset('css/principal.css') }}" rel="stylesheet"></script>
	</head>
	<body>

	<div class="container">
		<div class="row">
			<h2>Aqui va el titulo</h2>
		</div>
		<div class="row row-offcanvas row-offcanvas-right" id="calendario">
			 <div class="col-12 col-md-9">
			 	<div id="calendar">
			 	</div>
			 </div>
			 <div class="col-6 col-md-3 sidebar-offcanvas" id="sidebar">
			 	aqui va un lindo menu con opciones
			 </div>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script src="{{ URL::asset('fullcalendar/lib/moment.min.js') }}"></script>
	<script src="{{ URL::asset('fullcalendar/fullcalendar.js') }}"></script>
	<script src="{{ URL::asset('fullcalendar/locale/es.js') }}"></script>
	<script src="{{ URL::asset('js/inicio.js') }}"></script>
	</body>
</html>