<?php 
	
	/*
	* Copyright (C) 2016-2017 Abre.io LLC
	*
	* This program is free software: you can redistribute it and/or modify
    * it under the terms of the Affero General Public License version 3
    * as published by the Free Software Foundation.
	*
    * This program is distributed in the hope that it will be useful,
    * but WITHOUT ANY WARRANTY; without even the implied warranty of
    * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    * GNU Affero General Public License for more details.
	*
    * You should have received a copy of the Affero General Public License
    * version 3 along with this program.  If not, see https://www.gnu.org/licenses/agpl-3.0.en.html.
    */
	
	//Required configuration files
	require_once(dirname(__FILE__) . '/../../core/abre_verification.php');
	require(dirname(__FILE__) . '/../../configuration.php');
	require_once(dirname(__FILE__) . '/../../core/abre_functions.php'); 
	require_once('functions.php');
	require_once('permissions.php');
	
	if(superadmin() or AdminCheckRecommended($_SESSION['useremail']))
	{
		
		echo "<div class='page_container page_container_limit mdl-shadow--4dp'>";
		echo "<div class='page'>";
		
			//Search
			echo "<div class='row'>";
			echo "<div class='input-field col s12'>";
				echo "<input placeholder='Enter a Teacher' id='search_teacher' type='text'>";
				echo "<label for='search_teacher' class='active'>Search</label>";
			echo "</div>";
			echo "</div>";
			
			//Loader
			echo "<div id='loadingprogress' class='row'><div class='col s12'><div class='progress' style='width:100%'><div class='indeterminate'></div></div></div></div>";
			
			//Search Results
			echo "<div id='searchsearchresults'></div>";
			
		
		echo "</div>";
		echo "</div>";
		
	}

?>

<script>
	
	$(function() 
	{
		
		$('#searchsearchresults').hide();
	    $('#loadingprogress').hide();

		//Search Delay
		var delay = (function(){
		  var timer = 0;
		  return function(callback, ms){
		    clearTimeout (timer);
		    timer = setTimeout(callback, ms);
		  };
		})();
		
    	//Student Search/Filter
    	$("#search_teacher").keyup(function()
    	{
	    	$('#searchsearchresults').hide();
	    	$('#loadingprogress').show();
				
	    	delay(function()
	    	{
		    	var search_teacher = $('#search_teacher').val();
		    	search_teacher = btoa(search_teacher);
	
				$("#searchsearchresults").load('modules/<?php echo basename(__DIR__); ?>/teacher_recommendation_results.php?teacher_search='+search_teacher, function() {
					$('#loadingprogress').hide();
					$("#searchsearchresults").show();
				});
				
			}, 500 );
		});
			
	});	
	
		
</script>