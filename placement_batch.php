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