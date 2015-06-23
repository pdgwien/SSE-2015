<?php

require_once("../conf/DBConf.php");

class CardModel {
	
	private $id = 0;
	private $num = 0;
	private $validMonth = 0;
	private $validYear = 0;
	private $cvv = 0; 
	private $owner = "";
		
	public function __construct($id=0) {
		global $DB_FILE;
		if ($id != 0) {
			$db = new SQLite3($DB_FILE);
                        $db->busyTimeout(1500);
			$res = $db->query("SELECT COUNT(*), * FROM cards WHERE id = " . $id);
			$resArr = $res->fetchArray();
			if ($resArr[0] > 0) { // are there any rows?
				$this->setId($resArr['id']);
				$this->setNum($resArr['num']);
				$this->setValidMonth($resArr['validMonth']);
				$this->setValidYear($resArr['validYear']);
				$this->setCvv($resArr['cvv']);
				$this->setOwner($resArr['owner']);
			}
			$db->close();
			unset($db);
		}
	}
	
	public function validate($number, $owner, $validMonth, $validYear, $cvv) {
		global $DB_FILE;
		$db = new SQLite3($DB_FILE);
                $db->busyTimeout(1500);
		$stmt = $db->prepare("SELECT COUNT(*) FROM cards WHERE num = :num AND owner = :owner AND validMonth = :validMonth AND validYear = :validYear AND cvv = :cvv");
		$stmt->bindValue(":num", $number, SQLITE3_TEXT);
		$stmt->bindValue(":validMonth", $validMonth, SQLITE3_INTEGER);
		$stmt->bindValue(":validYear", $validYear, SQLITE3_INTEGER);
		$stmt->bindValue(":cvv", $cvv, SQLITE3_INTEGER);
		$stmt->bindValue(":owner", $owner, SQLITE3_TEXT);
		$res = $stmt->execute();
		if (!$res) {
			print("Couldn't validate card!");
			return false;
		} else {
			$resArr = $res->fetchArray();
			if ($resArr[0] > 0) {
				return true;
			} else {
				return false;
			}
		}
        $stmt->close();
		$db->close();
		unset($db);
	}
	
	public function save($card) {
		if (isset($card['id'])) {
			$this->setId($card['id']);
		} if (isset($card['num'])) {
			$this->setNum($card['num']);
		} if (isset($card['validMonth'])) {
			$this->setValidMonth($card['validMonth']);
		} if (isset($card['validYear'])) {
			$this->setValidYear($card['validYear']);
		} if (isset($card['cvv'])) {
			$this->setCvv($card['cvv']);
		} if (isset($card['owner'])) {
			$this->setOwner($card['owner']);
		}
		
		$this->persist();
	}
	
	public function persist() {
		global $DB_FILE;
		$db = new SQLite3($DB_FILE);
                $db->busyTimeout(1500);
                if ($this->getId() == 0) {
                    $stmt = $db->prepare("INSERT INTO cards(num, validMonth, validYear, cvv, owner) VALUES(:num, :validMonth, :validYear, :cvv,  :owner)");
                        
                    $stmt->bindValue(":num", $this->getNum(), SQLITE3_TEXT);
                    $stmt->bindValue(":validMonth", $this->getValidMonth(), SQLITE3_INTEGER);
                    $stmt->bindValue(":validYear", $this->getValidYear(), SQLITE3_INTEGER);
                    $stmt->bindValue(":cvv", $this->getCvv(), SQLITE3_INTEGER);
                    $stmt->bindValue(":owner", $this->getOwner(), SQLITE3_TEXT);
                    if (!$stmt->execute()) {
                        print("Couldn't insert new card!");
                    }
		} else {
                    $stmt = $db->prepare("UPDATE cards SET num=:num, validMonth=:validMonth, validYear=:validYear, cvv = :cvv,  owner = :owner WHERE id = :id");
                    $stmt->bindValue(":num", $this->getNum(), SQLITE3_INTEGER);
                    $stmt->bindValue(":validMonth", $this->getValidMonth(), SQLITE3_INTEGER);
                    $stmt->bindValue(":validYear", $this->getValidYear(), SQLITE3_INTEGER);
                    $stmt->bindValue(":cvv", $this->getCvv(), SQLITE3_INTEGER);
                    $stmt->bindValue(":owner", $this->getOwner(), SQLITE3_TEXT);
                    $stmt->bindValue(":id", $this->getId(), SQLITE3_INTEGER);
                    if (!$res = $stmt->execute()) {
                        print "Couldn't update card!";
                    }
		}
                $stmt->close();
		$db->close();
		unset($db);
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getNum() {
		return $this->num;
	}
	
	public function setNum($num) {
		$this->num = $num;
	}
	
	public function getValidMonth() {
		return $this->validMonth;
	}
	
	public function setValidMonth($validMonth) {
		$this->validMonth = $validMonth;
	}
	
	public function getValidYear() {
		return $this->validYear;
	}
	
	public function setValidYear($validYear) {
		$this->validYear = $validYear;
	}
	
	public function getCvv() {
		return $this->cvv;
	}
	
	public function setCvv($cvv) {
		$this->cvv = $cvv;
	}
	
	public function getOwner() {
		return $this->owner;
	}
	
	public function setOwner($owner) {
		$this->owner = $owner;
	}
}
?>
