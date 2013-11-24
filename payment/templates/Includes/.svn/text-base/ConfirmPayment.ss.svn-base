<% if PaymentSuccess == 0 %>
<h1>Payment Not Approved</h1>
<p>Your payment has not been approved. Please check your card details and available funds.</p>
<p><a href="$PaymentRepeatLink">Return to payment screen.</a></p>
<% else %>

<% control PaymentReceipt %>
<% if Expire == 1 %>
<h1>Online Receipt #$ID has Expired</h1>
<p>The receipt you are trying to view has expired. We have emailed this receipt to the email address supplied in the transaction.</p>
<p>If you have not received this e-mail receipt please check your Junk/ Spam folder.</p>
<ul>
<li><a href="#" onclick="javascript: window.print();">Print this page for your records</a></li>
<li><a href="/contact-us/#Accounts">Contact Accounts at the Tasman District Council</a></li>
</ul>
<% else %>
<h1>Payment Successful</h1>
<p>Thank you for your payment of $$TotalAmount. We have emailed this receipt to $EmailAddress.</p>
<p>If you do not receive this e-mail receipt please check your Junk/ Spam folder.</p>
<ul>
<li><a href="#" onclick="javascript: window.print();">Print this page for your records</a></li>
<li><a href="/contact-us/#Accounts">Contact Accounts at the Tasman District Council</a></li>
</ul>
<style type="text/css">
tbody, tfoot, thead, tr, th, td {
    border: none 0px #FFFFFF;
}

h2.tableformat {
    margin-top: 0em;
    border: none 0px;
}
</style>
<table>
<tr><td colspan="2"><h2 class="tableformat">Receipt - Tasman District Council</h2></td></tr>
<tr><td width="50%">GST#</td><td width="50%" style="text-align: right;">51-076-806</td></tr>
<tr><td>Online Payment#</td><td style="text-align: right;">$ID</td></tr>
<tr><td>Date</td><td style="text-align: right;">$LastEdited</td></tr>

<tr><td>Reference Number</td><td style="text-align: right;">$Reference</td></tr>
<tr><td>Contact Details</td><td style="text-align: right;">$PersonalDetails<br />$EmailAddress</td></tr>

<tr><td>Amount</td><td style="text-align: right;">$$SettlementAmount</td></tr>
<tr><td>Convenience Fee</td><td style="text-align: right;">$$FeeAmount</td></tr>
<tr><td>Total Amount Paid (include GST.)</td><td style="text-align: right;">$$TotalAmount</td></tr>
</table>
<% end_if %>
<% end_control %>
<% end_if %>
