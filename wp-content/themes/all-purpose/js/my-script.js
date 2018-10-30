jQuery(document).ready(function($){
	var body = $('body');
	$('input').click(function(){
		if ($('input').val() == 'Delete') {
			var isGood = confirm('Are you sure you want delete this photo?');
			var post_number = $(this).attr('name');
			if (isGood) {
				$.ajax({ url: 'https://www.visorsourcing.com/wp-content/themes/all-purpose/delete_post.php',
						data: {delete_var: post_number},
						type: 'post',
						success: function(output) {
									location.reload();
				}});
			}
			else {
				location.reload();
			}
		}
});})
