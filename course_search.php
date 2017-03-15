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
					echo "<select class='browser-default' id='building' name='building'>";
						echo "<option value='' disabled selected>Choose a Building</option>";
					    echo "<option value='Garfield Middle School'>Garfield Middle School</option>";
					    echo "<option value='Wilson Middle School'>Wilson Middle School</option>";
					    echo "<option value='Hamilton Freshman School'>Hamilton Freshman School</option>";
					    echo "<option value='Hamilton High School'>Hamilton High School</option>";
					echo "</select>";
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
		
		//Save Course Recommendation
		$("#building, #subject, #level").change(function()
		{
			
			var building = $("#building").val();
			
			if(building)
			{
				
				building = btoa(building);
				
				$('#loadingprogress').show();
				
				$('#searchsearchresults').load('modules/<?php echo basename(__DIR__); ?>/course_search_results.php?building='+building, function()
				{ 
					$('#loadingprogress').hide();
					$('#searchsearchresults').show();
				});
			}
			
		});
			
	});	
	
		
</script>