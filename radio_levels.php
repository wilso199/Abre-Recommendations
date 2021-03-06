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
		if(!isset($Recommendation_Course)){ $Recommendation_Course=""; }
		if(!isset($CourseName)){ $CourseName=""; }
		if(!isset($Recommendation_Level)){ $Recommendation_Level=""; }
		if(!isset($AvailableLevels)){ $AvailableLevels=""; }
		
		if($Recommendation_Course=="")
		{
			if(isset($_POST["Student_ID"])){ $StudentID=mysqli_real_escape_string($db, $_POST["Student_ID"]); }else{ $StudentID=""; }
			if(isset($_POST["RecCourse"])){ $RecCourse=mysqli_real_escape_string($db, $_POST["RecCourse"]); }else{ $RecCourse=""; }
			if(isset($_POST["CourseName"])){ $CurrentCourseName=mysqli_real_escape_string($db, $_POST["CourseName"]); $coursenameslug=preg_replace('/\PL/u', '', $CurrentCourseName); }else{ $CurrentCourseName=""; }
		}
		else
		{
			$RecCourse=$Recommendation_Course;
			$CurrentCourseName=$CourseName;
		}
		
		if($RecCourse!="")
		{
		
			//Check available levels of this course
			$query2 = "SELECT * FROM recommendations_courses where CourseName='$RecCourse'";
			$dbreturn2 = databasequery($query2);
			foreach ($dbreturn2 as $value2)
			{
				$AvailableLevels=htmlspecialchars($value2["Levels"], ENT_QUOTES);
			}
			
			$currenttime="$RecCourse-$CurrentCourseName";
			$currenttime = str_replace(' ', '', $currenttime);
			
			if($AvailableLevels!="")
			{
	
					if(strpos($AvailableLevels, 'Core') !== false)
					{
						echo "<input "; if($Recommendation_Level=='Core'){ echo "checked='checked'"; }; echo " class='recommend' data-levelvalue='1' data-levelname='Core' data-currentcourse='$CurrentCourseName' data-studentid='$StudentID' name='Level-$StudentID-$currenttime' type='radio' id='Core-$StudentID-$currenttime' /><label for='Core-$StudentID-$currenttime' style='margin: 0 0 0 20px;'>Core</label>";		
					}
					
					if(strpos($AvailableLevels, 'Core') !== false && strpos($AvailableLevels, 'CP') !== false)
					{
					echo "<input "; if($Recommendation_Level=='Core/CP'){ echo "checked='checked'"; }; echo " class='recommend' data-levelvalue='2' data-levelname='Core/CP' data-currentcourse='$CurrentCourseName' data-studentid='$StudentID' name='Level-$StudentID-$currenttime' type='radio' id='CoreCP-$StudentID-$currenttime' /><label for='CoreCP-$StudentID-$currenttime' style='margin: 0 0 0 20px;'>Core/CP</label>";
					}
					
					if(strpos($AvailableLevels, 'CP') !== false)
					{
						echo "<input "; if($Recommendation_Level=='CP'){ echo "checked='checked'"; }; echo " class='recommend' data-levelvalue='3' data-levelname='CP' data-currentcourse='$CurrentCourseName' data-studentid='$StudentID' name='Level-$StudentID-$currenttime' type='radio' id='CP-$StudentID-$currenttime' /><label for='CP-$StudentID-$currenttime' style='margin: 0 0 0 20px;'>CP</label>";
					}
					
					if(strpos($AvailableLevels, 'CP') !== false && (strpos($AvailableLevels, 'Honors') !== false or strpos($AvailableLevels, 'AP') !== false or strpos($AvailableLevels, 'CCP') !== false))
					{
						echo "<input "; if($Recommendation_Level=='CP/Honors'){ echo "checked='checked'"; }; echo " class='recommend' data-levelvalue='4' data-levelname='CP/Honors' data-currentcourse='$CurrentCourseName' data-studentid='$StudentID' name='Level-$StudentID-$currenttime' type='radio' id='CPHonors-$StudentID-$currenttime' /><label for='CPHonors-$StudentID-$currenttime' style='margin: 0 0 0 20px;'>CP/Honors</label>";
					}
						
					if(strpos($AvailableLevels, 'Honors') !== false)
					{
						echo "<input "; if($Recommendation_Level=='Honors'){ echo "checked='checked'"; }; echo " class='recommend' data-levelvalue='5' data-levelname='Honors' data-currentcourse='$CurrentCourseName' data-studentid='$StudentID' name='Level-$StudentID-$currenttime' type='radio' id='Honors-$StudentID-$currenttime' /><label for='Honors-$StudentID-$currenttime' style='margin: 0 0 0 20px;'>Honors</label>";
					}
					
					if(strpos($AvailableLevels, 'AP') !== false)
					{
						echo "<input "; if($Recommendation_Level=='AP'){ echo "checked='checked'"; }; echo " class='recommend' data-levelvalue='5' data-levelname='AP' data-currentcourse='$CurrentCourseName' data-studentid='$StudentID' name='Level-$StudentID-$currenttime' type='radio' id='AP-$StudentID-$currenttime' /><label for='AP-$StudentID-$currenttime' style='margin: 0 0 0 20px;'>AP</label>";
					}
					
					if(strpos($AvailableLevels, 'CCP') !== false)
					{
						echo "<input "; if($Recommendation_Level=='CCP'){ echo "checked='checked'"; }; echo " class='recommend' data-levelvalue='5' data-levelname='CCP' data-currentcourse='$CurrentCourseName' data-studentid='$StudentID' name='Level-$StudentID-$currenttime' type='radio' id='CCP-$StudentID-$currenttime' /><label for='CCP-$StudentID-$currenttime' style='margin: 0 0 0 20px;'>CCP</label>";
					}
					
			}
		}
		
	}

?>