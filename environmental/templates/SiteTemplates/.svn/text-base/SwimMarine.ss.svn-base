<a name="top"><h1>$Title</h1></a>

<% if XML.Image1 %>
<p>
	<h3>$XML.Image1Title</h3>
	<img src="$ImagesURL/Sites_SwimMarine/$XML.Image1" />
</p>
<% end_if %>

<% if HistoricalXML(2009-07-01) %>
	<p><a href="$Link#table">Click here to view a table showing the water quality sampling results for the 2009/2010 season</a></p>
<% end_if %>

<p>It is recommended recreational users allow the water to clear before using the beach again after periods of heavy rainfall.</p>

<h3>About $XML.AltSiteName</h3>
<p>
<% if XML.Image3 %>
	<img class="left" src="$ImagesURL/Sites_SwimMarine/$XML.Image3" />
<% end_if %>
$Content
</p>

<div class="clear">&nbsp;</div>

<% if HistoricalXML(2009-07-01) %>
	<a name="table"><h3>Table showing the water quality sampling results for the 2009/2010 season</h3></a>
	<table>
		<tr><th>Date</th><th>$XML.Measurement<br/>[$XML.Unit]</th></tr>
		<% control HistoricalXML(2009-07-01) %>
			<tr>
				<td>$DateTime</td>
				<td class="alignRight">{$NonDetect}{$Value}</td>
			</tr>
		<% end_control %>
	</table>
<% end_if %>

<a href="$Link#top">Back to Top</a>
