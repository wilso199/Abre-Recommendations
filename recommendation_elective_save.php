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
		$ElectiveNumber=mysqli_real_escape_string($db, $_POST["Elective_Number"]);
		$ElectiveName=mysqli_real_escape_string($db, $_POST["Elective_Name"]);
		$Year=date("Y");
			
		$sql = "SELECT * FROM recommendations where StudentID='$Student_ID' and StaffID='' and Recommendation='$ElectiveNumber' and Year='$Year'";
		$result = $db->query($sql);
		$numrows = $result->num_rows;
		if($numrows==0)
		{
			$stmt = $db->stmt_init();
			$sql = "INSERT INTO recommendations (StudentID, Recommendation, Recommendation_Course, Recommendation_Level, Year) VALUES ('$Student_ID', '$ElectiveNumber', '$ElectiveName', 'Elective', '$Year');";
			$stmt->prepare($sql);
			$stmt->execute();
			$stmt->close();
			$db->close();
		}
		else
		{
			if($ElectiveName!="")
			{
				mysqli_query($db, "UPDATE recommendations set Recommendation_Course='$ElectiveName' where StudentID='$Student_ID' and Recommendation='$ElectiveNumber' and StaffID='' and Year='$Year'") or die (mysqli_error($db));
			}
			else
			{
				mysqli_query($db, "DELETE FROM recommendations where StudentID='$Student_ID' and StaffID='' and Recommendation='$ElectiveNumber' and Year='$Year'") or die (mysqli_error($db));
			}
		}
			
	
	}

?>