<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Sessione
 *
 * @author Demigod
 */
class Sessione {

    public function getUid() {
        return $this->uid;
    }

    public function setUid($uid) {
        $this->uid = $uid;
    }

    public function getSid() {
        return $this->sid;
    }

    public function setSid($sid) {
        $this->sid = $sid;
    }

    public function getTimestamp() {
        return $this->timestamp;
    }

    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
    }

    public function getHash() {
        return $this->hash;
    }

    public function setHash($hash) {
        $this->hash = $hash;
    }

    public function getAddr() {
        return $this->addr;
    }

    public function setAddr($addr) {
        $this->addr = $addr;
    }

    public function save($conn) {
        if (empty($this->uid) or empty($this->timestamp) or empty($this->hash) or empty($this->addr))
            throw new Exception("Impossibile creare sessione con parametri mancanti");



        if ($this->sid == null) {
            $params = array(
                ':uid' => $this->uid,
                ':ts' => $this->timestamp,
                ':hash' => $this->hash,
                ':addr' => $this->addr
            );

            $query = $conn->prepare('INSERT INTO sessioni(uid, timestamp, hash,addr) VALUES (:uid, :ts, :hash,:addr)');
            $query->execute($params);
            $this->sid = $conn->lastInsertId();
            return $this->sid;
        } else {
            $params = array(
                ':sid' => $this->sid,
                ':uid' => $this->uid,
                ':ts' => $this->timestamp,
                ':hash' => $this->hash
            );

            $query = $conn->prepare('UPDATE sessioni SET uid = :uid, timestamp = :ts, hash = :hash WHERE sid = :sid');
            $query->execute($params);
        }
    }

    public function getSessione($sid, $hash, $conn) {
        $query = $conn->prepare('SELECT * FROM sessioni WHERE sid = :sid AND hash = :hash');
        $params = array(':sid' => $sid, ':hash' => $hash);
        $query->execute($params);

        $res = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($res) != 1)
            throw new Exception('Sessione non esistente nel database');

        $riga = $res[0];
        $this->makeObj($riga);
    }

    public function getBySID($sid, $conn) {
        $query = $conn->prepare('SELECT * FROM sessioni WHERE sid = :sid');
        $params = array(':sid' => $sid);
        $query->execute($params);

        $res = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($res) != 1)
            throw new Exception('Sessione non esistente nel database');

        $riga = $res[0];
        $this->makeObj($riga);
    }

    public function getAll($conn, $limit) {
        $query = $conn->prepare(
                "SELECT ID_Utente, Username, addr, Master, sid, timestamp
            FROM sessioni INNER JOIN utente 
            ON sessioni.uid = utente.ID_Utente
            ORDER BY sid DESC LIMIT :max ");
        $limit = intval($limit);
        $query->bindParam(':max', $limit, PDO::PARAM_INT);
        $query->execute();

        $res = $query->fetchAll(PDO::FETCH_ASSOC);

        $userSessions = $this->makeUS($res);

        return $userSessions;
    }

    public function getByUsername($username, $conn, $limit) {
        $query = $conn->prepare(
                "SELECT ID_Utente, Username, addr, Master, sid, timestamp
            FROM sessioni INNER JOIN utente 
            ON sessioni.uid = utente.ID_Utente AND
            utente.Username = :username
            LIMIT :limit");
        $limit = intval($limit);
        $query->bindParam(':limit', $limit, PDO::PARAM_INT);
        $query->bindParam(':username', $username);
        $query->execute();

        $res = $query->fetchAll(PDO::FETCH_ASSOC);

        $userSessions = $this->makeUS($res);

        return $userSessions;
    }

    private function makeUS($res) {
        $ret = array();

        require_once 'UserSession.php';
        foreach ($res as $sRes) {
            $us = new UserSession(
                    $sRes['ID_Utente'], $sRes['Username'], $sRes['Master'], $sRes['sid'], $sRes['timestamp'], $sRes['addr']
            );

            $ret[] = $us;
        }

        return $ret;
    }

    private function makeObj($r) {
        $this->uid = $r['uid'];
        $this->addr = $r['addr'];
        $this->sid = $r['sid'];
        $this->timestamp = $r['timestamp'];
        $this->hash = $r['hash'];
    }

    private $uid;
    private $sid;
    private $timestamp;
    private $hash;
    private $addr;

}

?>
