// PeterY script to add caret to menu items in mobile view
jQuery(document).ready(function(){
var $j = jQuery.noConflict();
jQuery('#usp-success-message').css('color','white');
var x = jQuery('title').text();
var current_url = window.location.href;
const current_url_http = current_url.split('/')[0];
const current_url_servername = current_url.split('/')[2];
var competition_url = current_url_http + '//' + current_url_servername + '/competition/';
if (x==='即將擧行 – CPAS' || x==='Upcoming Activities – CPAS') {jQuery('div.post-container').replaceWith("<iframe src='https://calendar.google.com/calendar/embed?src=684d8jj4f0qd2d359j4geefqmg%40group.calendar.google.com&ctz=America%2FEdmonton' style='border: 0' width='800' height=600' frameborder='0' scrolling='no'></iframe>");}
 jQuery('div[class="mobile-navigation"]').find('li').find('ul').hide();
jQuery('a[href="#pll_switcher"]').click(function(){$j(this).next().hide().slideDown();});
jQuery('li.item_welcome').css('color','white');
jQuery('a[href="' + competition_url +'"]').attr('href', '#competition_placeholder');
jQuery('a[href="' + current_url_http + '//' + current_url_servername + '/about-us/' + '"]').attr('href', '#about_us_placeholder');
jQuery('a[href="' + current_url_http + '//' + current_url_servername + '/activities/' + '"]').attr('href', '#activities_placeholder');
//jQuery('a[href="http://63.142.254.125/zh/%e9%97%9c%e6%96%bc%e6%88%91%e5%80%91/"]').attr('href','#about_us_placeholder');
//jQuery('a[href="http://63.142.254.125/zh/%e6%af%94%e8%b3%bd/"]').attr('href','#competition_placeholder');
//jQuery('a[href="http://63.142.254.125/zh/%e6%b4%bb%e5%8b%95/"]').attr('href','#activities_placeholder');
jQuery('a[href="#pll_switcher"]').append('&nbsp;&nbsp;<i class="fas fa-caret-down fa-xs"></i>');
jQuery('a[href="#competition_placeholder"]').append('&nbsp;&nbsp;<i class="fas fa-caret-down fa-xs"></i>');
jQuery('a[href="#about_us_placeholder"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
jQuery('a[href="#activities_placeholder"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
jQuery('a[href="#Members_corner"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
jQuery('a[href="#2022_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
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
jQuery('a[href="#competition_placeholder"]').click(function(){$j(this).next().slideToggle();});
jQuery('a[href="#about_us_placeholder"]').click(function(){$j(this).next().slideToggle();});
jQuery('a[href="#activities_placeholder"]').click(function(){$j(this).next().slideToggle();});
jQuery('a[href="#Members_corner"]').click(function(){$j(this).next().slideToggle();});
jQuery('a[href="#2022_competitions"]').click(function(){$j(this).next().slideToggle();});
jQuery('a[href="#2021_competitions"]').click(function(){$j(this).next().slideToggle();});
jQuery('a[href="#2020_competitions"]').click(function(){$j(this).next().slideToggle();});
jQuery('a[href="#2019_competitions"]').click(function(){$j(this).next().slideToggle();});
jQuery('a[href="#2018_competitions"]').click(function(){$j(this).next().slideToggle();});
jQuery('a[href="#2017_competitions"]').click(function(){$j(this).next().slideToggle();});
jQuery('a[href="#2016_competitions"]').click(function(){$j(this).next().slideToggle();});
jQuery('a[href="#2015_competitions"]').click(function(){$j(this).next().slideToggle();});
jQuery('a[href="#2014_competitions"]').click(function(){$j(this).next().slideToggle();});
jQuery('a[href="#2013_competitions"]').click(function(){$j(this).next().slideToggle();});
jQuery('a[href="#2012_competitions"]').click(function(){$j(this).next().slideToggle();});
jQuery('a[href="#2011_competitions"]').click(function(){$j(this).next().slideToggle();});
jQuery('a[href="#2010_competitions"]').click(function(){$j(this).next().slideToggle();});
jQuery('a[href="#2009_competitions"]').click(function(){$j(this).next().slideToggle();});
$j(".post-container").on('click','#delete_button', function(){
		var isGood = confirm('Are you sure you want delete this photo?');
		var post_number = $j(this).attr('name');
		if (isGood) {
			$j.ajax({ url: 'http://63.142.254.125/wp-content/themes/hitchcock-child/delete_post.php',
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
