$( document ).ready(function() {
	$('#calendar').fullCalendar({
		eventLimit: true,
		timeFormat: 'HH:mm',
		eventClick: function (data, event, view) {
			var currentLocation = window.location;
			//console.log(data);
			$('.popover').popover('hide');
			var ver = '<div class="rounded opciones_reunion p-1 pr-2 pl-2 ver" title="ver evento"><i class="fas fa-eye"></i></div>';
			var editar = "";
			var cancelar = "";
			var eliminar = "";
			var asistencia = "";
			if(moment().isBefore(data.start.format("YYYY/MM/DD HH:mm"))){
				editar = '<div class="rounded opciones_reunion p-1 pr-2 pl-2 editar" ver" title="editar evento"><i class="fas fa-pencil-alt"></i></div>';
				eliminar = '<div class="rounded opciones_reunion p-1 pr-2 pl-2 eliminar" ver" title="eliminar evento"><i class="fas fa-trash-alt"></i></div>';
				if(data.rechazable){
					cancelar = '<div class="rounded opciones_reunion p-1 pr-2 pl-2 cancelar" ver" title="cancelar asistencia"><i class="fas fa-ban"></i></div>';	
				}
			}else{
				asistencia = '<div class="rounded opciones_reunion p-1 pr-2 pl-2 asistencia" ver" title="registrar asistencia"><i class="fas fa-clipboard-list"></i></div>';
			}

			var esto= this;
            $(this).popover({
            	html: true,
            	content:'<div class="row  m-0 p-0">'+ver+editar+cancelar+eliminar+asistencia+'</div>',
            	trigger:"manual"
            })
            $(this).popover("show");
            $( ".popover-body" ).mouseleave(function() {
				 $('.popover').popover('hide');
			});
			$('.editar').click(function(){
				$('#modal_agendar_recomendados_div').hide();
				$('.popover').popover('hide');
			 	$('#modal_agendar_inicio').val(data.start.format("DD/MM/YYYY HH:mm"));
			 	$('#modal_agendar_termino').val(data.end.format("DD/MM/YYYY HH:mm"));
			 	$("#modal_agendar_aceptar" ).prop("disabled", true);
			 	$( ".modal_agendar_fecha" ).prop("disabled", true);
			 	$( ".modal_agendar_fecha" ).empty();
			 	$( '#modal_agendar_titulo' ).val(data.title);
			 	$( '#modal_agendar_detalles').val(data.detalles);
			 	if(data.allDay){
			 		$( '#modal_agendar_todo_el_dia' ).prop( "checked", true);
			 	}else{
			 		$( '#modal_agendar_todo_el_dia' ).prop( "checked", false);
			 	}
			 	if(data.rechazable){
			 		$( '#modal_agendar_rechazable' ).prop( "checked", true );
			 	}else{
			 		$( '#modal_agendar_rechazable' ).prop( "checked", false );
			 	}
			 	
			 	$('#modal_agendar_aceptar').data('id_schedule', data.id);
			 	$('#modal_agendar_aceptar').text('Guardar cambios');
			 	$('#modal_agendar_titulo_modal').text('Editar reunión');
			 	$('#modal_agendar').modal();
			 });
			$('.cancelar').click(function(){
				$('.popover').popover('hide');
				$('#modal_cancelar_body').html('Realmente desea cancelar su asistencia a la reunión "<strong>'+data.title+'</strong>" del dia '+data.start.format("DD-MM-YYYY")+' a las '+data.start.format("HH:mm")+'?');
			 	$('#modal_cancelar').modal();
			});

			$('.eliminar').click(function(){
				$('.popover').popover('hide');
				$('#modal_eliminar_body').html('Realmente desea eliminar la reunión "<strong>'+data.title+'</strong>" del dia '+data.start.format("DD-MM-YYYY")+' a las '+data.start.format("HH:mm")+'?');
			 	$('#modal_eliminar').modal();
			 	$('#modal_eliminar_cancelar').click(function(){
			 		$.ajax({
					url:currentLocation+'schedules/cancelById',
			   		type:'POST',
			   		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			   		data:{id: data.id},
			   		success:function(success){
			   			if(success){
			   				fillCalendar();
			   				$('#modal_exito_cancel').modal();
			   			}
			   		},
			   		error: function(e){
			   			console.log(e); 		
			   		}
			   	});
			 	});
			});

			$('.ver').click(function(){
				$( '#modal_ver_titulo' ).text(data.title);
				$('.popover').popover('hide');
				$('#modal_ver_inicio').text(data.start.format("DD/MM/YYYY HH:mm"));
			 	$('#modal_ver_termino').text(data.end.format("DD/MM/YYYY HH:mm"));
			 	if(data.detalles.trim()!=""){
			 		$( '#modal_ver_detalles').text(data.detalles);	
			 	}else{
			 		$( '#modal_ver_detalles').text("Este evento no tiene detalles adicionales.");
			 	}
			 	if(data.allDay){
			 		$( '#modal_ver_todo_el_dia' ).text("Si");
			 	}else{
			 		$( '#modal_ver_todo_el_dia' ).text("No");
			 	}
			 	if(data.rechazable){
			 		$( '#modal_ver_rechazable' ).text("Si");
			 	}else{
			 		$( '#modal_ver_rechazable' ).text("No");
			 	}
			 	$.ajax({
					url:currentLocation+'rooms/getById',
			   		type:'POST',
			   		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			   		data:{id: data.sala},
			   		success:function(room){
			   			$('#modal_ver_ubicacion').empty();
			   			$('#modal_ver_ubicacion').append(room[0].name);
			   		},
			   		error: function(e){
			   			console.log(e); 		
			   		}
			   	});
			 	$.ajax({
					url:currentLocation+'users/getByScheduleId',
			   		type:'POST',
			   		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			   		data:{id: data.id},
			   		success:function(users){
			   			$('#modal_ver_invitados').empty();
			   			users.forEach(function(user) {
			   				$('#modal_ver_invitados').append(user.name+"<br>");
			   			});
			   		},
			   		error: function(e){
			   			console.log(e); 		
			   		}
			   	});
			 	$('#modal_ver').modal();
			});

			$('.asistencia').click(function(){
				$('.popover').popover('hide');
			 	$('#modal_asistencia').modal();
			});
        }
	})
	fillCalendar();
	$('#datetimepicker7').datetimepicker({
		icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar-alt',
        },
         minDate: moment()
	});
    $('#datetimepicker8').datetimepicker({
        useCurrent: false,
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar-alt',
        },
        minDate: moment()
    });
    $("#datetimepicker7").on("change.datetimepicker", function (e) {
        $('#datetimepicker8').datetimepicker('minDate', e.date);
    });
    $("#datetimepicker8").on("change.datetimepicker", function (e) {
        $('#datetimepicker7').datetimepicker('maxDate', e.date);
    });

    $('#datetimepicker5').datetimepicker({
		icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar-alt',
        },
         minDate: moment()
	});
    $('#datetimepicker6').datetimepicker({
        useCurrent: false,
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar-alt',
        },
        minDate: moment()
    });
    $("#datetimepicker5").on("change.datetimepicker", function (e) {
        $('#datetimepicker6').datetimepicker('minDate', e.date);
    });
    $("#datetimepicker6").on("change.datetimepicker", function (e) {
        $('#datetimepicker5').datetimepicker('maxDate', e.date);
    });
	setInterval(fillCalendar(), 12000);
});

 function fillCalendar(){
 	var currentLocation = window.location;
    $.ajax({
       	type:'GET',
       	url:currentLocation+'schedules',
       	success:function(schedules){
       		//console.log(schedules);
       		var events = [];
			schedules.forEach(function(schedule) {
				if($.fullCalendar.moment(new Date())>$.fullCalendar.moment(schedule.end)){
					color = '#FE9A2E';
				}else if ($.fullCalendar.moment(new Date())<$.fullCalendar.moment(schedule.start)){
					color = '#00b259';
				}else{
					color = '#FF4000';
				}
				var allDay = schedule.all_day=="1" ? true : false;
				var rechazable = schedule.rejectable=="1" ? true : false;				
				event={
					id:schedule.id,
					title:schedule.title,
					start:schedule.start,
					end:schedule.end,
					backgroundColor: color,
					borderColor: color,
					allDay: allDay,
					textColor: 'white',
					tipo: schedule.type,
					detalles: schedule.details,
					rechazable: rechazable,
					sala: schedule.room_id,
					className:"punteable",
					displayEventTime:!allDay
				}
				events.push(event);
			});
			$('#calendar').fullCalendar('removeEvents');
			$('#calendar').fullCalendar('addEventSource', events);
       	},
       	error:function(e){
       		console.log(e);
       	}
    });
 }

 $('#agendar').click(function(){
 	$('#modal_agendar_recomendados_div').hide();
 	$('.modal_agendar_rango').val("");
 	$( "#modal_agendar_aceptar" ).prop("disabled", true);
 	$( ".modal_agendar_fecha" ).prop("disabled", true);
 	$( ".modal_agendar_fecha" ).empty();
 	$( '#modal_agendar_titulo' ).val("");
 	$( '#modal_agendar_detalles' ).val("");
 	$( '#modal_agendar_rechazable' ).prop( "checked", false );
 	$( '#modal_agendar_todo_el_dia' ).prop( "checked", false );
 	
 	$('#modal_agendar_aceptar').data('id_schedule', '0');
 	$('#modal_agendar_aceptar').text('Agendar reunión');
 	$('#modal_agendar_titulo_modal').text('Agendar reunión');

 	$('#modal_agendar').modal();
 	$('#datetimepicker7').datetimepicker({minDate: moment()});
 	$('#datetimepicker7').datetimepicker({maxDate: null});
 })

 $('.modal_agendar_form').change(function(){
 	validarLLenado();
 });

 $('.modal_agendar_form').on("input",function(){
	validarLLenado();
 })

 function validarLLenado(){
 	var titulo = $.trim($('#modal_agendar_titulo').val());
 	var inicio = $.trim($('#modal_agendar_inicio').val());
 	var final = $.trim($('#modal_agendar_termino').val());
 	var ubicacion = $('#modal_agendar_ubicacion').val();
 	var rechazable = $('#modal_agendar_rechazable').is(':checked');
 	var todo_el_dia = $('#modal_agendar_todo_el_dia').is(':checked');
 	var detalles = $.trim($('#modal_agendar_detalles').val());
 	var invitados =$.map($('#modal_agendar_invitados option'), function(element) {return element.value;});
 	if(titulo!=""&&inicio!=""&&final!=""&&ubicacion!=""&&invitados.length>0&&ubicacion!=null){
 		$( "#modal_agendar_aceptar" ).prop("disabled", false);
 	}else{
 		$( "#modal_agendar_aceptar" ).prop("disabled", true);
 	}
 }

 $('.modal_agendar_rango').on("input",function(){
 	verificarHora();
 });

 function verificarHora(){
 	var inicio = $.trim($('#modal_agendar_inicio').val());
 	var final = $.trim($('#modal_agendar_termino').val());
 	if(inicio!=""&&final!=""){
 		var inicio = moment(inicio, 'DD/MM/YYYY HH:mm').format('YYYYMMDD HH:mm');
 		var final = moment(final, 'DD/MM/YYYY HH:mm').format('YYYYMMDD HH:mm');
 		var currentLocation = window.location;
 		var data={inicio:inicio, final:final};
	 	$.ajax({
			url:currentLocation+'rooms/getByRange',
	   		type:'POST',
	   		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	   		data:data,
	   		success:function(rooms){
	   			$('#modal_agendar_ubicacion').empty();
	   			$.each(rooms, function( index, room ) {
	   				if(room.reuniones>0){
	   					var disponible = false;
	   				}else{
	   					var disponible = true;
	   				}
					$('#modal_agendar_ubicacion').append($('<option>', {
					    value: room.id,
					    text: room.name,
					    disabled: !disponible
					}));
				});
				var salas =$.map($('#modal_agendar_ubicacion option'), function(element) {return element.value;});
				if(salas.length>0){
					$( "#modal_agendar_ubicacion" ).prop("disabled", false);
				}
				validarLLenado();
				$.ajax({
					url:currentLocation+'users/getByRange',
			   		type:'POST',
			   		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			   		data:data,
			   		success:function(users){
			   			$('#modal_agendar_usuarios').empty();
			   			$('#modal_agendar_invitados').empty();
			   			$.each(users, function( index, user ) {
			   				if(user.reuniones>0){
			   					var disponible = "rojo";
			   				}else{
			   					var disponible = "";
			   				}
							$('#modal_agendar_usuarios').append($('<option>', {
							    value: user.id,
							    text: user.name,
							    class: disponible
							}));
						});
						$( ".modal_agendar_fecha" ).prop("disabled", false);
						validarLLenado();
			   		},
			   		error: function(e){
			   			console.log(e); 		}
			   	});
	   		},
	   		error: function(e){
	   			console.log(e);   		}
		});
 	}
 };

 $('.dual_list_select').change(function(){
	var esto=this;
	$.each($(esto).parents('.dual_list').find('.select_origen').find("option:selected"), function( index, value ) {
		$(esto).parents('.dual_list').find('.select_destino').append('<option class="'+$(value).attr('class')+'" value="'+$(value).val()+'">'+$(value).text()+'</option>');
  		value.remove();
	});
	$.each($(esto).parents('.dual_list').find('.select_destino').find("option:selected"), function( index, value ) {
		$(esto).parents('.dual_list').find('.select_origen').append('<option class="'+$(value).attr('class')+'" value="'+$(value).val()+'">'+$(value).text()+'</option>');
  		value.remove();
	});
	ordernar_listas($(esto).parents('.dual_list').find('.select_destino'));
	ordernar_listas($(esto).parents('.dual_list').find('.select_origen'));
	validarLLenado();
 })


function ordernar_listas(lista){
	var options = $(lista).find('option');                       
	options.detach().sort(function(a,b) {               
	    var at = $(a).text();
	    var bt = $(b).text();         
	    return (at > bt)?1:((at < bt)?-1:0);   
	});
	options.appendTo(lista);     
}
$(document).on('click','#modal_ocupados_aceptar', function () {
	var currentLocation = window.location;
	$.ajax({
	url:currentLocation+'schedules',
		type:'POST',
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		data:data,
		success:function(status){
			if(status){
				fillCalendar();
				$('#modal_agendar').modal('hide');
				$('#modal_ocupados').modal('hide');
				$('#modal_exito').modal('show');
			}
		},
		error: function(e){
			console.log(e); 		
		}
	});
});

$(document).on('click','#modal_ocupados_buscar', function () {
	var currentLocation = window.location;
	var titulo = $.trim($('#modal_agendar_titulo').val());
 	var inicio = moment($.trim($('#modal_agendar_inicio').val()), 'DD/MM/YYYY HH:mm').format('YYYYMMDD HH:mm');
 	var final = moment($.trim($('#modal_agendar_termino').val()), 'DD/MM/YYYY HH:mm').format('YYYYMMDD HH:mm');
 	var ubicacion = $('#modal_agendar_ubicacion').val();
 	var rechazable = $('#modal_agendar_rechazable').is(':checked')? 1 : 0;;
 	var todo_el_dia = $('#modal_agendar_todo_el_dia').is(':checked')? 1 : 0;;
 	var detalles = $.trim($('#modal_agendar_detalles').val());
 	var invitados =$.map($('#modal_agendar_invitados option'), function(element) {return $(element).val();});
 	var invitadosOptions =$.map($('#modal_agendar_invitados option'), function(element) {return element;});
 	data={
		titulo: titulo,
		inicio: inicio,
		final: final,
		ubicacion: ubicacion,
		rechazable: rechazable,
		todo_el_dia: todo_el_dia,
		detalles: detalles,
		invitados: invitados
 	};
	$('#modal_ocupados_buscar').html('<i class="fa fa-spinner fa-spin"></i> Buscando...');
	$('#modal_ocupados_aceptar').prop("disabled", true);
	$.ajax({
	url:currentLocation+'users/getFreeTime',
		type:'POST',
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		data:data,
		success:function(options){
			$('#modal_ocupados_buscar').html('Ver otro horario');
			$('#modal_ocupados_aceptar').prop("disabled", false);
			$('#modal_agendar_recomendados').empty();
			options.forEach(function(option) {
				$('#modal_agendar_recomendados').append('<tr><td class="pt-0 pl-3 pr-3 pb-0">'+moment(option.start, "YYYYMMDD HH:mm").format("DD/MM/YYYY HH:mm")+'</td><td class="pt-0 pl-3 pr-3 pb-0">'+moment(option.end, "YYYYMMDD HH:mm").format("DD/MM/YYYY HH:mm")+'</td><td class="pt-0 pl-3 pr-3 pb-0"><i class="far fa-check-circle modal_agendar_recomendados_seleccionar" data-inicio="'+moment(option.start, "YYYYMMDD HH:mm").format("DD/MM/YYYY HH:mm")+'" data-final="'+moment(option.end, "YYYYMMDD HH:mm").format("DD/MM/YYYY HH:mm")+'"></i></td></tr>');
			});
			$('#modal_agendar_recomendados_div').show();
			$('#modal_ocupados').modal('hide');
			$('#modal_agendar').modal('show');
			$('.modal_agendar_recomendados_seleccionar').click(function(){
			$('#modal_agendar_inicio').val($(this).data("inicio"));
			$('#modal_agendar_termino').val($(this).data("final"));
			$('#modal_agendar_recomendados_div').hide();
			verificarHora();
		});
		},
		error: function(e){
			console.log(e); 		
		}
	});
});

$("#modal_agendar_aceptar").click(function(){
	var currentLocation = window.location;
	var titulo = $.trim($('#modal_agendar_titulo').val());
 	var inicio = moment($.trim($('#modal_agendar_inicio').val()), 'DD/MM/YYYY HH:mm').format('YYYYMMDD HH:mm');
 	var final = moment($.trim($('#modal_agendar_termino').val()), 'DD/MM/YYYY HH:mm').format('YYYYMMDD HH:mm');
 	var ubicacion = $('#modal_agendar_ubicacion').val();
 	var rechazable = $('#modal_agendar_rechazable').is(':checked')? 1 : 0;;
 	var todo_el_dia = $('#modal_agendar_todo_el_dia').is(':checked')? 1 : 0;;
 	var detalles = $.trim($('#modal_agendar_detalles').val());
 	var invitados =$.map($('#modal_agendar_invitados option'), function(element) {return $(element).val();});
 	var invitadosOptions =$.map($('#modal_agendar_invitados option'), function(element) {return element;});
 	if(titulo!=""&&inicio!=""&&final!=""&&ubicacion!=""&&invitados.length>0){
 		$( "#modal_agendar_aceptar" ).prop("disabled", false);
 		data={
 			titulo: titulo,
 			inicio: inicio,
 			final: final,
 			ubicacion: ubicacion,
 			rechazable: rechazable,
 			todo_el_dia: todo_el_dia,
 			detalles: detalles,
 			invitados: invitados
 		}
 		var hayOcupados=false;
 		$.each(invitadosOptions, function( index, invitado ) {
 			if($(invitado).attr('class')=="rojo"){
				hayOcupados=true;
 			}
 		});
 		if(hayOcupados){
 			$('#modal_ocupados').modal({backdrop: 'static', keyboard: false});
 			$('#modal_agendar').modal('hide');
 			$('#modal_ocupados').modal('show');
 		}else{
 			$.ajax({
				url:currentLocation+'schedules',
		   		type:'POST',
		   		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		   		data:data,
		   		success:function(status){
		   			if(status){
		   				fillCalendar();
		   				$('#modal_agendar').modal('hide');
		   				$('#modal_exito').modal('show');
		   			}
		   		},
		   		error: function(e){
		   			console.log(e); 		
		   		}
		   	});
 		}

 	}else{
 		$( "#modal_agendar_aceptar" ).prop("disabled", true);
 	}
});


$('#bloquear').click(function(){
 	$('.modal_bloquear_rango').val("");
 	$( "#modal_bloquear_aceptar" ).prop("disabled", true);
 	//$( ".modal_bloquear_fecha" ).prop("disabled", true);
 	//$( ".modal_bloquear_fecha" ).empty();
 	$( '#modal_bloquear_titulo' ).val("");
 	$( '#modal_bloquear_detalles' ).val("");
 	$( '#modal_bloquear_todo_el_dia' ).prop( "checked", false );

 	$('#modal_bloquear').modal();
});
