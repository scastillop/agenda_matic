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
       	}
    });
 }