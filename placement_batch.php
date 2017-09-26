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
	require(dirname(__FILE__) . '/../../configuration.php');
	require_once(dirname(__FILE__) . '/../../core/abre_functions.php'); 
	require_once('functions.php');

	//Run through all students
	$query = "SELECT * FROM recommendations LEFT JOIN Abre_Students on Abre_Students.StudentID=recommendations.StudentID group by Abre_Students.StudentID";
	$dbreturn = databasequery($query);
	$studentfound=count($dbreturn);
	foreach ($dbreturn as $value)
	{	
		$student_search=htmlspecialchars($value["StudentID"], ENT_QUOTES);
		include "placement_calculation_engine.php";		
	}


?>