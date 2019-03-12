<?php
// Go Royven
	if(!isset($_POST['submit']) && !isset($_POST['btnDispatch']))
		header("Location: logcall.php");

$con = mysql_connect("localhost","royvengo","1234567890");
		if (!$con)
			{
			die('Cannot connect to database :'.mysql_error());
			}

		mysql_select_db("19_royven_pessdb",$con);

if(isset($_POST["submit"]))
		{
		$sql = "INSERT INTO incident (callerName, phoneNumber, incidentTypeId, incidentLocation, incidentDesc, incidentStatusId) VALUES ('$_POST[callerName]', '$_POST[contactNum]', '$_POST[incidentType]', '$_POST[location]', '$_POST[incidentDesc]', '1')";
		if (!mysql_query($sql,$con))
		{
				die('Error: '.mysql_error());
		}
		}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Dispatch</title>
<style type="text/css">
</style>
</head>

<body>
	<?php include "header.php";
	
	/* Search and retrieve similar pending incidents and populate a table */
	
	// connect to a datebase
	$con = mysql_connect("localhost","royvengo","1234567890");
		if (!$con)
			{
			die('Cannot connect to database :'.mysql_error());
			}
	// select a table in the database
	mysql_select_db("19_royven_pessdb",$con);
	
	$sql = "SELECT patrolcarId, statusDsc FROM patrolcar JOIN patrolcar_status ON patrolcar.patrolcarStatusId=patrolcar_status.statusId WHERE patrolcar.patrolcarStatusId='2' OR patrolcar.patrolcarStatusId='3'";
	
	$result = mysql_query($sql,$con);
	$incidentArray;
	$count=0;
	
	while($row = mysql_fetch_array($result))
	{
		$patrolcarArray[$count]=$row;
		$count++;
	}
	
	if(!mysql_query($sql,$con))
	{
		die('Error: '. mysql_error());
	}
	
	mysql_close($con);
	
	?>
	<form name="frmLogCall" method="POST" action="dispatch.php" onsubmit="return validateForm()">
		<fieldset style="color:black;">
			<legend><h2 style="color:black";>Log Call:</h2></legend>
				<table style="margin:auto">
					<tr>
						<td align="right">Caller's Name: </td>
						<td><p><input type="hidden" name="callerName" /><?php echo $_POST['callerName']?></p></td>
					</tr>
					<tr>
						<td align="right">Contact Number: </td>
						<td><p><input type="hidden" name="contactNum" /><?php echo $_POST['contactNum']?></p></td>
					</tr>
					<tr>
						<td align="right">Location: </td>
						<td><p><input type="hidden" name="location" /><?php echo $_POST['contactNum']?></p></td>
					</tr>
					<tr></tr>
					<tr>
						<td align="right" class="td_label">Incident Type: </td>
						<td class="td_Date"><input type="hidden" name="incidentType" />
						<?php echo $_POST['incidentType']?>
						</td>
					</tr>
					<tr></tr>
					<tr>
						<td align="right">Description: </td>
						<td><textarea name="incidentDesc" rows="5" cols="50"><?php echo $_POST['incidentDesc']?></textarea></td>
					</tr>
				</table>
			<table width ="40%" border="1" align="center" cellpadding="4" cellspacing="8">
		<tr>
			<td width="20%">&nbsp;</td>
			<td width="51%">Patrol Car ID</td>
			<td width="29%">Status</td>
		</tr>
		
  <?php
		$i=0;
		while($i<$count){
			?>
		<tr>
			<td class="td_label"><input type ="checkbox" name="chkPatrolcar[]" value="<?php echo $patrolcarArray[$i]['patrolcarId'] ?>"></td>
			<td><?php echo $patrolcarArray[$i]['patrolcarId'] ?></td>
			<td><?php echo $patrolcarArray[$i]['statusDsc'] ?></td>
		</tr>
		
		<?php $i++;
		} ?>
		
	</table>
	<table width="80%" border="0" align="center" cellpadding="4" cellspacing="4">
		<td width="46%" class="td_label"><input type="reset" name="btnCancel" id="btnCancel" value="Reset"></td>
		<td width="54%"class="td_Data">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="btnSubmit" id="btnSubmit" value="Submit"></td>
	</table>
	<?php
	if(isset($_POST["btnSubmit"]))
	{
		// connect to a datebase
	$con = mysql_connect("localhost","royvengo","1234567890");
		if (!$con)
			{
			die('Cannot connect to database :'.mysql_error());
			}
		
	mysql_select_db("19_royven_pessdb",$con);
	// update patrolcar status table and dispatch table
		$patrolcarDispatched = $_POST["chkPatrolcar"];
		$c = count($patrolcarDispatched);
		
		//insert new incident
		$status;
		if($c >0){
			$status='2';
		} else{
			$status='1';
		}
		
		$sql = "INSERT INTO incident (callerName, phoneNumber, incidentTypeId, incidentLocation, incidentDesc, incidentStatusId) VALUES ('".$_POST['callerName']."', '".$_POST['contactNum']."', '".$_POST['incidentType']."', '".$_POST['location']."', '".$_POST['incidentDesc']."', '$status')";
		if (!mysql_query($sql,$con))
		{
				die('Error1: '.mysql_error());
		}
		// retrieve new incremental key for incidentId
		$incidentId=mysql_insert_id($con);;
		
		for($i=0; $i<$c;$i++)
		{
			$sql = "UPDATE patrolcar SET patrolcarStatusId='1' WHERE patrolcarId='$patrolcarDispatched[$i]'";
			
			if(!mysql_query($sql,$con))
			{
				die('Error2:'.mysql_error());
			}
			$sql = "INSERT INTO dispatch (incidentId, patrolcarId, timeDispatched) VALUES ('$incidentId','$patrolcarDispatched[$i]',NOW())";
			
			if(!mysql_query($sql,$con))
			{
				die('Error3:'.mysql_error());
			}
		}
		mysql_close($con);
	}
	?>
		</fieldset>
	</form>
</body>
</html>