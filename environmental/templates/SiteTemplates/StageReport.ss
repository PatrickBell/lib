<h2>$Title</h2>

<% if AllXML %>
	<table>
		<tr><th>Site name</th><th>Date</th><th>Stage</th><th>Change</th><th>Peak stage</th><th>Peak time</th><th>Flow</th><th>Peak flow</th></tr>
		<% control AllXML %>
			<tr>
				<td>$SiteName</td>
				<td>$DataTo</td>
				<td class="alignRight">$Stage</td>
				<td class="alignRight">$StageChange</td>
				<td class="alignRight">$PeakStage</td>
				<td>$PeakTime</td>
				<td class="alignRight">$Flow</td>
				<td class="alignRight">$PeakFlow</td>
			</tr>
		<% end_control %>
	</table>
<% else %>
	<em>No data available</em>
<% end_if %>

$Content