// PeterY script to add caret to menu items in mobile view
//modified june 2023
jQuery(document).ready(function(){
		$ = jQuery.noConflict();
		const url_val = window.location.href;
		//regex that uses groups match to find the server name of the URL
		const regex = new RegExp(`((^[h].*?[:])(\/\/)(.*?)(\/))`, "g");
		var match = regex.exec(url_val);
		if (match) {
			var serverName = match[4];
			var http_https = match[3];
		}
$('#usp-success-message').css('color','white');
var x = $('title').text();
var current_url = window.location.href;
const current_url_http = current_url.split('/')[0];
const current_url_servername = current_url.split('/')[2];
var competition_url = current_url_http + '//' + current_url_servername + '/competition/';
if (x==='即將擧行 – CPAS' || x==='Upcoming Activities – CPAS') {$('div.post-container').replaceWith("<iframe src='https://calendar.google.com/calendar/embed?src=684d8jj4f0qd2d359j4geefqmg%40group.calendar.google.com&ctz=America%2FEdmonton' style='border: 0' width='800' height=600'></iframe>");}
 $('div[class="mobile-navigation"]').find('li').find('ul').hide();
$('a[href="#pll_switcher"]').click(function(){$(this).next().hide().slideDown();});
$('li.item_welcome').css('color','white');
$('a[href="' + competition_url +'"]').attr('href', '#competition_placeholder');
$('a[href="' + current_url_http + '//' + current_url_servername + '/about-us/' + '"]').attr('href', '#about_us_placeholder');
$('a[href="' + current_url_http + '//' + current_url_servername + '/activities/' + '"]').attr('href', '#activities_placeholder');
//$('a[href="http://63.142.254.125/zh/%e9%97%9c%e6%96%bc%e6%88%91%e5%80%91/"]').attr('href','#about_us_placeholder');
//$('a[href="http://63.142.254.125/zh/%e6%af%94%e8%b3%bd/"]').attr('href','#competition_placeholder');
//$('a[href="http://63.142.254.125/zh/%e6%b4%bb%e5%8b%95/"]').attr('href','#activities_placeholder');
$('a[href="#pll_switcher"]').append('&nbsp;&nbsp;<i class="fas fa-caret-down fa-xs"></i>');
$('a[href="#competition_placeholder"]').append('&nbsp;&nbsp;<i class="fas fa-caret-down fa-xs"></i>');
$('a[href="#about_us_placeholder"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
$('a[href="#activities_placeholder"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
$('a[href="#Members_corner"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
$('a[href="#2024_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
$('a[href="#2023_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
$('a[href="#2022_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
$('a[href="#2021_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
$('a[href="#2020_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
$('a[href="#2019_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
$('a[href="#2018_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
$('a[href="#2017_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
$('a[href="#2016_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
$('a[href="#2015_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
$('a[href="#2014_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
$('a[href="#2013_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
$('a[href="#2012_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
$('a[href="#2011_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
$('a[href="#2010_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
$('a[href="#2009_competitions"]').append('&nbsp; &nbsp;<i class="fas fa-caret-down fa-xs"></i>');
$('a[href="#competition_placeholder"]').click(function(){$(this).next().slideToggle();});
$('a[href="#about_us_placeholder"]').click(function(){$(this).next().slideToggle();});
$('a[href="#activities_placeholder"]').click(function(){$(this).next().slideToggle();});
$('a[href="#Members_corner"]').click(function(){$(this).next().slideToggle();});
$('a[href="#2024_competitions"]').click(function(){$(this).next().slideToggle();});
$('a[href="#2023_competitions"]').click(function(){$(this).next().slideToggle();});
$('a[href="#2022_competitions"]').click(function(){$(this).next().slideToggle();});
$('a[href="#2021_competitions"]').click(function(){$(this).next().slideToggle();});
$('a[href="#2020_competitions"]').click(function(){$(this).next().slideToggle();});
$('a[href="#2019_competitions"]').click(function(){$(this).next().slideToggle();});
$('a[href="#2018_competitions"]').click(function(){$(this).next().slideToggle();});
$('a[href="#2017_competitions"]').click(function(){$(this).next().slideToggle();});
$('a[href="#2016_competitions"]').click(function(){$(this).next().slideToggle();});
$('a[href="#2015_competitions"]').click(function(){$(this).next().slideToggle();});
$('a[href="#2014_competitions"]').click(function(){$(this).next().slideToggle();});
$('a[href="#2013_competitions"]').click(function(){$(this).next().slideToggle();});
$('a[href="#2012_competitions"]').click(function(){$(this).next().slideToggle();});
$('a[href="#2011_competitions"]').click(function(){$(this).next().slideToggle();});
$('a[href="#2010_competitions"]').click(function(){$(this).next().slideToggle();});
$('a[href="#2009_competitions"]').click(function(){$(this).next().slideToggle();});
$(".post-container").on('click','#delete_button', function(){
		var isGood = confirm('Are you sure you want delete this photo?');
		var post_number = $(this).attr('name');
		if (isGood) {
			$.ajax({ url: http_https + '//' + serverName +'/wp-content/themes/hitchcock-child/delete_post.php',
				data: {delete_var: post_number},
				type: 'post',
				success: function() {
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
