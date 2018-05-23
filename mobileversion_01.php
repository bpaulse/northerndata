<div style="overflow-y: scroll;">
	<table width="100%" height="100px">
		<tr align="center">
			<td colspan="2"><img src="images/logo.png" width="100px" /></td>
		</tr>
		<tr align="center">
			<td colspan="2">NORTHERN DATA</td>
		</tr>
		<tr align="center">
			<td colspan="2">manage | distribute | secure data</td>
		</tr>
	<table>

	<table width="100%">
		<tr align="center">
			<td colspan="2"><h4>ABOUT US</h4></td>
		</tr>
		<tr align="center">
			<td colspan="2">
				<?php
					$PageName = 'AboutUs';
					echo "<br >";
					foreach ( $function->getPageInfo($PageName) as $content ){
						echo $content['ContentText'];
						echo "<br /><br />";
					}
				?>
			</td>
		</tr>
	<table>

	<table width="100%">
		<tr align="center">
			<td colspan="2"><h4>SERVICES</h4></td>
		</tr>
		<tr align="center">
			<td>
				Database Administration<br />
				Azure<br />Data Strategy<br />Data Analytics<br />Business Continuity Services<br />Information and Knowledge Management</td>
		</tr>
	<table>
</div>

</body>
</html>