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
	
	if($pagerestrictions=="")
	{
		
		if(isset($_GET["teacher_search"]))
		{ 
			$StaffID=$_GET["teacher_search"]; 
			$StaffID=base64_decode($StaffID); 
		}	
		else
		{
			$StaffID = GetStaffIDRecommended($_SESSION['useremail']);
		}
		
		$CurrentSememester=GetCurrentSemesterRecommended();
					
		$query = "SELECT * FROM Abre_StudentSchedules where StaffId='$StaffID' and (TermCode='$CurrentSememester' or TermCode='Year') group by StudentID order by CourseName, LastName";
		$dbreturn = databasequery($query);
		$totalstudents = count($dbreturn);
		$counter=0;
		foreach ($dbreturn as $value)
		{
			$counter++;
			if($counter==1)
			{
				echo "<table class='striped'>";
				echo "<thead><tr>";
				//echo "<th></th>";
				echo "<th>Student</th><th>Current Course</th><th>Course</th><th>Level</th></tr></thead>";
				echo "<tbody>";
			}
						
			$FirstName=htmlspecialchars($value["FirstName"], ENT_QUOTES);
			$LastName=htmlspecialchars($value["LastName"], ENT_QUOTES);
			$StudentID=htmlspecialchars($value["StudentID"], ENT_QUOTES);
			$CourseName=htmlspecialchars($value["CourseName"], ENT_QUOTES);
			$StudentPicture="/modules/".basename(__DIR__)."/image.php?student=$StudentID";
			$Year=date("Y");
			$Recommendation='';
			$Recommendation_Course='';
			$Recommendation_Level='';
						
			//Check to see if option already saved
			$query2 = "SELECT * FROM recommendations where StaffId='$StaffID' and StudentID='$StudentID' and Year='$Year'";
			$dbreturn2 = databasequery($query2);
			foreach ($dbreturn2 as $value2)
			{
				$Recommendation=htmlspecialchars($value2["Recommendation"], ENT_QUOTES);
				$Recommendation_Course=htmlspecialchars($value2["Recommendation_Course"], ENT_QUOTES);
				$Recommendation_Level=htmlspecialchars($value2["Recommendation_Level"], ENT_QUOTES);
			}
						
			echo "<tr>";
				//echo "<td><img src='$StudentPicture' class='circle demoimage' style='width:40px; height:40px; margin-left:10px;'></td>";
				echo "<td>$FirstName $LastName<br>$StudentID</td>";
				echo "<td width='120px;' class='truncate'>$CourseName</td>";
				echo "<td width='120px;'>";
						include "dropdown_course.php";
				echo "</td>";
							
				echo "<td width='700px;' id='radio_$StudentID'>";
						include "radio_levels.php";
				echo "</td>";
			echo "</tr>";
						
			if($counter==$totalstudents)
			{
				echo "</body>";
				echo "</table>";
			}
		}
					
		if($totalstudents==0)
		{
			echo "<div class='row center-align'><div class='col s12'><h6>You have no students on your roster</h6></div></div>";
		}

		
	}

?>

<script>
	
	$(function() 
	{
		
		//Save Course Recommendation
		$(".recommend_dropdown ").change(function()
		{
			var StudentID = $(this).data('studentid');
			var RadioDiv='#radio_'+StudentID;
			var CurrentCourse = $(this).data('currentcourse');
			var RecommendedCourse = $(this).val();
			var StaffID = "<?php echo $StaffID; ?>";
			
			//Save Dropdown
			$.post( "modules/<?php echo basename(__DIR__); ?>/recommendation_save.php", { Student_ID: StudentID, RecCourse: RecommendedCourse, CourseName: CurrentCourse, Staff_ID: StaffID  })
			
			//Update Levels
			$.post( "modules/<?php echo basename(__DIR__); ?>/radio_levels.php", { Student_ID: StudentID, RecCourse: RecommendedCourse })
			.done(function(data) {
				$(RadioDiv).html(data);
  			});
			
		});
		
		//Save Level Recommendation
		$(document).off("click").on("click", ".recommend", function ()
		{
			var StudentID = $(this).data('studentid');
			var LevelValue = $(this).data('levelvalue');
			var LevelName= $(this).data('levelname');
			var CurrentCourse = $(this).data('currentcourse');
			var StaffID = "<?php echo $StaffID; ?>";
			$.post( "modules/<?php echo basename(__DIR__); ?>/recommendation_save.php", { Student_ID: StudentID, RecLevelValue: LevelValue, RecLevelName: LevelName, CourseName: CurrentCourse, Staff_ID: StaffID })
		});	
			
	});	
	
		
</script>