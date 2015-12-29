<?php

include_once('./modules/candidates/CandidatesUI.php');

class wsUI extends UserInterface
{
    public function __construct()
    {
        parent::__construct();

        $this->_authenticationRequired = true;
        $this->_moduleDirectory = 'ws';
        $this->_moduleName = 'ws';
        $this->_moduleTabText = '';
        $this->_subTabs = array();
    }


    public function handleRequest()
    {
        echo $_SESSION['CATS']->getSiteID();
		echo ',';
		echo $_SESSION['CATS']->getFullName();
		echo "\n";
		$candidate = new CandidatesUI();
		$candidate -> publicAddCandidateforWS(false, $this->_moduleDirectory);
		
    }

}

?>
