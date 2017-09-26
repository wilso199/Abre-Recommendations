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
	require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');	
	require_once(dirname(__FILE__) . '/../../core/abre_functions.php');	
	require_once('permissions.php');

	if($pagerestrictions=="")
	{
		
		//Save Placement
		function SavePlacement($StudentID, $StudentName, $CurrentGrade, $Teacher, $Recommendation_Level, $Recommendation_Course, $Verbage, $CalculatedPercentage){
			require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
			
			//Given the Course and Level, find the course code
			$query = "SELECT * FROM recommendations_codes where Course = '$Recommendation_Course' and Level = '$Verbage'";
			$dbreturn = databasequery($query);
			$CourseCode="";
			foreach ($dbreturn as $value)
			{ 
				$CourseCode=$value["CourseCode"];
			}
				
			mysqli_query($db, "DELETE FROM recommendations_placement where StudentID='$StudentID' and Course='$Recommendation_Course'") or die (mysqli_error($db));
			$stmt = $db->stmt_init();
			$sql = "INSERT INTO recommendations_placement (StudentID, StudentName, CurrentGrade, Teacher, Recommendation_Level, Course, Level, CalculatedPercentage, CourseCode) VALUES ('$StudentID', '$StudentName', '$CurrentGrade', '$Teacher', '$Recommendation_Level', '$Recommendation_Course', '$Verbage', '$CalculatedPercentage', '$CourseCode');";
			$stmt->prepare($sql);
			$stmt->execute();
			$stmt->close();
		}
		
		//Get Primary Subject of Teacher
		function GetTeacherSubjectbyStaffID($StaffID){
			
			$StaffID = strtoupper($StaffID);
			
			$query = "SELECT EMail1 FROM Abre_Staff where StaffID = '$StaffID' LIMIT 1";
			$dbreturn = databasequery($query);
			foreach ($dbreturn as $value)
			{ 
				$email=htmlspecialchars($value["EMail1"], ENT_QUOTES);
			}
			
			$email=encrypt($email, "");
			$query = "SELECT subject FROM directory where email = '$email' LIMIT 1";
			$dbreturn = databasequery($query);
			foreach ($dbreturn as $value)
			{ 
				$subject=htmlspecialchars($value["subject"], ENT_QUOTES);
				$subject=stripslashes(htmlspecialchars(decrypt($subject, ""), ENT_QUOTES));
				return $subject;
			}
		}
		
		//Get StaffID Given Email
		function GetStaffIDRecommended($email){
			$email = strtolower($email);
			//if($email=='webmaster@hcsdoh.org'){ $email='dmundey@hcsdoh.org'; }
			$query = "SELECT StaffID FROM Abre_Staff where EMail1 LIKE '$email' LIMIT 1";
			$dbreturn = databasequery($query);
			foreach ($dbreturn as $value)
			{ 
				$StaffId=htmlspecialchars($value["StaffID"], ENT_QUOTES);
				return $StaffId;
			}
		}
		
		//Get Current Semester
		function GetCurrentSemesterRecommended(){
			$currentMonth = date("F");
			if(	$currentMonth=="January" 	or 
				$currentMonth=="February" 	or 
				$currentMonth=="March" 		or 
				$currentMonth=="April" 		or 
				$currentMonth=="May" 		or 
				$currentMonth=="June" 		or 
				$currentMonth=="July" 		or 
				$currentMonth=="August"
			)
			{
				return "Sem2";
			}
			else
			{
				return "Sem1";
			}
		}
		
		//Check to see job title of user
		function AdminCheckRecommended($email){
			require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
			$email=encrypt($email, "");
			$contract=encrypt('Administrator', "");
			$title=encrypt('Counselor', "");
			$sql = "SELECT *  FROM directory where email='$email' and (contract='$contract' or title='$title')";
			$result = $db->query($sql);
			$count = $result->num_rows;
			if($count>=1)
			{
				return true;
			}
			else
			{
				return false;
			}
			$db->close();
		}
		
	}
?>