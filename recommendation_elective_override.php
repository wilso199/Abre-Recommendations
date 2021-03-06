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
	require_once(dirname(__FILE__) . '/../../core/abre_functions.php'); 
	require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
	require_once('functions.php');
	require_once('permissions.php');
	
	if($pagerestrictions=="")
	{
	
		//Get Post Data
		$Student_ID=mysqli_real_escape_string($db, $_POST["Student_ID"]);
		$Recommendation_ID=mysqli_real_escape_string($db, $_POST["Recommendation_ID"]);
		$Recommended_Level=mysqli_real_escape_string($db, $_POST["Recommended_Level"]);	
		$Recommended_Course=mysqli_real_escape_string($db, $_POST["Recommended_Course"]);	
		$Recommending_User=$_SESSION['useremail'];

		mysqli_query($db, "UPDATE recommendations set Override_Level='$Recommended_Level', Override_User='$Recommending_User' where StudentID='$Student_ID' and ID='$Recommendation_ID'") or die (mysqli_error($db));
		
		SavePlacement($Student_ID, $Recommended_Course, $Recommended_Level);
			
	
	}

?>