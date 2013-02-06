var resizeContents = function() {
	$('#contents').height($(window).height() - $('#masthead').height() - $('#tools').height());
}

$(function(){

	$('#contents').on('click', '.chapter > a', function(ev){
		$('.chapter').not(this).removeClass('active');
		$(this).closest('.chapter').toggleClass('active');
	});


	$('#contents').on('click', '.section a', function(ev){
		var $a = $(this);
		var $section = $a.closest('li.section');
		$('.section').not($section).removeClass('active');
		$section.addClass('active');

		$href = $a.attr('href');
		if($href[0] == '/') {
			$('#page_content').load($href + ' #page_content > *');
		}
		ev.preventDefault();
	});

	$('body').on('click', '.editlink', function(ev){
		$loading = $('<div class="loading">loading...</div>');
		$loading
			.width($('#editable').width())
			.height($('#editable').height());

		$('#editable')
			.html('')
			.append($loading)
			.load($(this).attr('href'));

		ev.preventDefault();
	});

	$(window).resize(resizeContents);
});