jQuery(document).ready(function(){
$ = jQuery.noConflict();
 jQuery('div[class="mobile-navigation"]').find('li').find('ul').hide();
jQuery('a[href="#pll_switcher"]').click(function(){$(this).next().hide().slideDown();});
jQuery('li.item_welcome').css('color','green');
jQuery('#usp-success-message').css('color','white');
var x = jQuery('title').text();
if (x=='即將擧行 – CPAS' || x=='Upcoming Activities – CPAS') {jQuery("div.post-container").replaceWith("<iframe src='https://calendar.google.com/calendar/embed?src=684d8jj4f0qd2d359j4geefqmg%40group.calendar.google.com&ctz=America%2FEdmonton' style='border: 0' width='800' height=600' frameborder='0' scrolling='no'></iframe>");}
$('input').click(function(){
	if ($(":input#delete_button").val() == 'Delete') {
		var isGood = confirm('Are you sure you want delete this photo?');
		var post_number = $(this).attr('name');
		if (isGood) {
			$.ajax({ url: 'https://www.cpas-yyc.com/wp-content/themes/hitchcock/delete_post.php',
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
});
}
);
