<html>
<head>
	<title>JasonsWeather.com</title>
	<style>
		body {
                        background-color: #87CEEB;
                        color: #333;
                        font-family: Arial, sans-serif;
                }


		h1 {
			text-align: center;
			font-size: 2em;
			margin-top: 20px;
		}

		form {
			margin-top: 20px;
			margin-bottom: 20px;
			text-align: center;
		}

		select {
			font-size: 1.2em;
			padding: 5px 10px;
		}

		input[type=submit] {
			font-size: 1.2em;
			padding: 5px 10px;
			background-color: #4CAF50;
			color: white;
			border: none;
			border-radius: 4px;
			cursor: pointer;
		}

		input[type=submit]:hover {
			background-color: #45a049;
		}

		ul {
			list-style-type: none;
			margin: 0;
			padding: 0;
			text-align: center;
			border-top: 1px solid #ccc;
			border-bottom: 1px solid #ccc;
			margin-top: 20px;
			margin-bottom: 20px;
		}

		li {
			display: inline-block;
			margin: 10px;
		}

		a {
			text-decoration: none;
			color: #333;
			font-size: 1.2em;
			padding: 5px 10px;
			border-radius: 4px;
		}

		a:hover {
			background-color: #ddd;
		}

		p {
			font-size: 1.2em;
			margin-bottom: 10px;
		}
	</style>
</head>
<body>
<?php
// Establish a database connection
$host = "localhost";
$username = "root";
$password = "Jimmy#1234";
$database = "testing";
$connection = mysqli_connect($host, $username, $password, $database);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the visitor's IP address
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // Get the visitor's preferred city
    $preferred_city = $_POST['city'];

    // Check if the IP address exists in the database
    $query = "SELECT preferred_city FROM user_preferences WHERE ip_address = '$ip_address'";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) > 0) {
    // If the IP address exists, update the existing row with the new preferred city value
    $query = "UPDATE user_preferences SET preferred_city = '$preferred_city' WHERE ip_address = '$ip_address'";
    mysqli_query($connection, $query);
    }
    else {
    // If the IP address does not exist, insert a new row with the IP address and preferred city value
    $query = "INSERT INTO user_preferences (ip_address, preferred_city) VALUES ('$ip_address', '$preferred_city')";
    mysqli_query($connection, $query);
    }

    // Close the database connection
    mysqli_close($connection);
}?>

<h1> <center> 7-Day Weather Forecast </center> </h1>
<!-----Menu----------------------------------------------------->
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <label for="city">Select your preferred city:</label>
  <select id="city" name="city">
    <?php
    $cities = array('New York NY', 'East L.A. CA', 'Chicago IL', 'Houston TX', 'Phoenix AZ', 'Philadelphia PA', 'Jacksonville FL');
    foreach ($cities as $city) {
        echo "<option value='$city'>$city</option>";
    }
    ?>
  </select>
  <input type="submit" name="submit" value="Save">
</form>

<!----Linking to other cities--->
<h1>Dashboard</h1>
<ul>
        <li><a href="NY.php">New York NY</a></li>
        <li><a href="CA.php">East L.A. CA</a></li>
        <li><a href="IL.php">Chicago IL</a></li>
        <li><a href="TX.php">Houston TX</a></li>
        <li><a href="AZ.php">Phoenix AZ</a></li>
        <li><a href="PA.php">Philadelphia PA</a></li>
        <li><a href="FL.php">Jacksonville FL</a></li>

</ul>

<?php
// Establish a database connection
$host = "localhost";
$username = "root";
$password = "Jimmy#1234";
$database = "testing";
$connection = mysqli_connect($host, $username, $password, $database);

// Check if the connection was successful
if (mysqli_connect_errno()) {
  die("Connection failed: " . mysqli_connect_error());
}

// Get the visitor's IP address
$ip_address = $_SERVER['REMOTE_ADDR'];

// Retrieve the visitor's preferred city from the database based on their IP address
$query = "SELECT preferred_city FROM user_preferences WHERE ip_address = '$ip_address'";
$result = mysqli_query($connection, $query);
$row = mysqli_fetch_assoc($result);
$preferred_city = $row['preferred_city'];
// If the visitor's preferred city is not set, use the default city
if (!$preferred_city) {
    $preferred_city = 'East L.A. CA';
}

if (mysqli_num_rows($result) > 0) {
  // If the user has a preferred city set, update the page heading to display it
  $row = mysqli_fetch_assoc($result);
  echo "<h1>Weather in $preferred_city</h1>";
}
else {
  // If the user does not have a preferred city set, display a default page heading
  echo "<h1>Weather in $preferred_city</h1>";
}
//close
mysqli_close($connection);
?>

<?php
// Establish a database connection
$host = "localhost";
$username = "root";
$password = "Jimmy#1234";
$database = "testing";
$connection = mysqli_connect($host, $username, $password, $database);

// Check if the connection was successful
if (mysqli_connect_errno()) {
  die("Connection failed: " . mysqli_connect_error());
}

// Prepare a SQL query to retrieve the data you need
$query = "SELECT * FROM weather_data WHERE state_city='$preferred_city'";

// Execute the query and get the results
$result = mysqli_query($connection, $query);

// Add a container div for the two columns
echo "<div style='display: flex;'>";

// Loop through the results and print them
while ($row = mysqli_fetch_assoc($result)) {
  echo "<div style='flex: 1; margin-right: 20px;'>";
  echo "<p style='display: flex; flex-direction: column; align-items: center;' >";
  //echo $row['state_city'] . ": ";
  echo $row['days'] . "<br>";
  if (stripos($row['days'],'night') !== false)
  { echo "<img src='/images/night.jpg' alt='night' width='100' height='100' >"; }
  else
  { echo "<img src='/images/day.jpg' alt='day' width='100' height='100' >"; }
  echo "<span style='text-align: center;'>";
  echo $row['short_descriptions'] . "<br>";
  echo $row['temperatures']. "<br>";
  echo "</span>";
  //echo $row['long_descriptions'];
  echo "</p>";

  echo "</div>";
}

 echo "</div>";

// Close the database connection
mysqli_close($connection);
?>
<?php
// Establish a database connection
$host = "localhost";
$username = "root";
$password = "Jimmy#1234";
$database = "testing";
$connection = mysqli_connect($host, $username, $password, $database);

// Check if the connection was successful
if (mysqli_connect_errno()) {
  die("Connection failed: " . mysqli_connect_error());
}

// Prepare a SQL query to retrieve the data you need
$query = "SELECT * FROM weather_data WHERE state_city='$preferred_city'";

// Execute the query and get the results
$result = mysqli_query($connection, $query);
echo "<table style='border: 2px solid black;'>";
echo "<tr><th style='text-align: left;'>Days</th><th style='text-align: left;'>Long Descriptions</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
  echo "<tr>";
  echo "<td>" . $row['days'] . "</td>";
  echo "<td>" . $row['long_descriptions'] . "</td>";
  echo "</tr>";
}
echo "</table>";

?>
</body>
</html>


