<?php

class DBManager {
  protected $connections = array();
  protected $record_connection_map = array();
  protected $records = array();

  public function conncet($name, $params) {
    $params = array_merge(array(
      'dsn'      =>  null,
      'user'     =>  '',
      'password' => '',
      'options'  => array(),
    ), $params);

    $con = new PDO(
      $params['dsn'],
      $params['user'],
      $params['password'],
      $params['options']
    );

    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $this->connections[$name] = $con;
  }

  public function getConnection($name = null) {
    if (is_null($name)) {
      return current($this->connections);
    }
    return $this->connections[$name];
  }

  public function setRecordConnectionMap($record_name, $name) {
    $this->record_connection_map[$record_name] = $name;
  }

  public function getConnectionForRecord($record_name) {
    if (isset($this->record_connection_map[$record_name])) {
      $name = $this->record_connection_map[$record_name];
      $con = $this->getConnection($name);
    } else {
      $con = $this->getConnection();
    }
    return $con;
  }

  public function get($record_name) {
    if (!isset($this->records[$record_name])) {
      $record_class = $record_name . 'record';
      $con = $this->getConnectionForRecord($record_name);

      $record = new $record_class($con);

      $this->records[$record_name] = $record;
    }
    return $this->records[$record_name];
  }

  public function __destruct() {
    foreach ($this->records as $record) {
      unset($record);
    }

    foreach ($this->connections as $con) {
      unset($con);
    }
  }
}