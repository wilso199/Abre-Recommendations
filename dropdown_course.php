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
		
		//Check to see subject of teacher
		$Staff_Subject=GetTeacherSubjectbyStaffID($StaffID);
		
		echo "<select class='browser-default recommend_dropdown' data-currentcourse='$CourseName' data-studentid='$StudentID'>";
			echo "<option value='ClearCourse'></option>";
			if($Staff_Subject=="")
			{
				$query = "SELECT * FROM recommendations_courses";
			}
			else
			{
				$query = "SELECT * FROM recommendations_courses where Subject='$Staff_Subject' order by Subject, CourseName";
			}
			$dbreturn = databasequery($query);
			foreach ($dbreturn as $value)
			{
				$CourseNameDrop=htmlspecialchars($value["CourseName"], ENT_QUOTES);
				if($Recommendation_Course==$CourseNameDrop){ echo "<option value='$CourseNameDrop' selected='selected'>$CourseNameDrop</option>"; }else{ echo "<option value='$CourseNameDrop'>$CourseNameDrop</option>"; }
			}
		echo "</select>";
		
	}

?>