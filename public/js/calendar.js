$( document ).ready(function() {
	if ($(window).width() < 514){
        $('#calendar').fullCalendar('option', 'aspectRatio', 0.7);
    } else {
        $('#calendar').fullCalendar('option', 'aspectRatio', 1.35);
    }
	$('#calendar').fullCalendar({
		eventLimit: true,
		timeFormat: 'HH:mm',
		header: { right: 'today prev,next'},
		footer: { center: 'month,agendaWeek,agendaDay'},
		aspectRatio: 1,
		views: {
			agendaWeek: {
			  	titleFormat: 'DD/MM',
			  	columnHeaderFormat: 'ddd D'
			},
			agendaDay: {
			  	titleFormat: 'DD/MM/YYYY'
			}
		},
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
				if(data.owner_id==$("body").data("id")){
					editar = '<div class="rounded opciones_reunion p-1 pr-2 pl-2 editar" ver" title="editar evento"><i class="fas fa-pencil-alt"></i></div>';
					eliminar = '<div class="rounded opciones_reunion p-1 pr-2 pl-2 eliminar" ver" title="eliminar evento"><i class="fas fa-trash-alt"></i></div>';
				}else if(data.rechazable){
					cancelar = '<div class="rounded opciones_reunion p-1 pr-2 pl-2 cancelar" ver" title="cancelar asistencia"><i class="fas fa-ban"></i></div>';	
				}
			}else{
				if(!data.asistenciaRegistrada&&data.owner_id==$("body").data("id")&&data.tipo!="off"){
					asistencia = '<div class="rounded opciones_reunion p-1 pr-2 pl-2 asistencia" ver" title="registrar asistencia"><i class="fas fa-clipboard-list"></i></div>';
				}
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
			 	$('#modal_agendar_aceptar').data('id_room', data.sala);
			 	$('#modal_agendar_aceptar').text('Guardar cambios');
			 	$('#modal_agendar_titulo_modal').text('Editar reunión');
			 	$('#modal_agendar').modal();
			 	verificarHora();
			 	$("#modal_agendar_aceptar" ).prop("disabled", false);
			 	$( ".modal_agendar_fecha" ).prop("disabled", false);
			 });
			$('.cancelar').click(function(){
				$('.popover').popover('hide');
				$('#modal_cancelar_body').html('Realmente desea rechazar su asistencia a la reunión "<strong>'+data.title+'</strong>" del dia '+data.start.format("DD-MM-YYYY")+' a las '+data.start.format("HH:mm")+'?');
			 	$('#modal_cancelar').modal().click(function(){
			 		$.ajax({
					url:currentLocation+'guests/rejectById',
			   		type:'POST',
			   		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			   		data:{id: data.id, user_id: 10},			   		
			   		success:function(success){
			   			if(success){
			   				fillCalendar();
			   				$('#modal_cancelar_exito').modal();
			   			}
			   		},
			   		error: function(e){
			   			console.log(e); 		
			   		}
			   	});
			 	});
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
			   			if(e.status==419){
			   				location.reload();
			   			}		
			   		}
			   	});
			 	});
			});
			$('.ver').click(function(){
				$( '#modal_ver_titulo' ).text(data.title);
				$('.popover').popover('hide');
				$('#modal_ver_inicio').text(data.start.format("DD/MM/YYYY HH:mm"));
			 	$('#modal_ver_termino').text(data.end.format("DD/MM/YYYY HH:mm"));
			 	if(data.detalles&&data.detalles.trim()!=""){
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
			 	if(data.sala){
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
				   			if(e.status==419){
				   				location.reload();
				   			}		
				   		}
				   	});
			 	}else{
			 		$('#modal_ver_ubicacion').empty();
				   	$('#modal_ver_ubicacion').append("Externa");
			 	}
			 	
			 	$.ajax({
					url:currentLocation+'users/getByScheduleId',
			   		type:'POST',
			   		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			   		data:{id: data.id},
			   		success:function(users){
			   			$('#modal_ver_invitados').empty();
			   			users.forEach(function(user) {
			   				if(data.asistenciaRegistrada){
			   					var asistio = user.concurred!=0?'<i class="far fa-calendar-check" title="Asistió"></i>':'<i class="far fa-calendar-times" title="No asistió"></i>'	
			   				}else{
			   					var asistio = "";
			   				}
			   				$('#modal_ver_invitados').append(asistio+"	"+user.name+"<br>");
			   			});
			   		},
			   		error: function(e){
			   			console.log(e.status);
			   			console.log(e);
			   			if(e.status==419){
			   				location.reload();
			   			}			
			   		}
			   	});
			 	$('#modal_ver').modal();
			});
			$('.asistencia').click(function(){
				$('.popover').popover('hide');
			 	$.ajax({
					url:currentLocation+'users/getByScheduleId',
			   		type:'POST',
			   		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			   		data:{id: data.id},
			   		success:function(users){
			   			$('#tabla_asistencia tbody').empty();
			   			users.forEach(function(user) {
			   				$('#tabla_asistencia tbody').append('<tr><td class="text-right"><div class="checkbox checkbox-single"><input type="checkbox" class="check_asistencia" data-id="'+user.id+'"><label></label></div></td><td>'+user.name+"</td></tr>");
			   			});
			   			$('#modal_asistencia_aceptar').data("id", data.id);
			   			$('#modal_asistencia').modal();
			   		},
			   		error: function(e){
			   			console.log(e.status);
			   			console.log(e);
			   			if(e.status==419){
			   				location.reload();
			   			}			
			   		}
			   	});
			});
        }
	});
	
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

    $('#datetimepicker3').datetimepicker({
    	useCurrent: false,
		icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar-alt',
        },
         maxDate: moment()
	});
    $('#datetimepicker4').datetimepicker({
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar-alt',
        },
        maxDate: moment()
    });
    $("#datetimepicker3").on("change.datetimepicker", function (e) {
        $('#datetimepicker4').datetimepicker('minDate', e.date);
    });
    $("#datetimepicker4").on("change.datetimepicker", function (e) {
        $('#datetimepicker3').datetimepicker('maxDate', e.date);
    });
	setInterval(fillCalendar(), 12000);
});

$( window ).resize(function() {
  	if ($(window).width() < 514){
        $('#calendar').fullCalendar('option', 'aspectRatio', 0.7);
    } else {
        $('#calendar').fullCalendar('option', 'aspectRatio', 1.35);
    }
});

$('#modal_asistencia_aceptar').click(function(){
	var currentLocation = window.location;
	var asistentes = $.map($('.check_asistencia:checked'), function(element) {return $(element).data("id");});
	$.ajax({
		url:currentLocation+'guests/setAssistance',
   		type:'POST',
   		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
   		data:{asistentes: asistentes, id: $('#modal_asistencia_aceptar').data("id")},
   		success:function(response){
   			fillCalendar();
   			$('#modal_asistencia').modal('hide');
   			$('#modal_asistencia_exito').modal();
   		},
   		error: function(e){
   			console.log(e.status);
   			console.log(e);
   			if(e.status==419){
   				location.reload();
   			}			
   		}
   	});
});

 function fillCalendar(){
 	var currentLocation = window.location;
    $.ajax({
       	type:'GET',
       	url:currentLocation+'schedules',
       	success:function(schedules){
       		var events = [];
			schedules.forEach(function(schedule) {
				if($.fullCalendar.moment(new Date())>$.fullCalendar.moment(schedule.end)){
					color = '#FE9A2E';
				}else if ($.fullCalendar.moment(new Date())<$.fullCalendar.moment(schedule.start)){
					color = '#00b259';
				}else{
					color = '#FF4000';
				}

				if(schedule.type == 'off'){
					color = '#dddddd';
				}

				var allDay = schedule.all_day=="1" ? true : false;
				var rechazable = schedule.rejectable=="1" ? true : false;				
				event={
					id:schedule.id,
					title:schedule.title,
					start:schedule.start,
					end:schedule.end,
					backgroundColor: color,
					asistenciaRegistrada: schedule.registered_assistance,
					borderColor: color,
					allDay: allDay,
					textColor: 'white',
					tipo: schedule.type,
					detalles: schedule.details,
					rechazable: rechazable,
					sala: schedule.room_id,
					className:"punteable",
					owner_id:schedule.owner_id,
					displayEventTime:!allDay
				}
				events.push(event);
			});
			$('#calendar').fullCalendar('removeEvents');
			$('#calendar').fullCalendar('addEventSource', events);
			if ($(window).width() < 514){
		        $('#calendar').fullCalendar('option', 'aspectRatio', 0.7);
		    } else {
		        $('#calendar').fullCalendar('option', 'aspectRatio', 1.35);
		    }
       	},
       	error:function(e){
       		console.log(e);
       		if(e.status==419){
   				location.reload();
   			}	
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
 	$('#modal_agendar_aceptar').data('id_room', '0');
 	$('#modal_agendar_aceptar').text('Agendar reunión');
 	$('#modal_agendar_titulo_modal').text('Agendar reunión');

 	$('#modal_agendar').modal();
 	
 	$('#datetimepicker7').data("datetimepicker").maxDate(false);
 	$('#datetimepicker8').data("datetimepicker").minDate(false);
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
 		var data={inicio:inicio, final:final, id: $("#modal_agendar_aceptar").data("id_schedule")};
 		if(data.id>0){
 			var url="getByRangeAvoidId"
 		}else{
 			var url="getByRange"
 		}
	 	$.ajax({
			url:currentLocation+"rooms/"+url,
	   		type:'POST',
	   		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	   		data:data,
	   		success:function(rooms){
	   			$('#modal_agendar_ubicacion').empty();
	   			$('#modal_agendar_ubicacion').append($('<option>', {
				    value: 0,
				    text: "Externa"
				}));
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
	   			if($("#modal_agendar_aceptar").data("id_room")>0){
	   				$("#modal_agendar_ubicacion").val($("#modal_agendar_aceptar").data("id_room")).prop('selected', true);
	   			}
				var salas =$.map($('#modal_agendar_ubicacion option'), function(element) {return element.value;});
				if(salas.length>0){
					$( "#modal_agendar_ubicacion" ).prop("disabled", false);
				}
				validarLLenado();
				$.ajax({
					url:currentLocation+'users/'+url,
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
						if(data.id>0){
							$.ajax({
								url:currentLocation+'users/getByScheduleId',
						   		type:'POST',
						   		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
						   		data:{id: data.id},
						   		success:function(users){
						   			users.forEach(function(user){
						   				$.each($('#modal_agendar_usuarios option'), function(index, opcion){
						   					if($(opcion).val()==user.id){
						   						$('#modal_agendar_invitados').append('<option class="'+$(opcion).attr('class')+'" value="'+$(opcion).val()+'">'+$(opcion).text()+'</option>');
						   						$(opcion).remove();
						   						ordernar_listas();
						   					}
						   				});
						   			});
						   			validarLLenado();
						   		},
						   		error: function(e){
						   			console.log(e.status);
						   			console.log(e);
						   			if(e.status==419){
						   				location.reload();
						   			}			
						   		}
						   	});
						}else{
							validarLLenado();
						}


						
			   		},
			   		error: function(e){
			   			console.log(e); 		}
			   	});
	   		},
	   		error: function(e){
	   			console.log(e);
	   			if(e.status==419){
			   		location.reload();
				}	
			}
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
			if(e.status==419){
   				location.reload();
   			}	 		
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
			if(e.status==419){
   				location.reload();
   			}			
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
 			invitados: invitados,
 			id: $("#modal_agendar_aceptar").data("id_schedule")
 		}
 		var hayOcupados=false;
 		$.each(invitadosOptions, function( index, invitado ) {
 			if($(invitado).attr('class')=="rojo"){
				hayOcupados=true;
 			}
 		});
 		if(data.id>0){
			var url="schedules/edit";
 		}else{
 			var url="schedules";
 		}
 		if(hayOcupados){
 			$('#modal_ocupados').modal({backdrop: 'static', keyboard: false});
 			$('#modal_agendar').modal('hide');
 			$('#modal_ocupados').modal('show');
 		}else{
 			$.ajax({
				url:currentLocation+url,
		   		type:'POST',
		   		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		   		data:data,
		   		success:function(status){
		   			if(status){
		   				fillCalendar();
		   				$('#modal_agendar').modal('hide');
		   				$('#modal_exito').modal('show');

		   				data={to_email : "jonathan.arce.93@gmail.com", 
		   						nameUser: "Jonathan",
	 							subject: "Agendamiento reunion",
	 							ownerUser: "Jorge",
	 							dateSchedule: "28-10-2018"
	 							}

		   				$.ajax({
							url:currentLocation+'mail/send',
					   		type:'POST',
					   		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					   		data:data,
					   		success:function(status){
					   			if(status){
					   				console.log("ok");
					   			}
					   		},
					   		error: function(e){
					   			console.log(e); 		
					   		}
		   				});


		   			}
		   		},
		   		error: function(e){
		   			console.log(e);
		   			if(e.status==419){
		   				location.reload();
		   			}			
		   		}
		   	});
 		}

 	}else{
 		$( "#modal_agendar_aceptar" ).prop("disabled", true);
 	}
});


$('#bloquear').click(function(){
 	$('.modal_bloquear_rango').val("");
 	$( "#modal_bloquear_aceptar" ).prop("disabled", false);
 	//$( ".modal_bloquear_fecha" ).prop("disabled", true);
 	//$( ".modal_bloquear_fecha" ).empty();
 	$( '#modal_bloquear_titulo' ).val("");
 	$( '#modal_bloquear_detalles' ).val("");
 	$( '#modal_bloquear_todo_el_dia' ).prop( "checked", false );

 	$('#datetimepicker5').data("datetimepicker").maxDate(false);
 	$('#datetimepicker6').data("datetimepicker").minDate(false);

 	$('#modal_bloquear').modal();
});


$("#modal_bloquear_aceptar").click(function(){
	var currentLocation = window.location;
	var titulo = $.trim($('#modal_bloquear_titulo').val());
 	var inicio = moment($.trim($('#modal_bloquear_inicio').val()), 'DD/MM/YYYY HH:mm').format('YYYYMMDD HH:mm');
 	var final = moment($.trim($('#modal_bloquear_termino').val()), 'DD/MM/YYYY HH:mm').format('YYYYMMDD HH:mm');
 	var todo_el_dia = $('#modal_bloquear_todo_el_dia').is(':checked')? 1 : 0;;
 	var detalles = $.trim($('#modal_bloquear_detalles').val());

 	if(titulo!=""&&inicio!=""&&final!=""){
 		$( "#modal_bloquear_aceptar" ).prop("disabled", false);

 		data={
 			id_guest : ["10"], 
 			titulo: titulo,
 			inicio: inicio,
 			final: final,
 			todo_el_dia: todo_el_dia,
 			detalles: detalles
 		}

 		$.ajax({
				url:currentLocation+'schedules/storeOff',
		   		type:'POST',
		   		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		   		data:data,
		   		success:function(status){
			   		console.log(status);	
	   			if(status == 1){
	   				fillCalendar();
	   				$('#modal_bloquear').modal('hide');
	   				$('#modal_bloquear_exito').modal('show');
	   			}else{
	   				$('#modal_bloquear').modal('hide');
		   			$( "#modal_bloquear_atencion" ).modal();

	   			}
		   		},
		   		error: function(e){
		   			console.log(e); 		
		   		}
		   	});
 	

 	}else{
 		$( "#modal_agendar_aceptar" ).prop("disabled", true);
 	}
});

$("#estadisticas").click(function(){
	$('#datetimepicker4').data("datetimepicker").maxDate(moment());
	$('#datetimepicker3').data("datetimepicker").maxDate(moment());
	$('#datetimepicker4').data("datetimepicker").defaultDate(moment(new Date()).subtract("m", 1));
	$('#datetimepicker3').data("datetimepicker").defaultDate(moment(new Date()).subtract("M", 1));
	generarEstadisticas();
	$("#modal_estadistica").modal();
});

function generarEstadisticas(){
	var inicio = $.trim($('#modal_estadisticas_inicio').val());
 	var final = $.trim($('#modal_estadisticas_termino').val());
 	if(inicio!=""&&final!=""){
 		var inicio = moment(inicio, 'DD/MM/YYYY HH:mm').format('YYYYMMDD HH:mm');
 		var final = moment(final, 'DD/MM/YYYY HH:mm').format('YYYYMMDD HH:mm');
 		var currentLocation = window.location;
 		var data={inicio:inicio, final:final};
	 	$.ajax({
			url:currentLocation+"users/statistics",
	   		type:'POST',
	   		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	   		data:data,
	   		success:function(users){
	   			$('#tabla_estadisticas tbody').empty();
	   			users.forEach(function(user) {
	   				$('#tabla_estadisticas tbody').append('<tr><td>'+user.name+'</td><td>'+user.reuniones+'</td><td>'+user.asistencia+'</td><td>'+redondear((user.asistencia*100)/user.reuniones)+'%</td><td>'+user.bloqueados+'</td></tr>');
	   			});
	   		},
	   		error: function(e){
	   			console.log(e); 		
	   		}
	   	});
	 } 			
}

function redondear(numero){
	if(numero%1==0){
		return numero;
	}else{
		return numero.toFixed(2)
	}
}

 $('.modal_estadisticas_rango').on("input",function(){
 	generarEstadisticas();
 });