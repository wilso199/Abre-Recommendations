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
		
		if(isset($_GET["student_search"])){ $student_search=$_GET["student_search"]; $student_search=base64_decode($student_search); }
		
		include "placement_calculation_engine.php";
				
	}

?>

<script>
	
	$(function() 
	{
		
		//Save Course Recommendation
		$(".recommend_elective_dropdown ").change(function()
		{
			var StudentID = $(this).data('studentid');
			var RecommendedElective = $(this).val();
			
			//Save Dropdown
			$.post( "modules/<?php echo basename(__DIR__); ?>/recommendation_elective_save.php", { Student_ID: StudentID, Elective_Number: ElectiveOrder, Elective_Name: RecommendedElective })
			
		});
		
		//Save Course Override
		$(".override_dropdown ").change(function()
		{
			var StudentID = $(this).data('studentid');
			var RecommendationID = $(this).data('recommendationid');
			var RecommendationCourse = $(this).data('recommendationcourse');
			var RecommendedLevel = $(this).val();
			
			//Save Dropdown
			$.post( "modules/<?php echo basename(__DIR__); ?>/recommendation_elective_override.php", { Student_ID: StudentID, Recommended_Level: RecommendedLevel, Recommendation_ID: RecommendationID, Recommended_Course: RecommendationCourse })
		});
			
	});	
	
		
</script>