<?php /* $Id: Reports.tpl 3304 2007-10-25 17:31:55Z will $ */ ?>
<?php TemplateUtility::printHeader('Customized Reports'); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active); ?>
    <div id="main">
        <?php TemplateUtility::printQuickSearch(); ?>

        <div id="contents">
            <table>
                <tr>
                    <td width="3%">
                        <img src="images/reports.gif" width="24" height="24" border="0" alt="Reports" style="margin-top: 3px;" />&nbsp;
                    </td>
                    <td><h2>Customized Reports</h2></td>
                </tr>
            </table>

            <p class="note">Customized Reports</p>

            <table border="0" width="925">
				<tr>
				<?php
                $users = new Users($_SESSION['CATS']->getSiteID());
				$AllUsers = $users -> getAllRecrutiers();
				$count =0;
				foreach($AllUsers as $row){
					echo "".
					"<td width=\"320\">".
					"<table class=\"statisticsTable\" width=\"300\">".
						"<tr>".
							"<th align=\"left\">".$row['firstName']." ".$row['lastName']."</th>".
							"<th align=\"left\">&nbsp;&nbsp;</th>".
						"</tr>".

						"<tr class=\"evenTableRow\">".
							"<td align=\"left\">New Job Orders</td>".
							"<td align=\"right\"></td>".
						"</tr>".
					"</table>".
				"</td>";
					$count++;
					if($count == 3){
						echo "</tr><tr>";
						$count = 0;
					}						
				}
				?>
				</tr>
            </table>
        </div>
    </div>
    <div id="bottomShadow"></div>
<?php TemplateUtility::printFooter(); ?>
