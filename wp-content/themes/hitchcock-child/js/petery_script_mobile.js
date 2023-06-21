jQuery(document).ready(function(){
$ = jQuery.noConflict();
var bodyClasses = jQuery('body').attr('class');
jQuery('#usp-success-message').css('color','white');
var x = jQuery('title').text();
if (x=='即將擧行 – CPAS' || x=='Upcoming Activities – CPAS') {jQuery("div.post-container").replaceWith("<iframe src='https://calendar.google.com/calendar/embed?src=684d8jj4f0qd2d359j4geefqmg%40group.calendar.google.com&ctz=America%2FEdmonton' style='border: 0' width='800' height=600' frameborder='0' scrolling='no'></iframe>");}
 jQuery('div[class="mobile-navigation"]').find('li').find('ul').hide();
jQuery('a[href="#pll_switcher"]').click(function(){$(this).next().hide().slideDown();});
jQuery('li.item_welcome').css('color','white');
jQuery('a[href="http://63.142.254.125/competition/"]').attr('href', '#competition_placeholder');
jQuery('a[href="http://63.142.254.125/about-us/"]').attr('href', '#about_us_placeholder');
jQuery('a[href="http://63.142.254.125/activities/"]').attr('href', '#activities_placeholder');
jQuery('a[href="http://63.142.254.125/zh/%e9%97%9c%e6%96%bc%e6%88%91%e5%80%91/"]').attr('href','#about_us_placeholder');
jQuery('a[href="http://63.142.254.125/zh/%e6%af%94%e8%b3%bd/"]').attr('href','#competition_placeholder');
jQuery('a[href="http://63.142.254.125/zh/%e6%b4%bb%e5%8b%95/"]').attr('href','#activities_placeholder');
jQuery('a[href="#pll_switcher"]').append('&nbsp;&nbsp;<i class="fas fa-caret-down fa-xs"></i>');
$('a[href="#competition_placeholder"]').append('&nbsp;&nbsp;<i class="fas fa-caret-down fa-xs"></i>');
jQuery('a[href="#about_us_placeholder"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
jQuery('a[href="#activities_placeholder"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
jQuery('a[href="#Members_corner"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
jQuery('a[href="#2021_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
jQuery('a[href="#2020_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
jQuery('a[href="#2019_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
jQuery('a[href="#2018_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
jQuery('a[href="#2017_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
jQuery('a[href="#2016_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
jQuery('a[href="#2015_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
jQuery('a[href="#2014_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
jQuery('a[href="#2013_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
jQuery('a[href="#2012_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
jQuery('a[href="#2011_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
jQuery('a[href="#2010_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
jQuery('a[href="#2009_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
jQuery('a[href="#competition_placeholder"]').click(function(){$(this).next().slideToggle();});
jQuery('a[href="#about_us_placeholder"]').click(function(){$(this).next().slideToggle();});
jQuery('a[href="#activities_placeholder"]').click(function(){$(this).next().slideToggle();});
jQuery('a[href="#Members_corner"]').click(function(){$(this).next().slideToggle();});
jQuery('a[href="#2021_competitions"]').click(function(){$(this).next().slideToggle();});
jQuery('a[href="#2020_competitions"]').click(function(){$(this).next().slideToggle();});
jQuery('a[href="#2019_competitions"]').click(function(){$(this).next().slideToggle();});
jQuery('a[href="#2018_competitions"]').click(function(){$(this).next().slideToggle();});
jQuery('a[href="#2017_competitions"]').click(function(){$(this).next().slideToggle();});
jQuery('a[href="#2016_competitions"]').click(function(){$(this).next().slideToggle();});
jQuery('a[href="#2015_competitions"]').click(function(){$(this).next().slideToggle();});
jQuery('a[href="#2014_competitions"]').click(function(){$(this).next().slideToggle();});
jQuery('a[href="#2013_competitions"]').click(function(){$(this).next().slideToggle();});
jQuery('a[href="#2012_competitions"]').click(function(){$(this).next().slideToggle();});
jQuery('a[href="#2011_competitions"]').click(function(){$(this).next().slideToggle();});
jQuery('a[href="#2010_competitions"]').click(function(){$(this).next().slideToggle();});
jQuery('a[href="#2009_competitions"]').click(function(){$(this).next().slideToggle();});
$(".post-container").on('click','#delete_button', function(){
		var isGood = confirm('Are you sure you want delete this photo?');
		var post_number = $(this).attr('name');
		if (isGood) {
			$.ajax({ url: 'http://63.142.254.125/wp-content/themes/hitchcock-child/delete_post.php',
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
);
}
);
