<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  <link rel="stylesheet" href="./bootstrap.css">
<title>Ferry Schedule</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>

<h2>Ferry Schedule</h2>

<?php
// connect to the database
include('connect-db.php');

// get the records from the database
if ($result = $mysqli->query("SELECT * FROM Schedule ORDER BY departs, time"))
{
// display records if there are records to display
if ($result->num_rows > 0)
{
// display records in a table
echo "<table class='table table-striped table-bordered table-hover' >";

// set table headers
echo "<tr><th>ID</th><th>Departs</th><th>Times</th><th>Days</th><th style='display:none'>Notes</th><th></th></tr>";

while ($row = $result->fetch_object())
{
// set up a row for each record
echo "<tr>";
echo "<td>" . $row->id . "</td>";
echo "<td>" . $row->departs . "</td>";
echo "<td>" . $row->time . "</td>";
echo "<td>" . $row->days . "</td>";
echo "<td style='display:none'>" . $row->notes . "</td>";  
echo "<td><a href='records.php?id=" . $row->id . "'><button type='submit' name='submit' class='btn-default btn-sm' value='Submit'>Edit</button></a> <a href='delete.php?id=" . $row->id . "'><button type='submit' name='submit' class='btn-default btn-danger btn-sm' value='Submit'>Del</button></a></td>";
echo "</tr>";
}

echo "</table>";
}
// if there are no records in the database, display an alert message
else
{
echo "No results to display!";
}
}
// show an error if there is an issue with the database query
else
{
echo "Error: " . $mysqli->error;
}

// close database connection
$mysqli->close();

?>

<a href="records.php">Add New Record</a>
</body>
</html>