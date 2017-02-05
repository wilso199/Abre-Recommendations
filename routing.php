<?php
	
	/*
	* Copyright 2015 Hamilton City School District	
	* 		
	* This program is free software: you can redistribute it and/or modify
    * it under the terms of the GNU General Public License as published by
    * the Free Software Foundation, either version 3 of the License, or
    * (at your option) any later version.
	* 
    * This program is distributed in the hope that it will be useful,
    * but WITHOUT ANY WARRANTY; without even the implied warranty of
    * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    * GNU General Public License for more details.
	* 
    * You should have received a copy of the GNU General Public License
    * along with this program.  If not, see <http://www.gnu.org/licenses/>.
    */
    
    //Required configuration files
	require_once(dirname(__FILE__) . '/../../core/abre_verification.php'); 
	require('functions.php');
	require('permissions.php');
	
	if($pagerestrictions=="")
	{ 
		
		if(superadmin() or AdminCheckRecommended($_SESSION['useremail']))
		{    
			echo "
				'recommendations': function(name)
				{
				    $('#navigation_top').hide();
				    $('#content_holder').hide();
				    $('#loader').show();
				    $('#titletext').text('Recommendations');
				    document.title = 'Recommendations';
					$('#content_holder').load('modules/".basename(__DIR__)."/student_search.php', function() { init_page(); });
					
					//Load Navigation
					$('#navigation_top').show();
					$('#navigation_top').load('modules/".basename(__DIR__)."/main_menu.php', function() {	
						$('#navigation_top').show();
						$('.tab_1').addClass('tabmenuover');
					});
					
			    },
				'recommendations/teachers': function(name)
				{
				    $('#navigation_top').hide();
				    $('#content_holder').hide();
				    $('#loader').show();
				    $('#titletext').text('Recommendations');
				    document.title = 'Recommendations';
					$('#content_holder').load('modules/".basename(__DIR__)."/teacher_search.php', function() { init_page(); });
					
					//Load Navigation
					$('#navigation_top').show();
					$('#navigation_top').load('modules/".basename(__DIR__)."/main_menu.php', function() {	
						$('#navigation_top').show();
						$('.tab_2').addClass('tabmenuover');
					});
					
			    },";
		}
		else
		{
			echo "
				'recommendations': function(name)
				{
				    $('#navigation_top').hide();
				    $('#content_holder').hide();
				    $('#loader').show();
				    $('#titletext').text('Recommendations');
				    document.title = 'Recommendations';
					$('#content_holder').load('modules/".basename(__DIR__)."/teacher_recommendation.php', function() { init_page(); });
			    },";
		}
		
	}
	
?>