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
                    <td><h2>Reports</h2></td>
                </tr>
            </table>

            <p class="note">Reports</p>

            <table border="0" width="925">
			<?php
                $users = new Users($_SESSION['CATS']->getSiteID());
				$AllUsers = $users -> getAll();
				foreach($AllUsers as $row){
					foreach($row as $cell)
					echo $cell . "\t";
					echo "<br />";
				}
				?>
            </table>
        </div>
    </div>
    <div id="bottomShadow"></div>
<?php TemplateUtility::printFooter(); ?>
