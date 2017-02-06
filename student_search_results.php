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
		
		if(isset($_GET["student_search"])){ $student_search=$_GET["student_search"]; $student_search=base64_decode($student_search); }	
		
		$query = "SELECT * FROM Abre_Students where StudentID='$student_search' LIMIT 1";
		$dbreturn = databasequery($query);
		$studentfound=count($dbreturn);
		foreach ($dbreturn as $value)
		{
	
			$FirstName=htmlspecialchars($value["FirstName"], ENT_QUOTES);
			$LastName=htmlspecialchars($value["LastName"], ENT_QUOTES);
			$StudentID=htmlspecialchars($value["StudentId"], ENT_QUOTES);
			$StudentPicture="/modules/".basename(__DIR__)."/image.php?student=$StudentID";
								
						$query2 = "SELECT * FROM recommendations where StudentID='$StudentID' and StaffID!='' and Year='2017'";
						$dbreturn2 = databasequery($query2);
						$foundrecs=count($dbreturn2);
						$counter=0;;
						foreach ($dbreturn2 as $value2)
						{
							$counter++;
							if($counter==1)
							{
								echo "<div class='row'>";
									echo "<div class='center-align'><img src='$StudentPicture' class='circle demoimage' style='width:80px; height:80px;'></div>";
									echo "<div class='col s12'><h4 class='center-align'>$FirstName $LastName</h4></div>";
									echo "<div class='col s12'><h4>Staff Recommendations</h4></div>";
								echo "</div>";	
								echo "<div class='row'>";
								echo "<div class='col s12'>";
								echo "<table class='responsive-table striped'>";
								echo "<thead><tr>";
									echo "<th>Teacher</th><th>Current Course</th><th>Recommended Course</th><th>Recommended Level</th><th>AIR Points</th><th>Calculated Level</th>";
								echo "</tr></thead>";
								echo "<tbody>";
							}
							
							$TeacherRecommendationPoints=htmlspecialchars($value2["Recommendation"], ENT_QUOTES);
							$Recommendation_Course=htmlspecialchars($value2["Recommendation_Course"], ENT_QUOTES);
							$Recommendation_Level=htmlspecialchars($value2["Recommendation_Level"], ENT_QUOTES);
							$CurrentCourse=htmlspecialchars($value2["CurrentCourse"], ENT_QUOTES);
							$StaffID=htmlspecialchars($value2["StaffID"], ENT_QUOTES);
									
							//Look up teachers name
							$query3 = "SELECT * FROM Abre_Staff where StaffID='$StaffID'";
							$dbreturn3 = databasequery($query3);
							foreach ($dbreturn3 as $value3)
							{
								$Staff_FirstName=htmlspecialchars($value3["FirstName"], ENT_QUOTES);
								$Staff_LastName=htmlspecialchars($value3["LastName"], ENT_QUOTES);
							}
							
							//Find Subject of Recommended Course
							$query3 = "SELECT * FROM recommendations_courses where CourseName='$Recommendation_Course'";
							$dbreturn3 = databasequery($query3);
							$CourseSubject="";
							foreach ($dbreturn3 as $value3)
							{
								$CourseSubject=htmlspecialchars($value3["Subject"], ENT_QUOTES);
							}
							
							//Find All AIR Scores for Given Student
							$query3 = "SELECT * FROM Abre_StudentAssessments where StudentID='$StudentID' group by AssessmentArea";
							$dbreturn3 = databasequery($query3);
							$possiblemathpoints=0; $totalmathpoints=0;
							$possibleenglishpoints=0; $totalenglishpoints=0;
							$possiblesocialstudiespoints=0; $totalsocialstudiespoints=0;
							$possiblesciencepoints=0; $totalsciencepoints=0;
							foreach ($dbreturn3 as $value3)
							{
								$AssessmentArea=htmlspecialchars($value3["AssessmentArea"], ENT_QUOTES);
								$PerformanceLevel=htmlspecialchars($value3["PerformanceLevel"], ENT_QUOTES);
								
								//Math
								if($CourseSubject=="Math" && $AssessmentArea=="03 Mathematics"){ $totalmathpoints=$totalmathpoints+$PerformanceLevel; $possiblemathpoints=$possiblemathpoints+5; }
								if($CourseSubject=="Math" && $AssessmentArea=="04 Mathematics"){ $totalmathpoints=$totalmathpoints+$PerformanceLevel; $possiblemathpoints=$possiblemathpoints+5; }
								if($CourseSubject=="Math" && $AssessmentArea=="05 Mathematics"){ $totalmathpoints=$totalmathpoints+$PerformanceLevel; $possiblemathpoints=$possiblemathpoints+5; }
								if($CourseSubject=="Math" && $AssessmentArea=="06 Mathematics"){ $totalmathpoints=$totalmathpoints+$PerformanceLevel; $possiblemathpoints=$possiblemathpoints+5; }
								if($CourseSubject=="Math" && $AssessmentArea=="07 Mathematics"){ $totalmathpoints=$totalmathpoints+$PerformanceLevel; $possiblemathpoints=$possiblemathpoints+5; }
								if($CourseSubject=="Math" && $AssessmentArea=="08 Mathematics"){ $totalmathpoints=$totalmathpoints+$PerformanceLevel; $possiblemathpoints=$possiblemathpoints+5; }
								if($CourseSubject=="Math" && $AssessmentArea=="Algebra 1"){ $totalmathpoints=$totalmathpoints+$PerformanceLevel; $possiblemathpoints=$possiblemathpoints+5; }
								if($CourseSubject=="Math" && $AssessmentArea=="Geometry"){ $totalmathpoints=$totalmathpoints+$PerformanceLevel; $possiblemathpoints=$possiblemathpoints+5; }
								if($CourseSubject=="Math" && $AssessmentArea=="Mathematics1"){ $totalmathpoints=$totalmathpoints+$PerformanceLevel; $possiblemathpoints=$possiblemathpoints+5; }
								
								//English
								if($CourseSubject=="Language Arts" && $AssessmentArea=="Grade 3 Reading Promotion"){ $totalenglishpoints=$totalenglishpoints+$PerformanceLevel; $possibleenglishpoints=$possibleenglishpoints+5; }
								if($CourseSubject=="Language Arts" && $AssessmentArea=="03 English Language Arts"){ $totalenglishpoints=$totalenglishpoints+$PerformanceLevel; $possibleenglishpoints=$possibleenglishpoints+5; }
								if($CourseSubject=="Language Arts" && $AssessmentArea=="04 English Language Arts"){ $totalenglishpoints=$totalenglishpoints+$PerformanceLevel; $possibleenglishpoints=$possibleenglishpoints+5; }
								if($CourseSubject=="Language Arts" && $AssessmentArea=="05 English Language Arts"){ $totalenglishpoints=$totalenglishpoints+$PerformanceLevel; $possibleenglishpoints=$possibleenglishpoints+5; }
								if($CourseSubject=="Language Arts" && $AssessmentArea=="06 English Language Arts"){ $totalenglishpoints=$totalenglishpoints+$PerformanceLevel; $possibleenglishpoints=$possibleenglishpoints+5; }
								if($CourseSubject=="Language Arts" && $AssessmentArea=="07 English Language Arts"){ $totalenglishpoints=$totalenglishpoints+$PerformanceLevel; $possibleenglishpoints=$possibleenglishpoints+5; }
								if($CourseSubject=="Language Arts" && $AssessmentArea=="08 English Language Arts"){ $totalenglishpoints=$totalenglishpoints+$PerformanceLevel; $possibleenglishpoints=$possibleenglishpoints+5; }
								if($CourseSubject=="Language Arts" && $AssessmentArea=="English Language Arts 1"){ $totalenglishpoints=$totalenglishpoints+$PerformanceLevel; $possibleenglishpoints=$possibleenglishpoints+5; }
								if($CourseSubject=="Language Arts" && $AssessmentArea=="English Language Arts 2"){ $totalenglishpoints=$totalenglishpoints+$PerformanceLevel; $possibleenglishpoints=$possibleenglishpoints+5; }	
								
								//Social Studies
								if($CourseSubject=="Social Studies" && $AssessmentArea=="04 Social Studies"){ $totalsocialstudiespoints=$totalsocialstudiespoints+$PerformanceLevel; $possiblesocialstudiespoints=$possiblesocialstudiespoints+5; }
								if($CourseSubject=="Social Studies" && $AssessmentArea=="06 Social Studies"){ $totalsocialstudiespoints=$totalsocialstudiespoints+$PerformanceLevel; $possiblesocialstudiespoints=$possiblesocialstudiespoints+5; }
								if($CourseSubject=="Social Studies" && $AssessmentArea=="American / United States Government"){ $totalsocialstudiespoints=$totalsocialstudiespoints+$PerformanceLevel; $possiblesocialstudiespoints=$possiblesocialstudiespoints+5; }
								if($CourseSubject=="Social Studies" && $AssessmentArea=="American / United States History"){ $totalsocialstudiespoints=$totalsocialstudiespoints+$PerformanceLevel; $possiblesocialstudiespoints=$possiblesocialstudiespoints+5; }
								
								//Science
								if($CourseSubject=="Science" && $AssessmentArea=="05 Science"){ $totalsciencepoints=$totalsciencepoints+$PerformanceLevel; $possiblesciencepoints=$possiblesciencepoints+5; }
								if($CourseSubject=="Science" && $AssessmentArea=="08 Science"){ $totalsciencepoints=$totalsciencepoints+$PerformanceLevel; $possiblesciencepoints=$possiblesciencepoints+5; }
								if($CourseSubject=="Science" && $AssessmentArea=="Biology"){ $totalsciencepoints=$totalsciencepoints+$PerformanceLevel; $possiblesciencepoints=$possiblesciencepoints+5; }
								if($CourseSubject=="Science" && $AssessmentArea=="Physical Sciences"){ $totalsciencepoints=$totalsciencepoints+$PerformanceLevel; $possiblesciencepoints=$possiblesciencepoints+5; }
															
								
							}							
							
							$found=0;
							echo "<tr><td>$Staff_FirstName $Staff_LastName<br>$StaffID</td><td>$CurrentCourse</td><td>$Recommendation_Course</td><td>$Recommendation_Level</td>";
							if($CourseSubject=="Math"){ $found=1; echo "<td>$totalmathpoints/$possiblemathpoints</td>"; }
							if($CourseSubject=="Language Arts"){ $found=1; echo "<td>$totalenglishpoints/$possibleenglishpoints</td>"; }
							if($CourseSubject=="Social Studies"){ $found=1; echo "<td>$totalsocialstudiespoints/$possiblesocialstudiespoints</td>"; }
							if($CourseSubject=="Science"){ $found=1; echo "<td>$totalsciencepoints/$possiblesciencepoints</td>"; }
							if($found==0){ echo "<td>N/A</td>"; }
							
							
							//Calculated Level
							$TeacherWeight=$TeacherRecommendationPoints*.5;
							if($CourseSubject=="Math")
							{ 
								$AIRWeight=$totalmathpoints*.5;
								$Denominator=(5*.5+$possiblemathpoints*.5);
								$CalculatedScore=($TeacherWeight+$AIRWeight)/$Denominator;
							}
							if($CourseSubject=="Language Arts")
							{ 
								$AIRWeight=$totalenglishpoints*.5;
								$Denominator=(5*.5+$possibleenglishpoints*.5);
								$CalculatedScore=($TeacherWeight+$AIRWeight)/$Denominator;
							}
							if($CourseSubject=="Social Studies")
							{ 
								$AIRWeight=$totalsocialstudiespoints*.5;
								$Denominator=(5*.5+$possiblesocialstudiespoints*.5);
								$CalculatedScore=($TeacherWeight+$AIRWeight)/$Denominator;
							}
							if($CourseSubject=="Science")
							{ 
								$AIRWeight=$totalsciencepoints*.5;
								$Denominator=(5*.5+$possiblesciencepoints*.5);
								$CalculatedScore=($TeacherWeight+$AIRWeight)/$Denominator;
							}
							
							$CalculatedScore=$CalculatedScore*100;
							$CalculatedScore=round($CalculatedScore);
							
							if($CalculatedScore<=20){ $Verbage="Core"; }
							if($CalculatedScore>20 && $CalculatedScore<=40){ $Verbage="Core/CP"; }
							if($CalculatedScore>40 && $CalculatedScore<=60){ $Verbage="CP"; }
							if($CalculatedScore>60 && $CalculatedScore<=80){ $Verbage="CP/Honors"; }
							if($CalculatedScore>80 && $CalculatedScore<=100){ $Verbage="Honors/AP/CCP"; }
							
							if($found==1)
							{ 
								echo "<td><b>$Verbage ($CalculatedScore%)</b></td></tr>";
							}
							else
							{
								echo "<td><b>$Verbage</b></td></tr>";
							}
							
							if($counter==$foundrecs)
							{
								echo "</tbody>";
								echo "</table>";
								echo "</div>";
							}

						}
								
						if($foundrecs!=0)
						{ 
							//Add Electives
							echo "<div class='row'>";
								echo "<div class='col s12'><h4>Electives</h4></div>";
								
								echo "<div class='col s12'>";
									$Year=date("Y");
									for ($x = 1; $x <= 8; $x++)
									{
										echo "<select class='browser-default recommend_elective_dropdown' data-studentid='$StudentID' data-electiveorder='$x'>";
											echo "<option value=''></option>";
											
										
											//Check to see if option already saved
											$Recommendation_Course='';
											$query3 = "SELECT * FROM recommendations where StaffId='' and Recommendation_Level='Elective' and StudentID='$StudentID' and Recommendation='$x' and Year='$Year'";
											$dbreturn3 = databasequery($query3);
											foreach ($dbreturn3 as $value3)
											{
												$Recommendation_Course=htmlspecialchars($value3["Recommendation_Course"], ENT_QUOTES);
												echo "<option value='$Recommendation_Course' selected='selected'>$Recommendation_Course</option>";
											}
											
											$query2 = "SELECT * FROM recommendations_courses where Subject='Elective'";
											$dbreturn2 = databasequery($query2);
											foreach ($dbreturn2 as $value2)
											{
												
												$ElectiveCourseName=htmlspecialchars($value2["CourseName"], ENT_QUOTES);
												echo "<option value='$ElectiveCourseName'>$ElectiveCourseName</option>";
											}
											
										echo "</select>";
										echo "<br>";
									} 
								echo "</div>";	
								
							echo "</div>";	
						}
						else
						{
							echo "<h5 class='center-align'>No recommendations have been made for $FirstName $LastName</h5>";
						}
					
			echo "</div>";
				
		}
		
		if($studentfound==0){ echo "<h5 class='center-align'>Student Not Found</h5>"; }
				
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
			var ElectiveOrder = $(this).data('electiveorder');
			
			//Save Dropdown
			$.post( "modules/<?php echo basename(__DIR__); ?>/recommendation_elective_save.php", { Student_ID: StudentID, Elective_Number: ElectiveOrder, Elective_Name: RecommendedElective })
			
		});
			
	});	
	
		
</script>