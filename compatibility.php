<?
/***********************************************
* File      :   compatibility.php
* Project   :   Z-Push
* Descr     :   
*
* Created   :   03.04.2008
*
* � Zarafa Deutschland GmbH, www.zarafaserver.de
* This file is distributed under GPL v2.
* Consult LICENSE file for details
************************************************/


class Compatibility {
	// saves information about folder data for a specific device	
    public static function saveFolderData($devid, $folders) {
        if(!is_array($folders) || empty($folders))
            return false;

		$unique_folders = array();
		$c =0;
		foreach ($folders as $folder) {
			
			// don't save folder-ids for emails
			if ($folder->type == SYNC_FOLDER_TYPE_INBOX) continue;

			// no folder from that type			
			if (! array_key_exists($folder->type, $unique_folders)) {
				$unique_folders[$folder->type] = $folder->serverid;
			}
			// collision
			else {
				debugLog("Compatibility::CONFLICT! Only one folder per type permitted (type: {$folder->type})");
			}
		}
				
		debugLog("saveFolderData : ". print_r($unique_folders,1));
            
        if (!file_put_contents(BASE_PATH . STATE_DIR . "/compat-$devid", serialize($unique_folders))) {
        	debugLog("Compatibility::saveFolderData: Data could not be saved!");
        }
    }

	// returns information about folder data for a specific device	
    public static function getFolderID($devid, $class) {
        $filename = BASE_PATH . STATE_DIR . "/compat-$devid";
        
        if(file_exists($filename)) {
            $arr = unserialize(file_get_contents($filename));

			if ($class == "Calendar") return $arr[SYNC_FOLDER_TYPE_APPOINTMENT];
			if ($class == "Contacts") return $arr[SYNC_FOLDER_TYPE_CONTACT];
			        	

        }

       	return false;
    }
    
	
};

?>
