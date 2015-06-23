<?php

require_once("../conf/DBConf.php");

class IpModel {
	
	private $address = "0.0.0.0";
	private $lastAccess = "";
    private $description = "";
		
    public function __construct($address="0.0.0.0") {
        global $DB_FILE;
        $this->address = $address;
        if ($address != "0.0.0.0") {
            $db = new SQLite3($DB_FILE);
            $db->busyTimeout(1500);
            $res = $db->query("SELECT COUNT(*), address, lastaccess, description FROM ip WHERE address = '" . $address . "'");
            $resArr = $res->fetchArray();
            if ($resArr[0] > 0) {
                if (isset($resArr[1])) $this->setAddress($resArr[1]);
                if (isset($resArr[2])) $this->setLastAccess($resArr[2]);
                if (isset($resArr[3])) $this->setDescription($resArr[3]);
                $res = $db->query("SELECT COUNT(*), address, lastaccess, description FROM ip WHERE address = '" . $address . "'");

                while ($resArr = $res->fetchArray()) {
                    print "Current IP: " . $resArr[1] . "<br />";
                    print "Last Access: " . $resArr[2] . "<br />";
                    print "Description:" . $resArr[3] . "<br />";
                }
            }
            $db->close();
			unset($db);
        }
    }
	
	public function touch() {
		if ($this->getAddress() != "0.0.0.0") {
			global $DB_FILE;
			$db = new SQLite3($DB_FILE);
                        $db->busyTimeout(1500);
			$stmt = $db->prepare("UPDATE ip SET lastAccess=datetime('now', 'localtime') WHERE address = :address");
			$stmt->bindValue(":address", $this->getAddress(), SQLITE3_TEXT);
			if (!$res = $stmt->execute()) {
				print "Couldn't update IP!";
			}
                        $stmt->close();
			$db->close();
			unset($db);
		}
	}
	
	public function save($ip) {
		if (isset($ip['address'])) {
			$this->setAddress($ip['address']);
		} if (isset($ip['lastAccess'])) {
			$this->setLastAccess($ip['lastAccess']);
		}
		if (isset($ip['description'])) {
			$this->setDescription($ip['description']);
		}

		$this->persist();
	}
	
	public function persist() {
		global $DB_FILE;
		$db = new SQLite3($DB_FILE);
		$res = $db->query("SELECT COUNT(*) FROM ip WHERE address = '" . $this->getAddress() . "'");
		$resArr = $res->fetchArray();
		if ($resArr[0] > 0) {
			$stmt = $db->prepare("UPDATE ip SET lastAccess=:lastAccess, description=:description WHERE address = :address");
			$stmt->bindValue(":address", $this->getAddress(), SQLITE3_TEXT);
			$stmt->bindValue(":lastAccess", $this->getLastAccess());
            $stmt->bindValue(":description", $this->getDescription(), SQLITE3_TEXT);
			if (!$res = $stmt->execute()) {
				print "Couldn't update IP!";
			}
		} else {
			$stmt = $db->prepare("INSERT INTO ip(address, lastAccess, description) VALUES(:address, :lastAccess, :description)");
			$stmt->bindValue(":address", $this->getAddress(), SQLITE3_TEXT);
			$stmt->bindValue(":lastAccess", $this->getLastAccess());
            $stmt->bindValue(":description", $this->getDescription(), SQLITE3_TEXT);
            if (!$stmt->execute()) {
				print("Couldn't insert new IP!");
			}
		}
		$stmt->close();
		$db->close();
		unset($db);
	}
	
	public function getAddress() {
		return $this->address;
	}
	
	public function setAddress($address) {
		$this->address = $address;
	}
	
	public function getlastAccess() {
		return $this->lastAccess;
	}
	
	public function setlastAccess($lastAccess) {
		$this->lastAccess = $lastAccess;
	}

	public function getDescription() {
		return $this->description;
	}
	
	public function setDescription($description) {
		$this->description = $description;
	}
}
?>
