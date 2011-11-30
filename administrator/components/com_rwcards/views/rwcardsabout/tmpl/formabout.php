<?php
/**
* RWCards Template for RWCards
*
* @author Ralf Weber  <ralf@weberr.de>
* @version 3.1
* @copyright Copyright (c) 2007, LoadBrain
* @link http://www.weberr.de/
*
* @license GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');?>
<style type="text/css">
table.admintable td.key
{
	background-color: #f6f6f6;
	text-align: left;
	width:100%;
	color: #666;
	font-weight: bold;
	border-bottom: 1px solid #e9e9e9;
	border-right: 1px solid #e9e9e9;
}
</style>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">

	<table class="admintable" width="100%">
		<tr>
			<td>
			&copy; Ralf Weber - LoadBrain<br />
			<a href="http://www.weberr.de" target="_blank">http://www.weberr.de</a><br />
			Email: <a href="mailto:ralf@weberr.de">ralf@weberr.de</a>
			<br />
			<br />
			For Questions, Bugs, Features, please visit the <a href="http://www.weberr.de/index.php/forum.html" target="_blank">forum</a> or directly the <a href="http://www.weberr.de/bugtracker/" target="_blank">bugtracker system</a>.
			</td>
		<tr>
			<td class="key">History</td>
		</tr>
		<tr>
			<td>
				<p style="font-weight:bold;">Version 3.1.0 (27.10.2010)</p>
				<p>
					<ul>
						<li>Many internal changes and some security fixes by Stephen Brandon (stephen@brandonitconsulting.co.uk)</li>
						<li>changed to standard Joomla configuration of component (parameters view), which makes it easier to add new config options. This means that the old configuration view has been removed.</li>
						<li>removed unused file in captcha component which allowed a XSS vulnerability</li>
						<li>fixed a database insert that used unfiltered code (sql injection)</li>
						<li>added SEF URL support for standard Joomla SEF</li>
						<li>removed all known PHP warnings</li>
						<li>allow "code" for downloads even when captcha is turned off</li>
						<li>better handling of incorrect data in the URL</li>
						<li>fixed broken code for deleting out-of-date cards</li>
						<li>removed all use of _GET and changed to JRequest. This solves all problems with slashes and quotes in input strings.</li>
						<li>component can now be updated simply by installing on top of the old one (without uninstalling first)</li>
					</ul>
				</p>
			</td>
		</tr>
		
		<tr>
			<td>
				<p style="font-weight:bold;">Version 3.0.20 (27.08.2010)</p>
				<p>
					<ul>
						<li>Fixed some minor layout things, switched to code included from Siam</li>
					</ul>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p style="font-weight:bold;">Version 3.0.20 rc 3 (26.03.2010)</p>
				<p>
					<ul>
						<li>Cleanup Code fixed some Problems in RWCards Models by Siam (siam@kiwuweb.de)<br /></li>
					</ul>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p style="font-weight:bold;">Version 3.0.20 rc 2 (24.03.2010)</p>
				<p>
					<ul>
						<li>Added two new fields in the config to adjust the Pagewith & Pagehight for Cardpreview and Cardsend Page by Siam (siam@kiwuweb.de)<br /></li>
					</ul>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p style="font-weight:bold;">Version 3.0.20 rc 1 (23.03.2010)</p>
				<p>
					<ul>
						<li>Fixed HTML to fit XHTML 1.0, cleanup Source-Code, optimized the Url's for searchengines by Siam (siam@kiwuweb.de)<br /></li>
					</ul>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p style="font-weight:bold;">Version 3.0.19 (05.03.2010)</p>
				<p>
					<ul>
						<li>Fixed Directory traversal vulnerability in index.php in the RWCards (com_rwcards) component 3.0.18 for Joomla! <br/>
						Thanks to siam for providing a quick solution.</li>
					</ul>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p style="font-weight:bold;">Version 3.0.18 (11.01.2009)</p>
				<p>
					<ul>
						<li>New stamp for 2010 added, sent by Anke Neumann</li>
					</ul>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p style="font-weight:bold;">Version 3.0.17 (23.07.2009)</p>
				<p>
					<ul>
						<li>Changed <br/>$mail =& JMail::getInstance();<br/>to
						$mail =& JFactory::getMailer();<br/>, email sending should now work for everyone, see<br>
						<a href="http://www.weberr.de/index.php/forum.html?func=view&amp;catid=5&amp;id=619&amp;limit=6&amp;start=12#1465" target="_blank"> in the forum</a></li>
						<li>Removed some css code</li>
						<li>Added full &lt;php for server where short tags are not allowed.</li>
					</ul>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p style="font-weight:bold;">Version 3.0.16 (22.04.2009)</p>
				<p>
					<ul>
						<li>Added working Upload Section to RWCards, Upload is now possible directly in component</li>
						<li>Added contentelemenst for JoomFish.<br/>
						Files get automatically installed if JoomFish is installed<br/><br/>
						Inspired by Jan see: <a href="http://www.joomfish.net/forum/viewtopic.php?f=21&t=3903" target="_blank">http://www.joomfish.net/forum/viewtopic.php?f=21&t=3903</a></li>
						<li>Fixed bug: Only published categories get listed in dropdown field</li>
					</ul>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p style="font-weight:bold;">Version 3.0.15 (04.04.2009)</p>
				<p>
					<ul>
						<li>put a z-index: 9999; in slimbox.css for preview of image to overlay all other elements</li>
					</ul>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p style="font-weight:bold;">Version 3.0.14 (04.01.2009)</p>
				<p>
					<ul>
						<li>only description for each card in frontend is now in title tag of image</li>
						<li>in admin pagination is added to manage sent cards</li>
					</ul>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p style="font-weight:bold;">Version 3.0.13 (12.11.2008)</p>
				<p>
					<ul>
						<li>fixed an error in JS-Code for viewing Front- and Back of card (only concerning IE)</li>
					</ul>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p style="font-weight:bold;">Version 3.0.12 (25.10.2008)</p>
				<p>
					<ul>
						<li>fixed a vulnerability in RWCards which regarding the report, can be exploited by malicious people to disclose sensitive information. See: http://secunia.com/advisories/32367/</li>
					</ul>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p style="font-weight:bold;">Version 3.0.11 (08.10.2008)</p>
				<p>
					<ul>
						<li>fixed problems with path, added to all links, images Juri::base();</li>
						<li>added quotation to sql strings to fix saving when magic_quotes is not on</li>
					</ul>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p style="font-weight:bold;">Version 3.0.10 (02.09.2008)</p>
				<p>
					<ul>
						<li>fixed bug in sending email to several receivers, so that only each one gets exactly one email</li>
					</ul>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p style="font-weight:bold;">Version 3.0.9 (18.07.2008)</p>
				<p>
					<ul>
						<li>Fixed some problems with layout (missing divs)</li>
					</ul>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p style="font-weight:bold;">Version 3.0.8 (24.04.2008)</p>
				<p>
					<ul>
						<li>Refixed check of formula if captcha is configured not to use</li>
					</ul>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p style="font-weight:bold;">Version 3.0.7 (10.04.2008)</p>
				<p>
					<ul>
						<li>Added menu icons to admin interface</li>
						<li>Put Strings for menu description in xml file in CDATA blocks</li>
						<li>Fixed JavaScript for selection of image so that the first image is selected by default when creating a new card</li>
					</ul>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p style="font-weight:bold;">Version 3.0.6 (14.02.2008)</p>
				<p>
					<ul>
						<li>Fixed check of formula if captcha is not used to work</li>
					</ul>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p style="font-weight:bold;">Version 3.0.5 (03.01.2008)</p>
				<p>
					<ul>
						<li>Again several new adaptions on Joomla 1.5 RC4 and some bugfixes</li>
						<li>had to rename all files/dirs with "view" to "look"</li>
						<li>Fixed link in email fo watch the card</li>
					</ul>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p style="font-weight:bold;">Version 3.0.4 (20.12.2007)</p>
				<p>
					<ul>
						<li>Adaption on Joomla 1.5 RC4</li>
					</ul>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p style="font-weight:bold;">Version 3.0.3</p>
				<p>
					<ul>
						<li>As the location of some libs changed in Joomla 1.5 RC3, I had to adept some imports, like jimport('joomla.utilities.mail'); change to jimport('joomla.mail.mail'); </li>
					</ul>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p style="font-weight:bold;">Version 3.0.2</p>
				<p>
					<ul>
						<li>Filenames in rwcards.xml and folders are now exactly the same concerning lower and uppercase</li>
						<li>fixed some minor bugs</li>
					</ul>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p style="font-weight:bold;">Version 3.0.1</p>
				<p>
					<ul>
						<li>new css files added</li>
					</ul>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p style="font-weight:bold;">Version 3.0.0</p>
				<p>
					<ul>
						<li>Using of Joomla 1.5 API</li>
						<li>Thumbnails are created without underscore in name.</li>
						<li>Possibility to send card to multiple receivers</li>
						<li>All JavaScripts and CSS files/functions start with rwcards so there will be no more conflicts</li>
						<li>Preview of categories and cards in animated boxes</li>
						<li>PNG-Support</li>
						<li>Captcha now works always</li>
						<li>Switched from prototype js-library to Joomlas standard mootools, no more conflicts now with other componentes/modules</li><li>Installation checks for GDLib and other needed libraries/directories</li>
					</ul>
				</p>
			</td>
		</tr>
	</table>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_rwcards" />
<input type="hidden" name="task" value="" />
</form>
