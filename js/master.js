$(function(){

	$('#contents').on('click', 'a', function(ev){
		var $a = $(this);
		var $li = $a.closest('li.section,li.chapter');
		var is_section = $li.hasClass('section');
		var is_chapter = $li.hasClass('chapter');
		if(!$li.hasClass('active')) {
			if(is_section) {
				$('#contents li.section.active').removeClass('active');
			}
			if(is_chapter) {
				$('#contents li.chapter.active').removeClass('active');
			}
		}
		console.log($li[0]);
		$li.toggleClass('active');
		$href = $a.attr('href');
		if($href[0] == '/') {
			$('#page_content').load($href + ' #page_content > *');
		}
		ev.preventDefault();
	});

});