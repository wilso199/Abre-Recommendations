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
	require_once(dirname(__FILE__) . '/../../core/abre_functions.php'); 
	require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
	require_once('functions.php');
	require_once('permissions.php');
	
	if($pagerestrictions=="")
	{
	
		//Get Post Data
		$Student_ID=mysqli_real_escape_string($db, $_POST["Student_ID"]);
		$Staff_ID=mysqli_real_escape_string($db, $_POST["Staff_ID"]);
		if(isset($_POST["RecLevelValue"])){ $RecLevelValue=mysqli_real_escape_string($db, $_POST["RecLevelValue"]); }else{ $RecLevelValue=""; }
		if(isset($_POST["RecLevelName"])){ $RecLevelName=mysqli_real_escape_string($db, $_POST["RecLevelName"]); }else{ $RecLevelName=""; }
		if(isset($_POST["RecCourse"])){ $RecCourse=mysqli_real_escape_string($db, $_POST["RecCourse"]); }else{ $RecCourse=""; }
		$CourseName=mysqli_real_escape_string($db, $_POST["CourseName"]);
		$Year=date("Y");
			
		//If saving level
		if($RecLevelValue!="")
		{
			$sql = "SELECT * FROM recommendations where StudentID='$Student_ID' and StaffID='$Staff_ID' and Year='$Year'";
			$result = $db->query($sql);
			$numrows = $result->num_rows;
			if($numrows==0)
			{
				$stmt = $db->stmt_init();
				$sql = "INSERT INTO recommendations (StudentID, StaffID, CurrentCourse, Recommendation, Recommendation_Level, Year) VALUES ('$Student_ID', '$Staff_ID', '$CourseName', '$RecLevelValue', '$RecLevelName', '$Year');";
				$stmt->prepare($sql);
				$stmt->execute();
				$stmt->close();
				$db->close();
			}
			else
			{
				mysqli_query($db, "UPDATE recommendations set Recommendation='$RecLevelValue', Recommendation_Level='$RecLevelName' where StudentID='$Student_ID' and StaffID='$Staff_ID' and Year='$Year'") or die (mysqli_error($db));
			}
		}	
		
		//If saving course
		if($RecCourse!="")
		{
			
			//If saving course
			if($RecCourse=="ClearCourse")
			{
				$sql = "SELECT * FROM recommendations where StudentID='$Student_ID' and StaffID='$Staff_ID' and Year='$Year'";
				$result = $db->query($sql);
				$numrows = $result->num_rows;
				if($numrows!=0)
				{
					mysqli_query($db, "DELETE FROM recommendations where StudentID='$Student_ID' and StaffID='$Staff_ID' and Year='$Year'") or die (mysqli_error($db));
				}
			}
			else
			{
				$sql = "SELECT * FROM recommendations where StudentID='$Student_ID' and StaffID='$Staff_ID' and Year='$Year'";
				$result = $db->query($sql);
				$numrows = $result->num_rows;
				if($numrows==0)
				{
					$stmt = $db->stmt_init();
					$sql = "INSERT INTO recommendations (StudentID, StaffID, CurrentCourse, Recommendation_Course, Year) VALUES ('$Student_ID', '$Staff_ID', '$CourseName', '$RecCourse', '$Year');";
					$stmt->prepare($sql);
					$stmt->execute();
					$stmt->close();
					$db->close();
				}
				else
				{
					mysqli_query($db, "UPDATE recommendations set Recommendation_Course='$RecCourse', Recommendation='', Recommendation_Level='' where StudentID='$Student_ID' and StaffID='$Staff_ID' and Year='$Year'") or die (mysqli_error($db));
				}
			}
		}	
			
	
	}

?>