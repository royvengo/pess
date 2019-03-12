<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Log Call</title>
<script>
function validateForm() {
          var x = document.forms["frmLogCall"]["callerName"].value;
          var y = document.forms["frmLogCall"]["contactNum"].value;
          var z = document.forms["frmLogCall"]["location"].value;
          var j = document.forms["frmLogCall"]["incidentDesc"].value;
          if ((x == "") || (y == "") || (z == "") || (j == "")) {
            alert("Name must be filled out");
            return false;
          }
        }
</script>
</head>
<body>
	<?php include "header.php";
	// Go Royven
		$con = mysql_connect("localhost","royvengo","1234567890");
		if (!$con)
			{
			die('Cannot connect to database :'.mysql_error());
			}

		mysql_select_db("19_royven_pessdb",$con);
		
		$result = mysql_query("SELECT * FROM incidenttype");
		
		$incidentType;
		
		while($row = mysql_fetch_array($result))
			
		$incidentType[$row['incidentTypeId']] = $row['incidentTypeDesc'];
		
		if(isset($_POST["submit"]))
		{
		$sql = "INSERT INTO incident (callerName, phoneNumber, incidentTypeId, incidentLocation, incidentDesc, incidentStatusId) VALUES ('$_POST[callerName]', '$_POST[contactNum]', '$_POST[incidentType]', '$_POST[location]', '$_POST[incidentDesc]', '1')";
		if (!mysql_query($sql,$con))
		{
				die('Error: '.mysql_error());
		}
		}
		mysql_close($con);
	?>
	<form name="frmLogCall" method="POST" action="dispatch.php" onsubmit="return validateForm()">
		<fieldset style="color:black;">
			<legend><h2 style="color:black";>Log Call:</h2></legend>
				<table style="margin:auto">
					<tr>
						<td align="right">Caller's Name: </td>
						<td><p><input type="text" name="callerName" /></p></td>
					</tr>
					<tr>
						<td align="right">Contact Number: </td>
						<td><p><input type="text" name="contactNum" /></p></td>
					</tr>
					<tr>
						<td align="right">Location: </td>
						<td><p><input type="text" name="location" /></p></td>
					</tr>
					<tr></tr>
					<tr>
						<td align="right" class="td_label">Incident Type: </td>
						<td class="td_Date">
						<select name="incidentType" id="incidentType">
						<?php foreach( $incidentType as $key => $value){?>
						<option value="<?php echo $key ?>"><?php echo $value ?></option>
						<?php } ?>
						</select>
						</td>
					</tr>
					<tr></tr>
					<tr>
						<td align="right">Description: </td>
						<td><textarea name="incidentDesc" rows="5" cols="50"> </textarea></td>
					</tr>
					<td align="right"><input type="reset" value="Reset"></td>
					<td align="right"><input type="submit" name="submit" value="Process Call..."></td>
				</table>
				
		</fieldset>
	</form>
	<script>
</script>
</body>
</html>