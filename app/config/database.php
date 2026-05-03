<?php
class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;
    private $ssl_ca;
    private $ssl_verify;
    public $conn;

    public function __construct() {
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->db_name = getenv('DB_NAME') ?: 'it_quest';
        $this->username = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASS') ?: '';
        $this->port = getenv('DB_PORT') ?: '3306';
        $this->ssl_ca = getenv('DB_SSL_CA') ?: '';
        $verifyValue = getenv('DB_SSL_VERIFY');
        $verifyValue = $verifyValue === false ? '1' : $verifyValue;
        $this->ssl_verify = in_array(strtolower($verifyValue), ['1', 'true', 'yes'], true);
    }

    public function getConnection() {
        $this->conn = null;
        try {
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];

            $sslCaPath = $this->resolveSslCaPath();
            if (!empty($sslCaPath)) {
                $options[PDO::MYSQL_ATTR_SSL_CA] = $sslCaPath;
                $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = $this->ssl_verify;
            }

            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }

    private function resolveSslCaPath() {
        $value = trim($this->ssl_ca);
        if ($value === '') {
            return '';
        }

        if (is_file($value)) {
            return $value;
        }

        if (stripos($value, 'BEGIN CERTIFICATE') !== false) {
            $pem = str_replace('\\n', "\n", $value);
            $hash = sha1($pem);
            $path = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR)
                . DIRECTORY_SEPARATOR
                . 'itquest_ca_' . $hash . '.pem';
            if (!file_exists($path)) {
                file_put_contents($path, $pem);
            }
            return $path;
        }

        return $value;
    }
}
?>