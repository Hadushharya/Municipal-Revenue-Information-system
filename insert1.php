<?php
	include("connection.php");  
 session_start();
 ?>
 <html>
<link href="stylee.css" media="screen" rel="stylesheet" type="text/css" />
<meta charset="utf-8">
<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<form action="insert2.php" method="post">
<div id="ac">
<div class="input-group">
<table width="316">
<caption><h1><b>ሓዱሽ ኣባል መዝግብ</b></h1></caption>
     <tr>
	 <td width="92" style="height: 30px">
		ተ/ቁ:&nbsp;&nbsp;</td>
		<td width="179" style="height: 30px">
			<input required name="sn" type="text" placeholder="">
		</td>
		</tr>
		<tr>
		<td width="92" style="height: 30px">
			መፍለይ ቑፅሪ :&nbsp;&nbsp;</td>
		<td width="179" style="height: 30px">
			<input required name="name" type="text" placeholder="">
		</td>
		</tr>
		<tr>
		<td width="92" style="height: 30px">
		ስም ምስ ኣቦሓጎ :&nbsp;&nbsp;</td>
		<td width="179" style="height: 30px">
			<input required name="name" type="text" placeholder="">
		</td>
		</tr>
		<tr>
		<td width="92" style="height: 30px">
		ፆታ :&nbsp;&nbsp;</td>
		<td width="179" style="height: 30px">
			<input required name="name" type="text" placeholder="">
		</td>
		</tr>
		<tr>
		<td width="92" style="height: 30px">
		ሳጓ :&nbsp;&nbsp;</td>
		<td width="179" style="height: 30px">
			<input required name="name" type="text" placeholder="">
		</td>
		</tr>
		<tr>
		<td width="92" style="height: 30px">
		ናይ ኣዶ ስም :&nbsp;&nbsp;</td>
		<td width="179" style="height: 30px">
			<input required name="name" type="text" placeholder="">
		</td>
		</tr>
		<tr>
		<td width="92" style="height: 30px">
		ትውልዲ ዘመን :&nbsp;&nbsp;</td>
		<td width="179" style="height: 30px">
			<input required name="name" type="text" placeholder="">
		</td>
		</tr>
		<tr>
		<td width="92" style="height: 30px">
		ኩነታት ትምህርቲ :&nbsp;&nbsp;</td>
		<td width="179" style="height: 30px">
			<input required name="name" type="text" placeholder="">
		</td>
		</tr>
		<tr>
		<td width="92" style="height: 30px">
		መኸተ ዝኣተወሉ ጊዜ :&nbsp;&nbsp;</td>
		<td width="179" style="height: 30px">
			<input required name="name" type="text" placeholder="">
		</td>
		</tr>
		<tr>
		<td width="92" style="height: 30px">
		ሞያ :&nbsp;&nbsp;</td>
		<td width="179" style="height: 30px">
			<input required name="name" type="text" placeholder="">
		</td>
		</tr>
		<tr>
		<td width="92" style="height: 30px">
		ትውልዲ ቦታ:&nbsp;&nbsp;</td>
		<td width="179" style="height: 30px">
			<input required name="name" type="text" placeholder="">
		</td>
		</tr>
		<tr>
		<td width="92" style="height: 30px">
		ድሕረ ባይታ ንነባራት:&nbsp;&nbsp;</td>
		<td width="179" style="height: 30px">
			<input required name="age" type="text" placeholder="">
		</td>

	</tr>
    <tr>
		<td width="92" style="height: 30px">
		ክፍሊ:&nbsp;&nbsp;</td>
		<td width="179" style="height: 30px">
			<input required name="gender" type="number_format" placeholder="">
		</td>

	</tr>
    <tr>
		<td width="92" style="height: 30px">
		ናይ ቀረባ ተፀዋዒ:&nbsp;&nbsp;</td>
		<td width="179" style="height: 30px">
			<input required name="level" type="text" placeholder="">
		</td>

	</tr>
    
	<tr>
		<td width="92" style="height: 30px">
		ናይ ተፀዋዒ ኣድራሻ:&nbsp;&nbsp;</td>
		<td width="179" style="height: 30px">
			<input name="subject" type="text" required placeholder="">
		</td>

	</tr>
     <tr>
	<td width="179" style="height: 30px">
              Photo:&nbsp;&nbsp;</label>
              <input type="file"  name="photo"id="exampleInputFile">
            </tr>
	<tr>
		<td>
		</td>
		<td>
			<input name="Submit" type="submit" value="ኣቐምጥ">
            <input  type="reset" value="ሰርዝ" />
		</td>

	</tr>
</table>
</div>
</form>