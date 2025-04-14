<?php
    class Database extends MySQLi {

        static protected $instance = null;      //* Uso un unica istanza del database per tutte le connessioni che devo effettuare

        public function __construct($host, $user, $password, $schema) {
            parent::__construct($host, $user, $password, $schema);              //* La classe eredita dal msqli, quindi devo richiamare il costruttore della classe padre
        }

        static function getInstance() {         //*Ogni volta che tento di accedere al db devo controllare se l'istanza static esiste
            if (self::$instance == null) {  
                self::$instance = new Database('my_mariadb', 'root', 'ciccio', 'scuola');   //* Se non esiste la costruisco sul momento
                return self::$instance;
            }
        }

        //* Metodi che semplificano l'interfaccia delle query per operazioni CRUD
        public function select($table, $where = 1) {
            $query = "SELECT * FROM $table WHERE $where";
            if ($result = $this->query($query)) {
                return $result->fetch_all(MYSQLI_ASSOC);
            }
            return null;                                            //* Restituisce null se non trova righe per la richiesta
        }

        public function insert($table, $values) {
            $query = "INSERT INTO $table VALUES $values";
            return $this->query($query);
        }

        public function update($table, $values, $where) {
            $query = "UPDATE $table SET $values WHERE $where";
            return $this->query($query);
        }

        public function delete($table, $where) {
            $query = "DELETE FROM $table WHERE $where";
            return $this->query($query);
        }
    }
