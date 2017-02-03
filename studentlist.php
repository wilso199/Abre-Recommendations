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
		
		$StaffID = GetStaffID($_SESSION['useremail']);
		$CurrentSememester=GetCurrentSemester();
	
		echo "<div class='page_container page_container_limit mdl-shadow--4dp'>";
		echo "<div class='page'>";
			
			echo "<table class='striped'>";
				echo "<thead><tr><th></th><th>Student</th><th>Current Course</th><th>Recommendation</th></tr></thead>";
				echo "<tbody>";
					
					$query = "SELECT * FROM Abre_StudentSchedules where StaffId='$StaffID' and (TermCode='$CurrentSememester' or TermCode='Year') group by StudentID order by CourseName, LastName";
					$dbreturn = databasequery($query);
					foreach ($dbreturn as $value)
					{
						$FirstName=htmlspecialchars($value["FirstName"], ENT_QUOTES);
						$LastName=htmlspecialchars($value["LastName"], ENT_QUOTES);
						$StudentID=htmlspecialchars($value["StudentID"], ENT_QUOTES);
						$CourseName=htmlspecialchars($value["CourseName"], ENT_QUOTES);
						$StudentPicture="/modules/".basename(__DIR__)."/image.php?student=$StudentID";
						$Year=date("Y");
						$Recommendation='';
						
						//Check to see if option already saved
						$query2 = "SELECT * FROM recommendations where StaffId='$StaffID' and StudentID='$StudentID' and Year='$Year'";
						$dbreturn2 = databasequery($query2);
						foreach ($dbreturn2 as $value2)
						{
							$Recommendation=htmlspecialchars($value2["Recommendation"], ENT_QUOTES);
						}
						
						echo "<tr>";
							echo "<td><img src='$StudentPicture' class='circle demoimage' style='width:40px; height:40px; margin-left:10px;'></td>";
							echo "<td>$FirstName $LastName</td>";
							echo "<td>$CourseName</td>";
							echo "<td>";
								echo "<input "; if($Recommendation==1){ echo "checked='checked'"; }; echo " class='recommend' data-level='1' data-currentcourse='$CourseName' data-studentid='$StudentID' name='Level-$StudentID' type='radio' id='Core-$StudentID' /><label for='Core-$StudentID' style='margin: 0 20px 0 0;'>Core</label>";
								
								echo "<input "; if($Recommendation==2){ echo "checked='checked'"; }; echo " class='recommend' data-level='2' data-currentcourse='$CourseName' data-studentid='$StudentID' name='Level-$StudentID' type='radio' id='CoreCP-$StudentID' /><label for='CoreCP-$StudentID' style='margin: 0 20px 0 0;'>Core/CP</label>";
								
								echo "<input "; if($Recommendation==3){ echo "checked='checked'"; }; echo " class='recommend' data-level='3' data-currentcourse='$CourseName' data-studentid='$StudentID' name='Level-$StudentID' type='radio' id='CP-$StudentID' /><label for='CP-$StudentID' style='margin: 0 20px 0 0;'>CP</label>";
								
								echo "<input "; if($Recommendation==4){ echo "checked='checked'"; }; echo " class='recommend' data-level='4' data-currentcourse='$CourseName' data-studentid='$StudentID' name='Level-$StudentID' type='radio' id='CPHonors-$StudentID' /><label for='CPHonors-$StudentID' style='margin: 0 20px 0 0;'>CP/Honors</label>";
								
								echo "<input "; if($Recommendation==5){ echo "checked='checked'"; }; echo " class='recommend' data-level='5' data-currentcourse='$CourseName' data-studentid='$StudentID' name='Level-$StudentID' type='radio' id='Honors-$StudentID' /><label for='Honors-$StudentID' style='margin: 0 20px 0 0;'>Honors</label>";
							echo "</td>";
						echo "</tr>";
					}
						
				echo "</body>";
			echo "</table>";
		
		echo "</div>";
		echo "</div>";

		
	}

?>

<script>
	
	$(function() 
	{
		
		//Save Recommendation
		$(".recommend").unbind().click(function()
		{
			var StudentID = $(this).data('studentid');
			var Level = $(this).data('level');
			var CurrentCourse = $(this).data('currentcourse');
			$.post( "modules/<?php echo basename(__DIR__); ?>/recommendation_save.php", { Student_ID: StudentID, RecLevel: Level, CourseName: CurrentCourse })
		});
			
	});	
	
		
</script>