<?php
/**
 * PHP script for getting results from database
 * 
 * PHP Version 7.0
 *  
 * @category SelectFromDB
 * @package  Movie
 * @author   Chris Kalms <example@ex.com>
 * @license  https://www.gnu.org/licenses/gpl.html GNU/GPLv3
 * @link     http://localhost/index.php/
 * @since    1.0.0  
 */

$movieArray = array();
$set = true;

$mySqlCon = @mysqli_connect("localhost", "moviesadmin", "adminpw", "moviesdb");
if (!$mySqlCon) {
    displayError("Can't connect to database.");
    $set = false;    
}
if (!(isset($_GET['title']) || isset($_GET['genre']) 
    || isset($_GET['rating']) || isset($_GET['year']))
) {
    selectAll($mySqlCon);
    $set = false;
}

if($set) {
  $title = $genre = $rating = $year = "";
  $title = $_GET["title"];
  $genre = $_GET["genre"];
  $rating = $_GET['rating'];
  $year = $_GET['year'];

  $t = empty($title) ? null : $title;
  $g = empty($genre) ? null : $genre;
  $r = empty($rating) ? null : $rating;
  $y = empty($year) ? null : $year;
  selectMovies($t, $g, $r, $y, $mySqlCon);
}

mysqli_close($mySqlCon);
echo(json_encode($movieArray));

/**
 * Selects from the database with specified parameters
 * 
 * @param title             $t   Title of the movie
 * @param genre             $g   Genre of the movie
 * @param rating            $r   Rating of the movie
 * @param year              $y   Year of the movie
 * @param mysqli-connection $con connection variable 
 * 
 * @return nothing
 */
function selectMovies($t, $g, $r, $y, $con) {
  global $movieArray;
  $select = $con -> prepare(
    "SELECT * FROM mymoviestable WHERE (Title LIKE CONCAT('%',?,'%') or ? IS NULL) 
    AND (Genre LIKE CONCAT('%',?,'%') OR ? IS NULL) AND (Rating LIKE CONCAT('%',?,'%') OR ? IS NULL) AND (Year LIKE CONCAT('%',?,'%') OR ? IS NULL);"
  );
  
  $select -> bind_param('ssssssii', $t, $t, $g, $g, $r, $r, $y, $y);
  $select -> execute();
  $thisResult = $select -> get_result();

  while ($row = $thisResult -> fetch_assoc()) {
    array_push($movieArray, createMovieObject($row));   
  }  
}

/**
 * Selects from the database
 * 
 * @param mysqli-connection $con connection variable
 * 
 * @return nothing
 */
function selectAll($con)
{
	global $movieArray;
  $selectAll = $con -> prepare(
    "SELECT * FROM mymoviestable;"
  );    
  $selectAll -> execute();
  $res = $selectAll -> get_result();

  //if results returned zero
  //display user friendly message
  if (($res -> num_rows) === 0) {        
    displayError("No Results");
  }

  while ($row = $res -> fetch_assoc()) {
	  array_push($movieArray, createMovieObject($row));			
  }
  
}

/**
 * Create a movie object from a row out of a database.
 * 
 * @param mysqli-row $row holds the data required to create the object
 * 
 * @return Movie object
 */
function createMovieObject($row) {	
	$m = new Movie();

  $m -> Title = $row['Title'];
  $m -> Studio = $row['Studio'];
  $m -> Status = $row['Status'];
  $m -> Sound = $row['Sound'];
  $m -> Versions = $row['Versions'];
  $m -> RecRetPrice = $row['RecRetPrice'];
  $m -> Rating = $row['Rating'];
  $m -> Year = $row['Year'];
  $m -> Genre = $row['Genre'];
	$m -> Aspect = $row['Aspect'];
	
	return $m;
}

class Movie{
	public $Title;
	public $Studio;
	public $Status;
	public $Sound;
	public $Versions;
	public $RecRetPrice;
	public $Rating;
	public $Year;
	public $Genre;
	public $Aspect;
}
?>