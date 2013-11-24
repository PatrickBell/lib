<a name="top"><h1>$Title</h1></a>

<% if XML.Image1 %>
<p>
	<h3>$XML.Image1Title</h3>
	<img src="$ImagesURL/Sites_RiverFlow/$XML.Image1" />
</p>
<% end_if %>

<% if HistoricalXML(7) %>
	<a href="$Link#table">Click here to view a table showing hourly rainfall readings for the last 7 days</a>
<% end_if %>

<% if XML.Image2 %>
<p>
	<h3>$XML.Image2Title</h3>
	<img src="$ImagesURL/Sites_RiverFlow/$XML.Image2" />
</p>
<% end_if %>

$Content

<% if HistoricalXML(7) %>
	<a name="table"><h3>Table showing the hourly rainfall readings for the last 7 days</h3></a>
	<table>
		<tr><th>Date</th><th>$XML.Measurement [$XML.Unit]</th></tr>
		<% control HistoricalXML(7) %>
			<tr>
				<td>$DateTime</td>
				<td class="alignRight">{$NonDetect}{$Value}</td>
			</tr>
		<% end_control %>
	</table>
<% end_if %>

<a href="$Link#top">Back to Top</a>
