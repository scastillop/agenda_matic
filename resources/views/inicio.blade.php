<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
		<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
		<link href="{{ URL::asset('fullcalendar/fullcalendar.css') }}" rel="stylesheet"></script>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
		<link href="{{ URL::asset('bootstrap-datepicker/css/bootstrap-datepicker.css') }}" rel="stylesheet"></script>
		<link href="{{ URL::asset('css/principal.css') }}" rel="stylesheet"></script>
	</head>
	<body data-id='{{ Auth::id() }}' >

	<div class="container">
		<div class="row" id="div_titulo">
			<h2>AgendaMatic</h2>

			<ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    <li class="nav-item">
                        @if (Route::has('register'))
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        @endif
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
		</div>
		<div class="row row-offcanvas row-offcanvas-right" id="div_calendar_sidebar">
			 <div class="col-12 col-lg-9" id="div_calendar">
			 	<div id="calendar">
			 	</div>
			 </div>
			 <div class="col-12 col-lg-3 sidebar-offcanvas" id="sidebar">
			 	<h2>Opciones</h2>
			 	<div id="div_opciones">
			 		<button type="button" class="btn btn-default boton_opciones" id="agendar">Agendar Reunion</button>
			 		<br>
			 		<button type="button" class="btn btn-default boton_opciones" id="bloquear">Bloquear Fecha</button>
			 	</div>
			 </div>
		</div>
	</div>

	<div class="modal" tabindex="-1" role="dialog" id="modal_agendar">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title" id="modal_agendar_titulo_modal">Agendar reunion</h2>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row m-1">
						<label for="modal_agendar_titulo" class="text-nowrap m-0">Titulo de la reunion</label>
						 <input type="text" class="input-sm form-control input-chico modal_agendar_form pt-0 pb-0" id="modal_agendar_titulo"/>
						 <hr>
						<div class='col-6 p-0'>
					        <div class="form-group m-0">
								<label for="datetimepicker7" class="text-nowrap m-0">Fecha y hora de inicio</label>
					           	<div class="input-group date" id="datetimepicker7" data-target-input="nearest">
					                <input type="text" class="form-control datetimepicker-input input-chico modal_agendar_rango modal_agendar_form" data-target="#datetimepicker7" id="modal_agendar_inicio" />
					                <div class="input-group-append" data-target="#datetimepicker7" data-toggle="datetimepicker">
					                    <div class="input-group-text input-chico rounded-0"><i class="far fa-calendar-alt"></i></div>
					                </div>
					            </div>
					        </div>
					    </div>
					    <div class='col-6 p-0'>
					        <div class="form-group m-0">
					        	<label for="datetimepicker8" class="text-nowrap m-0">Fecha y hora de termino</label>
					           	<div class="input-group date" id="datetimepicker8" data-target-input="nearest">
					                <input type="text" class="form-control datetimepicker-input input-chico rounded-0 modal_agendar_rango modal_agendar_form" data-target="#datetimepicker8" id="modal_agendar_termino"/>
					                <div class="input-group-append" data-target="#datetimepicker8" data-toggle="datetimepicker">
					                    <div class="input-group-text input-chico"><i class="far fa-calendar-alt"></i></div>
					                </div>
					            </div>
					        </div>
					    </div>
						<div class="form-group col-12 mt-2 mb-1 pr-0 pl-0" id="modal_agendar_recomendados_div">
							<label for="modal_agendar_ubicacion" class="col-6 text-nowrap m-0 p-0">Horarios recomendados</label>
						    <table class="table-bordered w-100 mt-1" id="modal_agendar_recomendados">
						    	<!--
						    	<thead>
						    		<tr>
						    			<td>Fecha y hora de inicio</td>
						    			<td>Fecha y hora de termino</td>
						    			<td>Seleccionar</td>
						    		</tr>
						    	</thead>
						    	-->
								<tr>
									<td class="pt-0 pl-3 pr-3 pb-0">24/10/2018 17:01</td>
									<td class="pt-0 pl-3 pr-3 pb-0">24/10/2018 17:01</td>
									<td class="pt-0 pl-3 pr-3 pb-0"><i class="far fa-check-circle modal_agendar_recomendados_seleccionar"></i></td>
								</tr>
								<tr>
									<td class="pt-0 pl-3 pr-3 pb-0">24/10/2018 17:01</td>
									<td class="pt-0 pl-3 pr-3 pb-0">24/10/2018 17:01</td>
									<td class="pt-0 pl-3 pr-3 pb-0"><i class="far fa-check-circle modal_agendar_recomendados_seleccionar"></i></td>
								</tr>
							</table>
						</div>

						<div class="form-group col-6 mt-2 mb-1 pr-2 pl-0">
							<label for="modal_agendar_ubicacion" class="col-6 text-nowrap m-0 p-0">Ubicacion</label>
						    <select class="input-sm form-control input-chico modal_agendar_fecha modal_agendar_form pt-0 pb-0" id="modal_agendar_ubicacion">
						    </select>
						</div>
						<div class="form-group col-3 p-0 mt-2 mb-1 pr-0 pl-1">
							<label for="modal_agendar_rechazable" class="col-6 text-nowrap m-0 p-0">Rechazable</label>
							<div class="form-check">
								<input type="checkbox" class="form-check-input modal_agendar_form" id="modal_agendar_rechazable">
							</div>
						</div>
						<div class="form-group col-3 p-0 mt-2 mb-1 pr-0 pl-1">
							<label for="modal_agendar_todo_el_dia" class="col-6 text-nowrap m-0 p-0">Todo el dia</label>
							<div class="form-check">
								<input type="checkbox" class="form-check-input modal_agendar_form" id="modal_agendar_todo_el_dia">
							</div>
						</div>
						<div class="form-group col-12 p-0 mt-1 mb-1 pr-0 pl-0">
							<label for="modal_agendar_detalles" class="text-nowrap m-0">Detalles de la reunion</label>
						 	<textarea class="form-control modal_agendar_form" id="modal_agendar_detalles" rows="2"></textarea>
						</div>
						<div class="dual_list col-12 p-0 m-0 row">
							<div class="form-group col-6 pr-2 pl-0 mt-1">
							    <label for="modal_agendar_usuarios" class="m-0 p-0">Usuarios disponibles</label>
							    <select multiple class="form-control select_origen dual_list_select modal_agendar_fecha modal_agendar_form" id="modal_agendar_usuarios">
									<option>1</option>
									<option class="rojo">2</option>
									<option>3</option>
									<option>4</option>
									<option>5</option>
							    </select>
						  	</div>
						  	<div class="form-group col-6 pr-0 pl-1 mt-1">
							    <label for="modal_agendar_invitados" class="m-0 p-0">Invitados</label>
							    <select multiple class="form-control select_destino dual_list_select modal_agendar_fecha modal_agendar_form" id="modal_agendar_invitados">
									<option>A</option>
									<option>B</option>
									<option>C</option>
									<option>D</option>
									<option>E</option>
							    </select>
						  	</div>
					  	</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" id="modal_agendar_aceptar">Agendar reunion</button>
					<button type="button" class="btn btn-default" data-dismiss="modal" id="modal_agendar_cancelar">Cancelar</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_ocupados" tabindex="10" role="dialog" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h2 class="modal-title">Atención!</h2>
	      </div>
	      <div class="modal-body">
	        Esta intentando agendar una reunion en la que hay invitados comprometidos previamente con otras reuniones.
	        <br>
	        <br>
	        Si lo desea puede agendar igualmente la reunion o solicitar la busqueda del horario mas cercano en el que los invitados no esten comprometidos. 
	      </div>
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-success" id="modal_ocupados_aceptar">Agendar igualmente</button>
	        <button type="button" class="btn btn-default" id="modal_ocupados_buscar">Ver otro horario</button>
	      </div>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="modal_exito" tabindex="10" role="dialog" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h2 class="modal-title">Éxito!</h2>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        La reunión ha sido registrada satisfactoriamente.
	      </div>
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-success" data-dismiss="modal" id="modal_exito_volver">Volver</button>
	      </div>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="modal_exito_cancel" tabindex="10" role="dialog" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h2 class="modal-title">Éxito!</h2>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        La reunión ha sido cancelada satisfactoriamente.
	      </div>
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-success" data-dismiss="modal" id="modal_exito_volver">Volver</button>
	      </div>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="modal_cancelar_exito" tabindex="10" role="dialog" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h2 class="modal-title">Éxito!</h2>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        La reunión ha sido rechazada satisfactoriamente.
	      </div>
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-success" data-dismiss="modal" id="modal_exito_volver">Volver</button>
	      </div>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="modal_cancelar" tabindex="10" role="dialog" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h2 class="modal-title">Atención!</h2>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body text-center" id="modal_cancelar_body">
	      </div>
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-success" data-dismiss="modal" id="modal_cancelar_cancelar">Cancelar asistencia</button>
	      	<button type="button" class="btn btn-default" data-dismiss="modal" id="modal_cancelar_volver">Volver</button>
	      </div>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="modal_eliminar" tabindex="10" role="dialog" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h2 class="modal-title">Atención!</h2>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body text-center" id="modal_eliminar_body">
	      </div>
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-success" data-dismiss="modal" id="modal_eliminar_cancelar">Eliminar reunión</button>
	      	<button type="button" class="btn btn-default" data-dismiss="modal" id="modal_eliminar_volver">Volver</button>
	      </div>
	    </div>
	  </div>
	</div>

	<div class="modal" tabindex="-1" role="dialog" id="modal_bloquear">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title" id="modal_bloquear_titulo_modal">Bloquear fechas</h2>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row m-1">
						<label for="modal_bloquear_titulo" class="text-nowrap m-0">Motivo del bloqueo</label>
						 <input type="text" class="input-sm form-control input-chico modal_bloquear_form pt-0 pb-0" id="modal_bloquear_titulo"/>
						 <hr>
						<div class='col-6 p-0'>
					        <div class="form-group m-0">
								<label for="datetimepicker5" class="text-nowrap m-0">Fecha y hora de inicio</label>
					           	<div class="input-group date" id="datetimepicker5" data-target-input="nearest">
					                <input type="text" class="form-control datetimepicker-input input-chico modal_bloquear_rango modal_bloquear_form" data-target="#datetimepicker5" id="modal_bloquear_inicio" />
					                <div class="input-group-append" data-target="#datetimepicker5" data-toggle="datetimepicker">
					                    <div class="input-group-text input-chico rounded-0"><i class="far fa-calendar-alt"></i></div>
					                </div>
					            </div>
					        </div>
					    </div>
					    <div class='col-6 p-0'>
					        <div class="form-group m-0">
					        	<label for="datetimepicker6" class="text-nowrap m-0">Fecha y hora de termino</label>
					           	<div class="input-group date" id="datetimepicker6" data-target-input="nearest">
					                <input type="text" class="form-control datetimepicker-input input-chico rounded-0 modal_bloquear_rango modal_bloquear_form" data-target="#datetimepicker6" id="modal_bloquear_termino"/>
					                <div class="input-group-append" data-target="#datetimepicker6" data-toggle="datetimepicker">
					                    <div class="input-group-text input-chico"><i class="far fa-calendar-alt"></i></div>
					                </div>
					            </div>
					        </div>
					    </div>
						
						<div class="form-check col-12 p-0 mt-3 mb-2 pl-4">
							<input type="checkbox" class="form-check-input modal_bloquear_form" id="modal_bloquear_todo_el_dia">
							<label class="form-check-label" for="modal_bloquear_todo_el_dia">
								Todo el dia
							</label>
						</div>

						<div class="form-group col-12 p-0 mt-1 mb-1 pr-0 pl-0">
							<label for="modal_bloquear_detalles" class="text-nowrap m-0">Detalles del bloqueo</label>
						 	<textarea class="form-control modal_bloquear_form" id="modal_bloquear_detalles" rows="2"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" id="modal_bloquear_aceptar">Bloquear</button>
					<button type="button" class="btn btn-default" data-dismiss="modal" id="modal_bloquear_cancelar">Cancelar</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_bloquear_atencion" tabindex="10" role="dialog" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h2 class="modal-title">Atención</h2>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body text-center" id="modal_bloquear_body">
	      	Se encontraron reuniones en el rango seleccionado.
	      	Para poder bloquear no debe tener reuniones pendiente en el rango seleccionado.
	      </div>
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-default" data-dismiss="modal" id="modal_bloquear_volver">Volver</button>
	      </div>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="modal_bloquear_exito" tabindex="10" role="dialog" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h2 class="modal-title">Éxito!</h2>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        Los dias seleccionados han sido bloqueados satisfactoriamente.
	      </div>
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-success" data-dismiss="modal" id="modal_exito_volver">Volver</button>
	      </div>
	    </div>
	  </div>
	</div>

	<div class="modal" tabindex="-1" role="dialog" id="modal_ver">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title" id="modal_ver_titulo_modal">Ver evento</h2>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row m-1">
						<div class="col-12 p-0 m-0">
							<label for="modal_ver_titulo" class="text-nowrap m-0"><strong>Titulo del evento</strong></label>
						</div>
						<div class="col-12 p-0 mb-2">
							<div class="modal_ver_form pt-0 pb-0" id="modal_ver_titulo">Este es el titulo de la reunion</div>
						</div>
						<div class='col-6 p-0'>
							<label for="modal_ver_inicio" class="text-nowrap m-0"><strong>Fecha y hora de inicio</strong></label>
					        <div class="modal_ver_rango modal_ver_form" id="modal_ver_inicio">15/15/15 15:15</div>
					    </div>
					    <div class='col-6 p-0'>
					      	<label for="modal_ver_termino" class="text-nowrap m-0"><strong>Fecha y hora de termino</strong></label>
					        <div class="modal_ver_rango modal_ver_form" id="modal_ver_termino">15/15/15 15:15</div>
					    </div>
						<div class="col-12 mt-2 mb-1 pr-2 pl-0">
							<label for="modal_ver_ubicacion" class="m-0 p-0"><strong>Ubicacion</strong></label>
						    <div class="modal_ver_fecha modal_ver_form pt-0 pb-0" id="modal_ver_ubicacion">Sala algo</div>
						</div>
						<div class="col-6 p-0 mt-2 mb-1 pr-0 pl-1">
							<div class="m-0 p-0"><strong>EL evento es rechazable</strong></div>
							 <div class="modal_ver_form" id="modal_ver_rechazable">Si</div>
						</div>
						<div class="col-6 p-0 mt-2 mb-1 pr-0 pl-1">
							<div class="m-0 p-0"><strong>El evento abarca todo el dia</strong></div>
							<div class="modal_ver_form" id="modal_ver_todo_el_dia">No</div>
						</div>
						<div class="col-12 p-0 mt-1 mb-3 pr-0 pl-0">
							<label for="modal_ver_detalles" class="m-0"><strong>Detalles del evento</strong></label>
						 	<div class="modal_ver_form" id="modal_ver_detalles">Estos son unos lindos detalles que tiene esta reunion</div>
						</div>
						<div class="col-12 p-0 m-0">
							<label for="modal_ver_invitados" class="m-0 p-0"><strong>Invitados</strong></label>
							<div class="modal_ver_fecha modal_ver_form" id="modal_ver_invitados">inviitado1<br>invitado2<br>invitado3</div>
					  	</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" data-dismiss="modal" id="modal_ver_aceptar">Volver</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal" tabindex="-1" role="dialog" id="modal_asistencia">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title" id="modal_asistencia_titulo_modal">Registrar Asistencia</h2>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row m-1">
						<div class="col-12 p-0 m-0">
							<table class="table-bordered" id="tabla_asistencia">
								<thead>
									<tr>
										<th>Asistió</th>
										<th>Invitado</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="text-right">
											<div class="checkbox checkbox-single">
							                    <input type="checkbox">
							                    <label></label>
							                </div>
										</td>
										<td>Usuario 1</td>
									</tr>
									<tr>
										<td class="text-right">
											<div class="checkbox checkbox-single">
							                    <input type="checkbox">
							                    <label></label>
							                </div>
										</td>
										<td>Usuario 2</td>
									</tr>
									<tr>
										<td class="text-right">
											<div class="checkbox checkbox-single">
							                    <input type="checkbox">
							                    <label></label>
							                </div>
										</td>
										<td>Usuario 3</td>
									</tr>
								</tbody>
							</table>

					  	</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" id="modal_asistencia_aceptar">Guardar cambios</button>
					<button type="button" class="btn btn-default" data-dismiss="modal" id="modal_asistencia_cancelar">Cancelar</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_asistencia_exito" tabindex="10" role="dialog" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h2 class="modal-title">Éxito!</h2>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        La asistencia ha sido registrada satisfactoriamente.
	      </div>
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-success" data-dismiss="modal" id="modal_exito_volver">Volver</button>
	      </div>
	    </div>
	  </div>
	</div>

	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script src="https://unpkg.com/popper.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script src="{{ URL::asset('fullcalendar/lib/moment.min.js') }}"></script>
	<script src="{{ URL::asset('fullcalendar/fullcalendar.js') }}"></script>
	<script src="{{ URL::asset('fullcalendar/locale/es.js') }}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/locale/es.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
	<script src="{{ URL::asset('bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
	<script src="{{ URL::asset('bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
	<script src="{{ URL::asset('js/calendar.js') }}"></script>
	</body>
</html>