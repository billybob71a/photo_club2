jQuery(document).ready(function(){
$ = jQuery.noConflict();
 var x = jQuery('h1[class="post-title"]').text();
var divTriangle = $("<i/>").addClass("arrow");
if (x=='Home') {jQuery('.post-container').css("background-color", "transparent");}
if (x=='主頁') {jQuery('.post-container').css("background-color", "transparent");}			 
 jQuery('.content').css("background-color", "transparent");
 jQuery('div[class="mobile-navigation"]').find('li').find('ul').hide();
jQuery('a[href="#pll_switcher"]').click(function(){$(this).next().hide().slideDown();});
jQuery('li.item_welcome').css('color','white');
jQuery('a[href="https://www.cpas-yyc.com/competition/"]').append(divTriangle);
jQuery('a[href="https://www.cpas-yyc.com/competition/"]').attr('href', '#competition_placeholder');
jQuery('a[href="https://www.cpas-yyc.com/about-us/"]').attr('href', '#about_us_placeholder');
jQuery('a[href="https://www.cpas-yyc.com/activities/"]').attr('href', '#activities_placeholder');
jQuery('a[href="https://www.cpas-yyc.com/zh/%e9%97%9c%e6%96%bc%e6%88%91%e5%80%91/"]').attr('href','#about_us_placeholder');
jQuery('a[href="https://www.cpas-yyc.com/zh/%e6%af%94%e8%b3%bd/"]').attr('href','#competition_placeholder');
jQuery('a[href="https://www.cpas-yyc.com/zh/%e6%b4%bb%e5%8b%95/"]').attr('href','#activities_placeholder');
jQuery('a[href="#competition_placeholder"]').append('&nbsp; &nbsp;<i class="fa fa-arrow-circle-o-down fa-1.5x"></i>');
jQuery('a[href="#about_us_placeholder"]').append('&nbsp; &nbsp;<i class="fa fa-arrow-circle-o-down fa-1.5x"></i>');
jQuery('a[href="#activities_placeholder"]').append('&nbsp; &nbsp;<i class="fa fa-arrow-circle-o-down fa-1.5x"></i>');
jQuery('a[href="#competition_placeholder"]').click(function(){$(this).next().slideToggle();});
jQuery('a[href="#about_us_placeholder"]').click(function(){$(this).next().slideToggle();});
jQuery('a[href="#activities_placeholder"]').click(function(){$(this).next().slideToggle();});
var body = $('body');
	$('input').click(function(){
		if ($(":input#delete_button").val() == 'Delete') {
			var isGood = confirm('Are you sure you want delete this photo?');
			var post_number = $(this).attr('name');
			if (isGood) {
				$.ajax({ url: 'https://www.visorsourcing.com/wp-content/themes/hitchcock/delete_post.php',
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
