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
	
	if($pagerestrictions=="")
	{
		
		//Check to see subject of teacher
		$Staff_Subject=GetTeacherSubjectbyStaffID($StaffID);
		
		echo "<select class='browser-default recommend_dropdown' data-currentcourse='$CourseName' data-radio='$radioid' data-studentid='$StudentID'>";
			echo "<option value='ClearCourse'></option>";
			//if($Staff_Subject!="Math" && $Staff_Subject!="Science" && $Staff_Subject!="Social Studies" && $Staff_Subject!="Language Arts")
			//{
				$query = "SELECT * FROM recommendations_courses order by CourseName";
			//}
			//else
			//{
				//$query = "SELECT * FROM recommendations_courses where Subject='$Staff_Subject' or Subject='' order by Subject, CourseName";
			//}
			$dbreturn = databasequery($query);
			foreach ($dbreturn as $value)
			{
				$CourseNameDrop=htmlspecialchars($value["CourseName"], ENT_QUOTES);
				if($Recommendation_Course==$CourseNameDrop){ echo "<option value='$CourseNameDrop' selected='selected'>$CourseNameDrop</option>"; }else{ echo "<option value='$CourseNameDrop'>$CourseNameDrop</option>"; }
			}
		echo "</select>";
		
	}

?>