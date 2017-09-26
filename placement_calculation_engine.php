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
		
		//Values
		$TeacherHighestScore=5;
		$StarHighestScore=5;
		
		$query = "SELECT * FROM Abre_Students where StudentID='$student_search' LIMIT 1";
		$dbreturn = databasequery($query);
		$studentfound=count($dbreturn);
		foreach ($dbreturn as $value)
		{
	
			$FirstName=htmlspecialchars($value["FirstName"], ENT_QUOTES);
			$LastName=htmlspecialchars($value["LastName"], ENT_QUOTES);
			$StudentCurrentGrade=htmlspecialchars($value["CurrentGrade"], ENT_QUOTES);
			$StudentID=htmlspecialchars($value["StudentId"], ENT_QUOTES);
			$StudentPicture="/modules/".basename(__DIR__)."/image.php?student=$StudentID";
								
			$query2 = "SELECT * FROM recommendations where StudentID='$StudentID' and StaffID!='' and Year='2017'";
			$dbreturn2 = databasequery($query2);
			$foundrecs=count($dbreturn2);
			$counter=0;;
			foreach ($dbreturn2 as $value2)
			{
				$counter++;
				
				//Set Variables
				$possiblemathpoints=0; $totalmathpoints=0;
				$possibleenglishpoints=0; $totalenglishpoints=0;
				$possiblesocialstudiespoints=0; $totalsocialstudiespoints=0;
				$possiblesciencepoints=0; $totalsciencepoints=0;
				$Recommendation_Level_Points=0;
				$Verbage="";
				$found=0;
				
				//Recommended Variables
				$TeacherRecommendationPoints=htmlspecialchars($value2["Recommendation"], ENT_QUOTES);
				$Recommendation_Course=htmlspecialchars($value2["Recommendation_Course"], ENT_QUOTES);
				$Recommendation_Level=htmlspecialchars($value2["Recommendation_Level"], ENT_QUOTES);
				$Override_Level=htmlspecialchars($value2["Override_Level"], ENT_QUOTES);
				$TeacherRecommendationID=htmlspecialchars($value2["ID"], ENT_QUOTES);
				if ($Recommendation_Level == "Core")
				{
				    $Recommendation_Level_Points=1;
				} elseif ($Recommendation_Level == "Core/CP") {
				    $Recommendation_Level_Points=2;
				} elseif ($Recommendation_Level == "CP") {
				    $Recommendation_Level_Points=3;
				} elseif ($Recommendation_Level == "CP/Honors") {
				    $Recommendation_Level_Points=4;
				} elseif ($Recommendation_Level == "Honors") {
				    $Recommendation_Level_Points=5;
				} elseif ($Recommendation_Level == "AP") {
				    $Recommendation_Level_Points=5;
				} elseif ($Recommendation_Level == "CCP") {
				    $Recommendation_Level_Points=5;
				}
				$CurrentCourse=htmlspecialchars($value2["CurrentCourse"], ENT_QUOTES);
				$StaffID=htmlspecialchars($value2["StaffID"], ENT_QUOTES);
				
				//Set up Table
				if($counter==1)
				{
					echo "<div class='row'>";
						echo "<div class='center-align'><img src='$StudentPicture' class='circle demoimage' style='width:80px; height:80px;'></div>";
						echo "<div class='col s12'><h4 class='center-align'>$FirstName $LastName</h4><h6 class='center-align'>Grade: $StudentCurrentGrade</h6></div>";
					echo "</div>";	
					echo "<div class='row'>";
					echo "<div class='col s12'>";
					echo "<table class='responsive-table striped'>";
					echo "<thead><tr>";
						echo "<th>Teacher</th>";
						echo "<th>Recommended Course</th>";
						echo "<th>Rec Level</th>";
						echo "<th>AIR</th>";
						echo "<th>Grades</th>";
						echo "<th>STAR</th>";
						echo "<th>Calculated Placement</th>";
						echo "<th>Override</th>";
					echo "</tr></thead>";
					echo "<tbody>";
				}
									
				//Look Up Teachers Name
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
					
				//Start Row Display
				echo "<tr>";
				
					//Display Teacher
					echo "<td><span id='teacher+$counter' class='pointer'>$Staff_FirstName $Staff_LastName ($StaffID)</span></td>";
					echo "<div class='mdl-tooltip mdl-tooltip--large' data-mdl-for='teacher+$counter'>$CurrentCourse</div>";
					
					//Display Recommended Course
					echo "<td>$Recommendation_Course</td>";
					
					//Display Teacher Points
					echo "<td><span id='teacherpoints+$counter' class='pointer'>$Recommendation_Level_Points</span></td>";
					echo "<div class='mdl-tooltip mdl-tooltip--large' data-mdl-for='teacherpoints+$counter'>$Recommendation_Level</div>";
					
					//Display AIR Points
					if($CourseSubject=="Math"){ 
						$found=1; 
						echo "<td><span id='airpoints+$counter' class='pointer'>$totalmathpoints</span></td>";
						echo "<div class='mdl-tooltip mdl-tooltip--large' data-mdl-for='airpoints+$counter'>$possiblemathpoints Math Points Attempted</div>";
					}
					if($CourseSubject=="Language Arts"){ 
						$found=1; 
						echo "<td><span id='airpoints+$counter' class='pointer'>$totalenglishpoints</span></td>";
						echo "<div class='mdl-tooltip mdl-tooltip--large' data-mdl-for='airpoints+$counter'>$possibleenglishpoints Language Arts Points Attempted</div>";
					}
					if($CourseSubject=="Social Studies"){ 
						$found=1; 
						echo "<td><span id='airpoints+$counter' class='pointer'>$totalsocialstudiespoints</span></td>";
						echo "<div class='mdl-tooltip mdl-tooltip--large' data-mdl-for='airpoints+$counter'>$possiblesocialstudiespoints Social Studies Points Attempted</div>";
					}
					if($CourseSubject=="Science"){ 
						$found=1; 
						echo "<td><span id='airpoints+$counter' class='pointer'>$totalsciencepoints</span></td>";
						echo "<div class='mdl-tooltip mdl-tooltip--large' data-mdl-for='airpoints+$counter'>$possiblesciencepoints Science Points Attempted</div>";
					}
					if($found==0){ echo "<td><span id='airpoints+$counter' class='pointer'>0</span></td>"; echo "<div class='mdl-tooltip mdl-tooltip--large' data-mdl-for='airpoints+$counter'>0 Possible AIR Points</div>"; }
					
					
					//Display the Grades
					echo "<td>";
						$query3 = "SELECT * FROM Abre_StudentGrades where StudentID='$StudentID' and StaffID='$StaffID' and MarkingPeriodGrade!='' and (MarkingPeriodGrade LIKE '%A%' or MarkingPeriodGrade LIKE '%B%' or MarkingPeriodGrade LIKE '%C%' or MarkingPeriodGrade LIKE '%D%' or MarkingPeriodGrade LIKE '%F%') and (MarkingPeriodCode='Qtr1' or MarkingPeriodCode='Qtr2' or MarkingPeriodCode='Qtr3' or MarkingPeriodCode='Qtr4')";
						$dbreturn3 = databasequery($query3);
						$returnedrecords=count($dbreturn3);
						$returnedgradecount=0;
						$MarkingPeriodGradestring="";
						$MarkingPeriodGradedisplay="";
						$GradeRecommendationPoints=0;
						foreach ($dbreturn3 as $value3)
						{
							$returnedgradecount++;
							$MarkingPeriodGrade=htmlspecialchars($value3["MarkingPeriodGrade"], ENT_QUOTES);
							
							if (strpos($MarkingPeriodGrade, 'A') !== false){ $GradeRecommendationPoints=$GradeRecommendationPoints+5; }
							if (strpos($MarkingPeriodGrade, 'B') !== false){ $GradeRecommendationPoints=$GradeRecommendationPoints+4; }
							if (strpos($MarkingPeriodGrade, 'C') !== false){ $GradeRecommendationPoints=$GradeRecommendationPoints+3; }
							if (strpos($MarkingPeriodGrade, 'D') !== false){ $GradeRecommendationPoints=$GradeRecommendationPoints+2; }
							if (strpos($MarkingPeriodGrade, 'F') !== false){ $GradeRecommendationPoints=$GradeRecommendationPoints+1; }
							$MarkingPeriodGradedisplay="$MarkingPeriodGradedisplay, $MarkingPeriodGrade";
							
						}
						$possiblegradepoints=$returnedrecords*5;
						echo "<span id='grades+$counter' class='pointer'>$GradeRecommendationPoints</span>";
					echo "</td>";
					$MarkingPeriodGradedisplay = substr($MarkingPeriodGradedisplay, 1);
					if($MarkingPeriodGradedisplay==""){ $MarkingPeriodGradedisplay="Grades Not Available"; }
					echo "<div class='mdl-tooltip mdl-tooltip--large' data-mdl-for='grades+$counter'>$MarkingPeriodGradedisplay</div>";
					
					//Display STAR Points
					if($CourseSubject=='Math'){ $CourseSubjectStar='Math'; }
					if($CourseSubject=='Language Arts' or $CourseSubject=='Social Studies' or $CourseSubject=='Science'){ $CourseSubjectStar='Language Arts'; }
					$query3 = "SELECT AVG(GE) FROM recommendations_star where StudentId='$StudentID' and Subject='$CourseSubjectStar'";
					$dbreturn3 = databasequery($query3);
					$stardatacount=count($dbreturn3);
					$StarGE=0;
					foreach ($dbreturn3 as $value3)
					{
						$StarGE=htmlspecialchars($value3["AVG(GE)"], ENT_QUOTES);
					}
					if($stardatacount==0)
					{ 
						echo "<td></td>";
					}
					else
					{
						//Calulate number of points from Star
						$StarRecommendationPoints=0;
						$query3 = "SELECT *  FROM `recommendations_star` WHERE `StudentId` = '$StudentID' and Subject='$CourseSubjectStar' group by `DateTaken` DESC";
						$dbreturn3 = databasequery($query3);
						$starrecommendationsforstudent=count($dbreturn3);
						if($starrecommendationsforstudent!=0)
						{
							$CurrentGradeVsStar = $StarGE-$StudentCurrentGrade;
							if($CurrentGradeVsStar>=2){ $StarRecommendationPoints=5; }
							if ($CurrentGradeVsStar>=1 && $CurrentGradeVsStar<2){ $StarRecommendationPoints=4; }
							if ($CurrentGradeVsStar>=0 && $CurrentGradeVsStar<1){ $StarRecommendationPoints=3; }
							if ($CurrentGradeVsStar>-2 && $CurrentGradeVsStar<0){ $StarRecommendationPoints=2; }
							if ($CurrentGradeVsStar<=-2){ $StarRecommendationPoints=1; }
						}
						else
						{
							$StarRecommendationPoints="";
						}
						
						echo "<td><span id='star+$counter' class='pointer'>$StarRecommendationPoints</span></td>";
						$query3 = "SELECT *  FROM `recommendations_star` WHERE `StudentId` = '$StudentID' and Subject='$CourseSubjectStar' group by `DateTaken` DESC";
						$dbreturn3 = databasequery($query3);
						$GEResult="";
						foreach ($dbreturn3 as $value3)
						{
							$GE=htmlspecialchars($value3["GE"], ENT_QUOTES);
							$DateTaken=htmlspecialchars($value3["DateTaken"], ENT_QUOTES);
							$DateTaken = substr($DateTaken, 0, -6);
							$GEResult="$GEResult $GE ($DateTaken)<br>";
						}
						$GEResult = substr($GEResult, 1);
						echo "<div class='mdl-tooltip mdl-tooltip--large' data-mdl-for='star+$counter'>$GEResult</div>";
					}
							
					//Calculated Level
					$AIRWeighting=.5;
					$possiblesubjectpoints=0;
					$AIRWeight=0;
					
					if($CourseSubject=="Math")
					{ 
						$AIRWeight=$totalmathpoints*$AIRWeighting;
						$possiblesubjectpoints=$possiblemathpoints;
					}
					if($CourseSubject=="Language Arts")
					{ 
						$AIRWeight=$totalenglishpoints*$AIRWeighting;
						$possiblesubjectpoints=$possibleenglishpoints;
					}
					if($CourseSubject=="Social Studies")
					{ 
						$AIRWeight=$totalsocialstudiespoints*$AIRWeighting;
						$possiblesubjectpoints=$possiblesocialstudiespoints;
					}
					if($CourseSubject=="Science")
					{ 
						$AIRWeight=$totalsciencepoints*$AIRWeighting;
						$possiblesubjectpoints=$possiblesciencepoints;
					}
					
					//Calculation Formula (If has Rec Level Only)
					if ($possiblesubjectpoints!=0 && $possiblegradepoints!=0 && $StarGE!=0) {
						$found=1;
						$GradeWeighting=.25;
						$StarWeighting=.20;
						$TeacherWeighting=.05;
						$TeacherWeight=$TeacherRecommendationPoints*$TeacherWeighting;
						$GradeWeight=$GradeRecommendationPoints*$GradeWeighting;
						$StarWeight=$StarRecommendationPoints*$StarWeighting;
						
						$verbagebreakdown="Teacher Rec 5%<br>AIR 50%<br>Grades 25%<br>Star 20%";
						
					    $numerator=$TeacherWeight+$AIRWeight+$GradeWeight+$StarWeight;
						$Denominator=$TeacherHighestScore*$TeacherWeighting+$possiblesubjectpoints*$AIRWeighting+$possiblegradepoints*$GradeWeighting+$StarHighestScore*$StarWeighting;
					} elseif ($possiblesubjectpoints!=0 && $possiblegradepoints!=0 && $StarGE==0) {
						$found=1;
						$GradeWeighting=.45;
						$TeacherWeighting=.05;
						$TeacherWeight=$TeacherRecommendationPoints*$TeacherWeighting;
						$GradeWeight=$GradeRecommendationPoints*$GradeWeighting;
						
						$verbagebreakdown="Teacher Rec 5%<br>AIR 50%<br>Grades 45%";
						
					    $numerator=$TeacherWeight+$AIRWeight+$GradeWeight;
						$Denominator=$TeacherHighestScore*$TeacherWeighting+$possiblesubjectpoints*$AIRWeighting+$possiblegradepoints*$GradeWeighting;
					} elseif ($possiblesubjectpoints!=0 && $possiblegradepoints==0 && $StarGE==0) {
						$found=1;
						$TeacherWeighting=.5;
						$TeacherWeight=$TeacherRecommendationPoints*$TeacherWeighting;
						
						$verbagebreakdown="Teacher Rec 50%<br>AIR 50%";
						
					    $numerator=$TeacherWeight+$AIRWeight;
						$Denominator=$TeacherHighestScore*$TeacherWeighting+$possiblesubjectpoints*$AIRWeighting;
					} elseif ($possiblesubjectpoints==0 && $possiblegradepoints!=0 && $StarGE!=0) {
						$found=1;
						$GradeWeighting=.5;
						$StarWeighting=.45;
						$TeacherWeighting=.05;
						$TeacherWeight=$TeacherRecommendationPoints*$TeacherWeighting;
						$GradeWeight=$GradeRecommendationPoints*$GradeWeighting;
						$StarWeight=$StarRecommendationPoints*$StarWeighting;
						
						$verbagebreakdown="Teacher Rec 5%<br>Grades 50%<br>Star 45%";
						
					    $numerator=$TeacherWeight+$GradeWeight+$StarWeight;
						$Denominator=$TeacherHighestScore*$TeacherWeighting+$possiblegradepoints*$GradeWeighting+$StarHighestScore*$StarWeighting;
					} elseif ($possiblesubjectpoints==0 && $possiblegradepoints!=0 && $StarGE==0) {
						$found=1;
						$GradeWeighting=.5;
						$GradeWeight=$GradeRecommendationPoints*$GradeWeighting;
						$TeacherWeighting=.5;
						$TeacherWeight=$TeacherRecommendationPoints*$TeacherWeighting;
						$verbagebreakdown="Teacher Rec 50%<br>Grades 50%";
						
					    $numerator=$TeacherWeight+$GradeWeight;
						$Denominator=$TeacherHighestScore*$TeacherWeighting+$possiblegradepoints*$GradeWeighting;
					}
					
					if($possiblesubjectpoints==0 && $possiblegradepoints==0 && $StarGE==0)
					{
						$Verbage=$Recommendation_Level;
					}
					else
					{
						$CalculatedScore=$numerator/$Denominator;
								
						$CalculatedScore=$CalculatedScore*100;
						$CalculatedScore=round($CalculatedScore);
						
						if($CalculatedScore<=60){ $Verbage="Core"; }
						if($CalculatedScore>60 && $CalculatedScore<80){ $Verbage="CP"; }
						if($CalculatedScore>=80)
						{ 
							if($Recommendation_Level=='Honors'){ 
								$Verbage="Honors"; 
							} elseif ($Recommendation_Level=='AP'){
								$Verbage="AP";
							} elseif ($Recommendation_Level=='CCP'){
								$Verbage="CCP";
							} elseif ($Recommendation_Level=="CP/Honors"){
								$Verbage="Honors";
							} elseif ($Recommendation_Level!=""){
								$Verbage="Not Found"; 
							}
						}						
					}
					
					//Check to see if level exists, if not choose next highest level
					$querylevel = "SELECT * FROM recommendations_courses where CourseName='$Recommendation_Course' and Subject!='Elective' LIMIT 1";
					$dbreturnlevel = databasequery($querylevel);
					foreach ($dbreturnlevel as $valuelevel)
					{
						$AvailableLevels=htmlspecialchars($valuelevel["Levels"], ENT_QUOTES);
						if (strpos($AvailableLevels, $Verbage) === false)
						{ 
							//Check if multiple levels exist
							if(strpos($AvailableLevels, ',') !== false)
							{
								if($CalculatedScore<=60)
								{
									$Verbage=substr($AvailableLevels, 0, strrpos($AvailableLevels.",", ","));
									$Verbage = explode(",", $Verbage, 2);
									$Verbage = $Verbage[0];
									$Verbage = str_replace(' ', '', $Verbage);
								}
								else
								{
									$Verbage=substr($AvailableLevels, strrpos($AvailableLevels, ',') + 1);
									$Verbage = str_replace(' ', '', $Verbage);
								}
							}
							else
							{
								$Verbage=$AvailableLevels;
								$Verbage = str_replace(' ', '', $Verbage);
							}
						}
						
					}
					
					//Check for CCP/CP Issue
					if($Verbage=="CP")
					{							
						$returnvalueCP=substr_count($AvailableLevels, 'CP');
						$returnvalueCCP=substr_count($AvailableLevels, 'CCP');
						
						if($returnvalueCP==1 && $returnvalueCCP==1)
						{
							$Verbage="CCP";
						}
						
					}
							
					if($found==1)
					{ 
						echo "<td><b id='verbagebreakdown+$counter' class='pointer'>$Verbage ($CalculatedScore%)</b></td>";
						echo "<div class='mdl-tooltip mdl-tooltip--large' data-mdl-for='verbagebreakdown+$counter'>$verbagebreakdown</div>";
					}
					else
					{
						echo "<td><b>N/A</b></td>";
					}
					
					//Override
					echo "<td>";
						echo "<select class='browser-default override_dropdown' data-studentid='$StudentID' data-recommendationid='$TeacherRecommendationID' data-recommendationcourse='$Recommendation_Course'>";
						
						//See what levels are available for recommended course
						$query3 = "SELECT * FROM recommendations_courses where CourseName='$Recommendation_Course' and Subject!='Elective'";
						$dbreturn3 = databasequery($query3);
						foreach ($dbreturn3 as $value3)
						{
							$Possible_Levels=htmlspecialchars($value3["Levels"], ENT_QUOTES);
							echo "<option value=''></option>";	
							
							if(strpos($Possible_Levels, 'Core') !== false)
							{
								if($Override_Level=="Core")
								{
									echo "<option value='Core' selected='selected'>Core</option>";	
								}
								else
								{
									echo "<option value='Core'>Core</option>";	
								}
							}
							if(strpos($Possible_Levels, 'CP') !== false)
							{
								
								if(strpos($Possible_Levels, 'CCP') !== false)
								{
									$returnvalue=substr_count($Possible_Levels, 'CP');
									if($returnvalue==2)
									{
										if($Override_Level=="CP")
										{
											echo "<option value='CP' selected='selected'>CP</option>";	
										}
										else
										{
											echo "<option value='CP'>CP</option>";	
										}
									}
								}
								else
								{
									if($Override_Level=="CP")
									{
										echo "<option value='CP' selected='selected'>CP</option>";	
									}
									else
									{
										echo "<option value='CP'>CP</option>";	
									}
								}
							}
							if(strpos($Possible_Levels, 'Honors') !== false)
							{
								if($Override_Level=="Honors")
								{
									echo "<option value='Honors' selected='selected'>Honors</option>";	
								}
								else
								{
									echo "<option value='Honors'>Honors</option>";	
								}
							}
							if(strpos($Possible_Levels, 'AP') !== false)
							{
								if($Override_Level=="AP")
								{
									echo "<option value='AP' selected='selected'>AP</option>";	
								}
								else
								{
									echo "<option value='AP'>AP</option>";	
								}
							}
							if(strpos($Possible_Levels, 'CCP') !== false)
							{
								if($Override_Level=="CCP")
								{
									echo "<option value='CCP' selected='selected'>CCP</option>";	
								}
								else
								{
									echo "<option value='CCP'>CCP</option>";	
								}
							}
							
						}	
						
						
						//Save Final Placement to Database
						SavePlacement($StudentID, "$FirstName $LastName", $StudentCurrentGrade, "$Staff_FirstName $Staff_LastName", $Recommendation_Level, $Recommendation_Course, $Verbage, $CalculatedScore);
								
							
						echo "</select>";
					echo "</td>";
							
					if($counter==$foundrecs)
					{
						echo "</tr></tbody>";
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
											
								$query2 = "SELECT * FROM recommendations_courses where Subject='Elective' order by CourseName";
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