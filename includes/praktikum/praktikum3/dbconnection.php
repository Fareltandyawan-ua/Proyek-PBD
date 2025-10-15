<?php
class DBConnection {
    private string $servername = "localhost";
    private string $username = "root";
    private string $password = "";
    private string $dbname = "kuliah_wf_2025";
    public mysqli $dbconn;

public function init_connect(): void {
    $this->dbconn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
}
public function send_query(string $query): array {
    $result = $this->dbconn->query($query);
    if ($this->dbconn->error) {
        return [
            "status" => "error",
            "message" => $this->dbconn->error,
            "data" => []
        ];
    }
    if ($result === true) { // Untuk query INSERT, UPDATE, DELETE
        return [
            "status" => "success",
            "message" => "Query executed successfully",
            "data" => []
        ];
    }
    return [ // Untuk query SELECT
        "status" => "success",
        "message" => "Query executed successfully",
        "data" => $result->fetch_all(MYSQLI_ASSOC)
    ];
}

public function close_connection(): void {
    if ($this->dbconn) {
        $this->dbconn->close();
    }
}
}
?>