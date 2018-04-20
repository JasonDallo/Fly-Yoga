$(document).ready(function(){

	$('#cardShop').click(function(){
		showShop();
	});
	$.ajax({
		url: "/courses/weekly",
		method: "GET",

		success: function(data){
			refreshCalendar(data);
		}
	});

	function refreshCalendar(data){
		var Mesevents = [];
		$.each(data, function(index, value){
			var obj = {title  : value.title, start  : value.date_debut, defaultTimedEventDuration: "4.5", id: value.id};
			Mesevents.push(obj);
			
		})
		$('.calendar').fullCalendar({

			eventClick: function(calEvent, jsEvent, view) {
				$.ajax({
					url: "/courses/event",
					method: "POST",
					data: {'id': calEvent.id},
					success: function(data){
						$('#modalTitle').html(calEvent.title);
						$('.img-circle').attr('src', "http://vignette2.wikia.nocookie.net/browmanwood/images/8/86/Point_d'interrogation.png/revision/latest?cb=20130217173859&path-prefix=fr");
						$('.media-heading').prepend(data.title);
						$('.prof').html(data.prof);
						$(".infos").html("<span class='label label-warning'>"+data.lieu+"</span>"+"<span class='label label-info'>"+data.tarif+"€</span>"+"<span class='label label-info'>"+data.placeUses+"/"+data.nbr_place+"</span>"+"<span class='label label-info'>"+data.duree+"</span>"+"<span class='label label-success'>"+data.type+"</span>");
						$('#eventUrl').attr('href',calEvent.url);
						$('#PostForm').append('<input type="hidden" name="id" value="'+ calEvent.id +'">');
						$('#fullCalModal').modal();
					}
				});
			},
			dayClick: function() {
				alert('Bonjour');
			},
			lang: 'fr',
			aspectRatio: 2.5,
			customButtons: {
				myCustomButton: {
					text: 'Custom',
					click: function() {
						alert('Custom');
					}
				}
			},
			header: {
				left: 'prev,next today myCustomButton',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},

			events: Mesevents,
		});
		
	};

	function showShop(){
		$.ajax({
			url: "/courses/cards",
			method: "GET",
			success: function(data){
				$.each(data, function(index, value){
					$('#shopBody').append('<div class="item"><img src="../../img/shop/'+ value.id +'.jpg" style="width:150px;"><ul><li>'+ value.name +'</li><li>'+ value.prix +'</li><li>'+ value.description +'</li></ul><form method="POST" action="/courses/cards/checkout"> <input type="hidden" name="id" value="'+ value.id +'"><button type="submit" class="btn btn-primary">Acheter</button></div>')
				});
				$('#Shop').modal();
			}
		});
	};
})