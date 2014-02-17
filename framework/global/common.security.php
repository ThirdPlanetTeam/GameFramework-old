<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013-2014 Third Planet Team *
 **********************************/

/*******
 * Part of this code come from Sebastien Sauvage 
 * Sources here : http://sebsauvage.net/paste/?36dbd6c6be607e0c#M5uR8ixXo5rXBpXx32gOATLraHPffhBJEeqiDl1dMhs
 * http://sebsauvage.net/links/?kO4Krg
 *******/


class GFCommonSecurity {

	const DataDir = 'log';
	const Filename = 'ipbans.php';
	const DefaultBanAfter = 5;
	const DefaultBanDuration = 1800;

	private $BanAfter;
	private $BanDuration;
   
    public function __construct($BanAfter = GFCommonSecurity::DefaultBanAfter, $BanDuration = GFCommonSecurity::DefaultBanDuration) {
        

    	$this->BanAfter = $BanAfter;
    	$this->BanDuration = $BanDuration;


        if (!is_dir(GFCommonSecurity::getDir())) 
        { 
        	mkdir(GFCommonSecurity::getDir(),0705); 
        	chmod(GFCommonSecurity::getDir(),0705); 
        }

        /*if (!is_file(GFCommonSecurity::getDir().'/.htaccess')) 
        { 
        	file_put_contents($GLOBALS['config']['DATADIR'].'/.htaccess',"Allow from none\nDeny from all\n"); 
        } // Protect data files. */

		if (!is_file(GFCommonSecurity::getFilname())) 
		{
			file_put_contents(GFCommonSecurity::getFilname(), "<?php\n\$GLOBALS['IPBANS']=".var_export(array('FAILURES'=>array(),'BANS'=>array()),true).";\n?>");
		}

		include GFCommonSecurity::getFilname();        
    }


    public static function getFilname()
    {
    	return GFCommonSecurity::getDir() . '/' . GFCommonSecurity::Filename;
    }

    public static function getDir()
    {
    	return SERVER_ROOT . '/' . GFCommonSecurity::DataDir;
    }

    public static function refreshPermissions($userid, $force=false) {

        if(isset($_SESSION['permschecked'][$userid]) && !$force) {
            // No refresh needed
            return;
        }

        $perms = Modeles::getModel('account', 'permissions');

        $liste_perms = $perms->getPermissions($userid);

        unset($_SESSION['token']['admin']);
        unset($_SESSION['token']['perms']);

        if(is_array($liste_perms)) {

            foreach ($liste_perms as $line) {

                if($line[PermissionsModel::PERM_FIELD] != null) {
                    $key = $line[PermissionsModel::PERM_FIELD];

                    $_SESSION['token']['perms'][$key] = true;

                    if($key == 'ADMIN') {
                        $_SESSION['token']['admin'] = true;
                    }
                }

                $isadmin = $line[PermissionsModel::ADMIN_FIELD];

                if($isadmin != null && $isadmin == true) {
                     $_SESSION['token']['admin'] = true;
                }
            }


            $_SESSION['permschecked'][$userid] = true;
        }
    }

    public static function getBrowserInfo() {
        $browser = [];

        $browser['user-agent'] = $_SERVER['HTTP_USER_AGENT'];
        $browser['lang'] = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $browser['encoding'] = $_SERVER['HTTP_ACCEPT_ENCODING'];

        return $browser;        
    }

    // ------------------------------------------------------------------------------------------
    // Brute force protection system
    // Several consecutive failed logins will ban the IP address for 30 minutes.

    // Signal a failed login. Will ban the IP if too many failures:
    public function loginFailed()
    {
        $ip=$_SERVER["REMOTE_ADDR"]; 
        $gb=$GLOBALS['IPBANS'];

        if (!isset($gb['FAILURES'][$ip])) {
        	$gb['FAILURES'][$ip]=0;
        } 

        $gb['FAILURES'][$ip]++;

        if ($gb['FAILURES'][$ip]>($this->BanAfter - 1))
        {
            $gb['BANS'][$ip]=time()+$this->BanDuration;
            $this->Log('IP address banned from login');
        }

        $GLOBALS['IPBANS'] = $gb;

        $this->WriteBanFile($gb);
    }

    // Unauthorized place
    public function unauthorizedPlace()
    {
        $ip=$_SERVER["REMOTE_ADDR"]; 
        $gb=$GLOBALS['IPBANS'];

        if (!isset($gb['FAILURES'][$ip])) {
        	$gb['FAILURES'][$ip]=0;
        } 

        $gb['FAILURES'][$ip]++;

        if ($gb['FAILURES'][$ip]>($this->BanAfter - 1))
        {
            $gb['BANS'][$ip]=time()+$this->BanDuration;
            $this->Log('IP address banned from admin section');
        }

        $GLOBALS['IPBANS'] = $gb;

        $this->WriteBanFile($gb);
    }    

    // Signals a successful login. Resets failed login counter.
    public function loginOk()
    {
        $ip=$_SERVER["REMOTE_ADDR"]; 
        $gb=$GLOBALS['IPBANS'];

        unset($gb['FAILURES'][$ip]); 
        unset($gb['BANS'][$ip]);

        $GLOBALS['IPBANS'] = $gb;

        $this->WriteBanFile($gb);
        $this->Log('Login ok.');
    }

    // Checks if the user CAN login. If 'true', the user can try to login.
    public function canLogin()
    {
        $ip = $_SERVER["REMOTE_ADDR"]; 
        $gb = $GLOBALS['IPBANS'];

        if (isset($gb['BANS'][$ip]))
        {
            // User is banned. Check if the ban has expired:
            if ($gb['BANS'][$ip]<=time())
            { // Ban expired, user can try to login again.
                $this->Log('Ban lifted.');
                unset($gb['FAILURES'][$ip]); 
                unset($gb['BANS'][$ip]);
                $this->WriteBanFile($gb);
                return true; // Ban has expired, user can login.
            }
            return false; // User is banned.
        }
        return true; // User is not banned.
    }    


    public function Log($message)
    {
        $t = strval(date('Y/m/d_H:i:s')).' - '.$_SERVER["REMOTE_ADDR"].' - '.strval($message)."\n";
        file_put_contents(GFCommonSecurity::getDir().'/security.txt',$t,FILE_APPEND);
    }    

    private function WriteBanFile($content) {
		file_put_contents(GFCommonSecurity::getFilname(), "<?php\n\$GLOBALS['IPBANS']=".var_export($content,true).";\n?>");
    }
}

