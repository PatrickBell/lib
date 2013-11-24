<?php

class NcsMockServer extends RestfulServer {
	
	public static $cemetery_list = <<<EOF
<h2>Listing Cemetery Records</h2>
<br/>
<table width="100%">
  <tr>
    <th>Name</th>
    <th>Cemetery</th>
    <th>Date of Death</th>
    <th>Age</th>

  </tr>

  <tr>
    <td><a href="/cemeteries/01962">Easton, Elizabeth (Biddy)</a></td>
    <td>Motueka</td>
    <td>26/01/1945</td>
    <td>89 years</td>

  </tr>
  <tr>
    <td><a href="/cemeteries/01963">Easton, Lockhard Dobbie</a></td>
    <td>Motueka</td>
    <td>10/04/1914</td>
    <td>59 years</td>
  </tr>

  <tr>
    <td><a href="/cemeteries/01988">Easton, Phyllis Margaret (Peggy)</a></td>
    <td>Motueka</td>
    <td>27/01/1949</td>
    <td>27 years</td>
  </tr>
</table>

<br/>
<p></p>
<div class="clear"/></div>
EOF;

	public static $cemetery_detail = <<<EOF
<h2>Cemetery Record Details</h2>

<table width="100%">
<tr>
	<td width="34%"><b>Surname:</b></td>
  	<td width="66%">Smith</td>
</tr>

<tr>
	<td><b>First names:</b></td>

  	<td>Betty Violet</td>
</tr>


<tr>
	<td><b>Gender:</b></td>
  	<td>Female</td>
</tr>


<tr>

	<td><b>Age:</b></td>
  	<td>77 years</td>
</tr>


<tr>
	<td><b>Date of Birth:</b></td>
  	<td> 5/05/1931</td>

</tr>


<tr>
	<td><b>Date of Death:</b></td>
  	<td>23/11/2008</td>
</tr>

<tr>
	<td><b>Date of Interment:</b></td>
  	<td>27/11/2008</td>

</tr>


<tr>
	<td><b>Occupation:</b></td>
  	<td>Nurse</td>
</tr>


<tr>
	<td><b>Cemetery:</b></td>

  	<td>Motueka</td>
</tr>


<tr>
	<td><b>Plot Location:</b></td>
  	<td><em>Block:</em> RSA<br/>
<em>Row:</em> 2<br/>

<em>Plot:</em> 76</td>
</tr>


<tr>
	<td><b>Funeral Director:</b></td>
  	<td>Waimea Richmond Funeral Services</td>
</tr>


<tr>
	<td><b>Notes:</b></td>
  	<td>With: Raymond Kenneth Smith</td>
</tr>


</table>

  <h2>Other Interments in this Plot</h2>
<p><a href="/cemeteries/30187">Smith, Raymond Kenneth</a><br/>

  </p>

<div class="clear"/></div>
EOF;

	public static $cemetery_list_not_found = <<<EOF
<h2>No Records Found</h2>
<p>Try searching using just the surname or partial surname.</p>
<p></p>
<div class="clear"/></div>
EOF;

	public static $property_detail = <<<EOF
<h2>Property Details</h2>


<table width="100%">
<tr>
  <td width="34%"><b>Valuation No.</b></td>
  <td width="66%">1870006001</td>
</tr>

<tr>
  <td><b>Location</b></td>

  <td>17 Rangihaeata Road, Takaka</td>
</tr>


<tr>
  <td><b>Legal Description</b></td>
  <td>PT LOT 1 DP 13030</td>
</tr>

<tr>
  <td><b>Certificate of Title</b></td>

  <td>10B/426</td>
</tr>

<tr>
  <td><b>Ward No.</b></td>
  <td>1</td>
</tr>


<tr>
  <td><b>Property Area (hectares)</b></td>

  <td>6.4507</td>
</tr>
</table>

<h2>Current Rating Valuation</h2>
<p>As valued at 1/09/2008</p>

<table width="100%">
<tr>
  <td width="34%"><b>Land Value</b></td>
  <td width="66%" style="text-align:right;">$440,000</td>

</tr>

<tr>
  <td><b>Improvements Value</b></td>
  <td style="text-align:right;">$160,000</td>
</tr>

<tr>
  <td><b>Capital Value</b></td>
  <td style="text-align:right;">$600,000</td>

</tr>

<tr>
  <td><b>Nature of Improvements</b></td>
  <td style="text-align:right;"><a href="/property/rates/rate-record-search/improvement-codes-for-rateable-property-search/">DWG FG OI</a></td>
</tr>
</table>

<h2>Rates Information</h2>

<table width="100%">
<tr>

  <td width="34%"><b>Current Rating Year</b></td>
  <td  width="66%" style="text-align:right;">2010/2011</td>
</tr>

<tr>
  <td><b>Current Year's Rates</b></td>
  <td style="text-align:right;">$2,038.80</td>
</tr>

<!--<tr>
  <td colspan="2"><b>Current Year Rates Instalments</b></td></tr>
  <tr><td>Instalment 1.</td><td style="text-align:right;"> $2,499.75</td></tr>
  <tr><td>Instalment 2.</td><td style="text-align:right;"> $2,499.75</td></tr>
  <tr><td>Instalment 3.</td><td style="text-align:right;"> $2,499.75</td></tr>
  <tr><td>Instalment 4.</td><td style="text-align:right;"> $2,499.75</td></tr>
</tr>

<tr><td colspan="2">&nbsp;</td></tr>//-->
<tr>
  <td><b>Previous Year's Rates</b></td>
  <td style="text-align:right;">$1,929.70</td>
</tr>
</table>

<h2>Rates for Current Year 2010/2011</h2>

<table style="width: 100%">
  <tr>

    <th style="text-align: left;">Type</th>
    <th style="text-align: left;">Description (Basis)</th>
    <th style="text-align: left;">Differential</th>
    <th style="text-align: right;">Factor</th>
    <th style="text-align: right;">Amount</th>
  </tr>

  <tr>
    <td>1</td>
    <td>General Rate (C)</td>
    <td>Use</td>
    <td style="text-align:right;">600000.00</td>
    <td style="text-align:right;">$1,339.20</td>

  </tr>
  <tr>
    <td>10</td>
    <td>Uniform Annual General Charge (U)</td>
    <td>Districtwide</td>
    <td style="text-align:right;">1.00</td>
    <td style="text-align:right;">$264.33</td>

  </tr>
  <tr>
    <td>88</td>
    <td>General District Drainage (C)</td>
    <td>Location</td>
    <td style="text-align:right;">600000.00</td>
    <td style="text-align:right;">$25.80</td>

  </tr>
  <tr>
    <td>108</td>
    <td>Refuse/Recycling Rate (U)</td>
    <td>Location</td>
    <td style="text-align:right;">1.00</td>
    <td style="text-align:right;">$116.90</td>

  </tr>
  <tr>
    <td>109</td>
    <td>Shared Facilities Rate (U)</td>
    <td>Districtwide</td>
    <td style="text-align:right;">1.00</td>
    <td style="text-align:right;">$52.16</td>

  </tr>
  <tr>
    <td>120</td>
    <td>Mapua Rehabilitation Rate (U)</td>
    <td>Districtwide</td>
    <td style="text-align:right;">1.00</td>
    <td style="text-align:right;">$12.20</td>

  </tr>
  <tr>
    <td>125</td>
    <td>Museums Facilities Rate (U)</td>
    <td>Districtwide</td>
    <td style="text-align:right;">1.00</td>
    <td style="text-align:right;">$54.10</td>

  </tr>
  <tr>
    <td>126</td>
    <td>District Facilities Rate (U)</td>
    <td>Districtwide</td>
    <td style="text-align:right;">1.00</td>
    <td style="text-align:right;">$41.84</td>

  </tr>
  <tr>
    <td>129</td>
    <td>Golden Bay Community Board (U)</td>
    <td>Location</td>
    <td style="text-align:right;">1.00</td>
    <td style="text-align:right;">$17.20</td>

  </tr>
  <tr>
    <td>152</td>
    <td>Takaka Firefighting - Ward (U)</td>
    <td>Location</td>
    <td style="text-align:right;">1.00</td>
    <td style="text-align:right;">$15.20</td>

  </tr>
  <tr>
    <td>174</td>
    <td>River Works - Z Classification (L)</td>
    <td>Location</td>
    <td style="text-align:right;">440000.00</td>
    <td style="text-align:right;">$99.88</td>

  </tr>

  <tr>
    <td colspan="2"><b>Total Annual Rates:</b></td>
    <td colspan="4" style="text-align: right;"><b>$2,038.81</b></td>
  </tr>
</table>

<h2>Water</h2>

<table style="width: 100%">
  <tr>
    <th style="text-align: left;">Account</th>
    <th style="text-align: left;">Location</th>
    <th style="text-align: left;">Meter Id</th>
    <th style="text-align: right;">Charged YTD</th>
    <th style="text-align: right;">Last Year</th>

  </tr>

</table>

<h2>History</h2>

<table style="width: 100%">
  <tr>
    <th style="width: 25%;">Year</th>
    <th style="width: 25%;">Land Value</th>

    <th style="width: 25%;">Capital Value</th>
    <th style="width: 25%;">Annual Rates</th>
  </tr>
  <tr>
    <td>2009/2010</td>
    <td style="text-align: right;">$440,000</td>
    <td style="text-align: right;">$600,000</td>

    <td style="text-align: right;">$1,929.70</td>
  </tr>  <tr>
    <td>2008/2009</td>
    <td style="text-align: right;">$410,000</td>
    <td style="text-align: right;">$550,000</td>
    <td style="text-align: right;">$1,847.90</td>

  </tr>  <tr>
    <td>2007/2008</td>
    <td style="text-align: right;">$410,000</td>
    <td style="text-align: right;">$550,000</td>
    <td style="text-align: right;">$1,721.90</td>
  </tr>  <tr>

    <td>2006/2007</td>
    <td style="text-align: right;">$410,000</td>
    <td style="text-align: right;">$550,000</td>
    <td style="text-align: right;">$1,644.70</td>
  </tr>  <tr>
    <td>2005/2006</td>

    <td style="text-align: right;">$192,000</td>
    <td style="text-align: right;">$330,000</td>
    <td style="text-align: right;">$1,564.65</td>
  </tr>  <tr>
    <td>2004/2005</td>
    <td style="text-align: right;">$192,000</td>

    <td style="text-align: right;">$330,000</td>
    <td style="text-align: right;">$1,386.65</td>
  </tr>  <tr>
    <td>2003/2004</td>
    <td style="text-align: right;">$192,000</td>
    <td style="text-align: right;">$330,000</td>

    <td style="text-align: right;">$1,326.25</td>
  </tr>  <tr>
    <td>2002/2003</td>
    <td style="text-align: right;">$153,000</td>
    <td style="text-align: right;">$280,000</td>
    <td style="text-align: right;">$1,239.15</td>

  </tr>  <tr>
    <td>2001/2002</td>
    <td style="text-align: right;">$153,000</td>
    <td style="text-align: right;">$280,000</td>
    <td style="text-align: right;">$1,183.95</td>
  </tr>  <tr>

    <td>2000/2001</td>
    <td style="text-align: right;">$153,000</td>
    <td style="text-align: right;">$280,000</td>
    <td style="text-align: right;">$1,159.45</td>
  </tr>
</table>

<h2>Property Image</h2>


<img width='300' height='300' src='http://gis.tdc.govt.nz/SpatialMedia/Insertmap.asp?service=ExploreNCS&layername=Parcel&query=var%3D1870006001@Valuation+Assessment&zoomby=201&zoomispercent=false&test=false'/>


<div class="clear"/></div>
EOF;

	public static $property_list = <<<EOF
<h2>Listing Properties</h2>
<div class="pagination"><span class="disabled prev_page">&laquo; Previous</span> <span class="current">1</span> <a href="/properties?page=2&amp;street%5Baddress_1%5D=takaka" rel="next">2</a> <a href="/properties?page=3&amp;street%5Baddress_1%5D=takaka">3</a> <a href="/properties?page=4&amp;street%5Baddress_1%5D=takaka">4</a> <a href="/properties?page=5&amp;street%5Baddress_1%5D=takaka">5</a> <a href="/properties?page=6&amp;street%5Baddress_1%5D=takaka">6</a> <a href="/properties?page=7&amp;street%5Baddress_1%5D=takaka">7</a> <a href="/properties?page=8&amp;street%5Baddress_1%5D=takaka">8</a> <a href="/properties?page=9&amp;street%5Baddress_1%5D=takaka">9</a> <span class="gap">&hellip;</span> <a href="/properties?page=22&amp;street%5Baddress_1%5D=takaka">22</a> <a href="/properties?page=23&amp;street%5Baddress_1%5D=takaka">23</a> <a href="/properties?page=2&amp;street%5Baddress_1%5D=takaka" class="next_page" rel="next">Next &raquo;</a></div><br/>

<table width="100%">
  <tr>
    <th>Valuation No</th>
    <th>Location</th>
  </tr>
  <tr>
    <td><a href="/properties/2197">1871041000</a></td>
    <td> Central Takaka Road, Takaka</td>

  </tr>
  <tr>
    <td><a href="/properties/2208">1871042000</a></td>
    <td> Central Takaka Road, Takaka</td>
  </tr>
  <tr>
    <td><a href="/properties/25347">1871041101</a></td>

    <td> Central Takaka Road, Takaka</td>
  </tr>
  <tr>
    <td><a href="/properties/2199">1871041100</a></td>
    <td> Central Takaka Road, Takaka</td>
  </tr>

  <tr>
    <td><a href="/properties/21097">1871040401</a></td>
    <td>Central Takaka Road, Takaka</td>
  </tr>
  <tr>
    <td><a href="/properties/21797">1871042101</a></td>
    <td>Central Takaka Road, Takaka</td>

  </tr>
  <tr>
    <td><a href="/properties/2193">1871040800</a></td>
    <td>10 Central Takaka Road, Takaka</td>
  </tr>
  <tr>
    <td><a href="/properties/22523">1871041002</a></td>

    <td>17 Central Takaka Road, Takaka</td>
  </tr>
  <tr>
    <td><a href="/properties/2201">1871041300</a></td>
    <td>19 Central Takaka Road, Takaka</td>
  </tr>
  <tr>

    <td><a href="/properties/2200">1871041200</a></td>
    <td>29 Central Takaka Road, Takaka</td>
  </tr>
  <tr>
    <td><a href="/properties/2190">1871040500</a></td>
    <td>35 Central Takaka Road, Takaka</td>
  </tr>

  <tr>
    <td><a href="/properties/2202">1871041400</a></td>
    <td>37 Central Takaka Road, Takaka</td>
  </tr>
  <tr>
    <td><a href="/properties/2194">1871040900</a></td>
    <td>40 Central Takaka Road, Takaka</td>

  </tr>
  <tr>
    <td><a href="/properties/2204">1871041600</a></td>
    <td>55 Central Takaka Road, Takaka</td>
  </tr>
  <tr>
    <td><a href="/properties/2203">1871041500</a></td>

    <td>57 Central Takaka Road, Takaka</td>
  </tr>
  <tr>
    <td><a href="/properties/2205">1871041700</a></td>
    <td>70 Central Takaka Road, Takaka</td>
  </tr>
  <tr>

    <td><a href="/properties/2207">1871041900</a></td>
    <td>71 Central Takaka Road, Takaka</td>
  </tr>
  <tr>
    <td><a href="/properties/2206">1871041800</a></td>
    <td>82 Central Takaka Road, Takaka</td>
  </tr>

  <tr>
    <td><a href="/properties/2212">1871042400</a></td>
    <td>86 Central Takaka Road, Takaka</td>
  </tr>
  <tr>
    <td><a href="/properties/2209">1871042100</a></td>
    <td>87 Central Takaka Road, Takaka</td>

  </tr>
</table>
<div class="pagination"><span class="disabled prev_page">&laquo; Previous</span> <span class="current">1</span> <a href="/properties?page=2&amp;street%5Baddress_1%5D=takaka" rel="next">2</a> <a href="/properties?page=3&amp;street%5Baddress_1%5D=takaka">3</a> <a href="/properties?page=4&amp;street%5Baddress_1%5D=takaka">4</a> <a href="/properties?page=5&amp;street%5Baddress_1%5D=takaka">5</a> <a href="/properties?page=6&amp;street%5Baddress_1%5D=takaka">6</a> <a href="/properties?page=7&amp;street%5Baddress_1%5D=takaka">7</a> <a href="/properties?page=8&amp;street%5Baddress_1%5D=takaka">8</a> <a href="/properties?page=9&amp;street%5Baddress_1%5D=takaka">9</a> <span class="gap">&hellip;</span> <a href="/properties?page=22&amp;street%5Baddress_1%5D=takaka">22</a> <a href="/properties?page=23&amp;street%5Baddress_1%5D=takaka">23</a> <a href="/properties?page=2&amp;street%5Baddress_1%5D=takaka" class="next_page" rel="next">Next &raquo;</a></div><br/>

<p></p>

<div class="clear"/></div>
EOF;

	public static $property_not_found = <<<EOF
<h2>No Properties Found</h2>
<p>Try searching using just the street name or partial street name.</p>
<p></p>

<div class="clear"/></div>
EOF;

	function cemeteries() {
		$id = $this->request->param('ID');
		if (is_numeric($id)) {
			if ($id==31643) {
				return self::$cemetery_detail;
			}
			else {
				// Failed, the ncs service crashes in this case, so we replicate this behaviour here
		        $this->response->setStatusCode(500);
				$this->response->setBody('crashed');
				$this->response->addHeader('Content-Length',0);
				return $this->response;
			}
		}
		else {
			if ($this->request->requestVar('surname')=='Easton' && $this->request->requestVar('cemetery_code')=='MOT') {
				return self::$cemetery_list;
			}
			else {
				return self::$cemetery_list_not_found;
			}
		}
	}

	function properties() {
		$id = $this->request->param('ID');
		if (is_numeric($id)) {
			if ($id==1010) {
				return self::$property_detail;
			}
			else {
				// Failed, the ncs service crashes in this case, so we replicate this behaviour here
		        $this->response->setStatusCode(500);
				$this->response->setBody('crashed');
				$this->response->addHeader('Content-Length',0);
				return $this->response;
			}
		}
		else {
			$street = $this->request->requestVar('street');
			if ($street['address_1']=='Takaka') {
				return self::$property_list;
			}
			else {
				return self::$property_not_found;
			}
		}
	}
}
