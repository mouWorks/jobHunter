<li>	<dl id="searct_location" class="search_location">		<dt class="" value="{{$value}}">{{$text}}</dt>	</dl>	<ul class="search_location_block" id="locations" style="display: none;">		@foreach ($locations as $value => $text)			<li value="{{$value}}"><a href="javascript:;">{{$text}}</a></li>		@endforeach	</ul></li><script>	$(document).ready(function(){		$("#locations li").on('click', function(){			$("#searct_location dt").text($(this).find('a').text());			$("#searct_location dt").attr('value', $(this).attr('value'));			$(this).parent().hide();		});		$("#submit").on('click', function(){			let selectLocation = $("#searct_location dt").attr('value');			let q = $("#q").val();			let url = '';			if (location.pathname === '/awshack') {				url = location.protocol + '//' + location.hostname + location.pathname + '/list/104';			} else {				url = location.protocol + '//' + location.hostname + location.pathname;			}			location.href = url + '?q=' + q + "&selectLocation=" + selectLocation;		});		$(".search_form").on('submit', function (event){			event.preventDefault();		});	});</script>