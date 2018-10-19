$( document ).ready(function() {
	$('#calendar').fullCalendar({
	})
	fillCalendar();
	setInterval(fillCalendar(), 12000);
});

 function fillCalendar(){
 	var currentLocation = window.location;
    $.ajax({
       	type:'GET',
       	url:currentLocation+'schedules',
       	success:function(schedules){
       		events = [];
			schedules.forEach(function(schedule) {
				if($.fullCalendar.moment(new Date())>$.fullCalendar.moment(schedule.end)){
					color = '#FE9A2E';
				}else if ($.fullCalendar.moment(new Date())<$.fullCalendar.moment(schedule.start)){
					color = '#00b259';
				}else{
					color = '#FF4000';
				}
				event={
					id:schedule.id,
					title:schedule.title,
					start:schedule.start,
					end:schedule.end,
					backgroundColor: color,
					borderColor: color,
					allDay: schedule.all_day,
					textColor: 'white'
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

 $.ajax({
 	url:'rooms/getByRange',
 	type:'POST',
 	headers: {'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')},
 	data:{inicio:moment("20181001 10:21", "YYYYMMDD HH:mm").format("YYYYMMDD HH:mm"), final:moment("20181030 10:21" , "YYYYMMDD HH:mm").format("YYYYMMDD HH:mm")},
 	//data:data,
 	success:function(rdata){
 		console.log(rdata);
 	},
 	error: function(e){
 		console.log(e);
 	}

 })


 $.ajax({
 	url:'users/getUsersByRange',
 	type:'POST',
 	headers: {'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')},
 	data:{inicio:moment("20181001 10:21", "YYYYMMDD HH:mm").format("YYYYMMDD HH:mm"), final:moment("20181030 10:21" , "YYYYMMDD HH:mm").format("YYYYMMDD HH:mm")},
 	//data:data,
 	success:function(rdata){
 		console.log(rdata);
 	},
 	error: function(e){
 		console.log(e);
 	}

 })