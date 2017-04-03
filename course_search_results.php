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
		
		$building=$_GET["building"];
		$building=base64_decode($building);
		
		$gradequery=NULL;
		$schoolquery=NULL;
		
		if($building=="Garfield Middle School")
		{
			$gradequery="Abre_Students.CurrentGrade='06' or Abre_Students.CurrentGrade='07'";
			$schoolquery="SchoolName='Fairwood Elementary' or SchoolName='Crawford Woods Elementary' or SchoolName='Linden Elementary' or SchoolName='Ridgeway Elementary' or SchoolName='Riverview Elementary' or SchoolName='Garfield Middle School'";
		} elseif ($building=="Wilson Middle School"){
			$gradequery="Abre_Students.CurrentGrade='06' or Abre_Students.CurrentGrade='07'";
			$schoolquery="SchoolName='Highland Elementary' or SchoolName='Brookwood Elementary' or SchoolName='Bridgeport Elementary' or SchoolName='Riverview Elementary' or SchoolName='Wilson Middle School'";
		} elseif ($building=="Hamilton Freshman School"){
			$gradequery="Abre_Students.CurrentGrade='08'";
			$schoolquery="SchoolName='Garfield Middle School' or SchoolName='Wilson Middle School'";
		} elseif ($building=="Hamilton High School"){
			$gradequery="Abre_Students.CurrentGrade='09' or Abre_Students.CurrentGrade='10' or Abre_Students.CurrentGrade='11' or Abre_Students.CurrentGrade='12'";
			$schoolquery="SchoolName='Hamilton Freshman School' or SchoolName='Hamilton High School'";
		}
		
		$counter=0;
		$query = "SELECT Count(*), recommendations_placement.Course, recommendations_placement.Level, Abre_Students.SchoolName FROM recommendations_placement LEFT JOIN Abre_Students ON recommendations_placement.StudentID=Abre_Students.StudentId where ($gradequery) and ($schoolquery) group by recommendations_placement.Course, recommendations_placement.Level";
		$dbreturn = databasequery($query);
		$resultcount=count($dbreturn);
		foreach ($dbreturn as $value)
		{
			$counter++;
			if($counter==1)
			{
				echo "<div class='row'><div class='col s12'>";
				echo "<table class='bordered'><thead><tr><th>Count</th><th>Course</th><th>Level</th></thead><tbody>";
			}
			
			$Count=htmlspecialchars($value["Count(*)"], ENT_QUOTES);
			$Course=htmlspecialchars($value["Course"], ENT_QUOTES);
			$Level=htmlspecialchars($value["Level"], ENT_QUOTES);
			echo "<tr><td>$Count</td><td>$Course</td><td>$Level</td></tr>";
			
			if($counter==$resultcount)
			{
				echo "</tbody></table>";
				echo "</div></div>";
			}
			
		}
		
		if($resultcount==0)
		{
			echo "<div class='row center-align'><div class='col s12'><h6>No placements data has been generated</h6></div></div>";
		}
		
	}

?>