<?php
class database
{
    protected $dbConnect ,$lockalhost, $username, $password;
    public function __construct($lockalhost,$username,$password,) {
        $this->lockalhost = $lockalhost;
        $this->username = $username;
        $this->password = $password;
    }
    function dbConnect()
    { 
        $this->dbConnect = mysqli_connect($this->lockalhost, $this->username, $this->password); 
        return $this->dbConnect;
    }

    function dbCreate()
    {
        $sqlcreatebase = "CREATE DATABASE IF NOT EXISTS shortUrls";
        if($this->dbConnect->query($sqlcreatebase)){

        } else{
        echo "Ошибка: " . $this->dbConnect->error;
        }
    }
    function tableCreate()
    {
        if($this->dbConnect->connect_error){
            die("Ошибка: " . $this->dbConnect->connect_error);
        }
        $sqlcreatetable = "CREATE TABLE IF NOT EXISTS shortUrls.Urls (id INTEGER AUTO_INCREMENT PRIMARY KEY, baseUrl VARCHAR(80), shortUrl VARCHAR(30) UNIQUE);";
        
        if($this->dbConnect->query($sqlcreatetable)){
        
        } else{
            echo "Ошибка: " . $this->dbConnect->error;
        }
    }
    function generateRandomString($length = 5) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    function getMyUrl()
    {
        $myUrl = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . "/";
        $myUrl = explode('?', $myUrl);
        $myUrl = $myUrl[0];
        return $myUrl;
    }
    function getMy404Url()
    {
        $myUrl = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $myUrl = explode('?', $myUrl);
        $myUrl = $myUrl[0];
        return $myUrl;
    }

    function getDataFromJs(){
        $dataFromJs = $_POST["ourForm_inp"];
        return $dataFromJs;
    }

    function addToDatabase($baseUrl){

        $shortUrl = "";
        $isUnique = false;

        while (!$isUnique) {
            $shortUrl = $this->generateRandomString();
            $sqlCheckUnique = "SELECT * FROM shortUrls.Urls WHERE shortUrl = '$shortUrl';";
            $result = $this->dbConnect()->query($sqlCheckUnique);
            if ($result->num_rows == 0) {
                $isUnique = true;
            }
        }
        $myUrl = database::getMyUrl();
        $linkTodb = $myUrl . $shortUrl;
        $sqlinsertData = "INSERT INTO shortUrls.Urls (baseUrl, shortUrl) VALUES ('$baseUrl', '$linkTodb');";
        if ($this->dbConnect()->query($sqlinsertData) === TRUE) {
            return $linkTodb;
        } else {
            echo "Ссылка не уникальна и не была создана , пожалуйста повторите попытку";
        }



    }
    function shortRedirect(){
        $conn = $this->dbConnect;
        $my404Url = database::getMy404Url();
        $getdataquery = "SELECT * FROM shortUrls.Urls";
        if($result = $conn->query($getdataquery)){
            foreach($result as $row){
                if ($my404Url == $row["shortUrl"]) {
                    $new_url = $row["baseUrl"];
                    header('Location: '.$new_url);
                }
            }
        } else{
            echo "Ошибка: " . $conn->error;
        }
        
    }
    function render(){
        $dataFromJs = database::getDataFromJs();
        $newUrl = database::addToDatabase($dataFromJs);
        echo  "старая ссылка " . '<a href="'.$dataFromJs.'" target="_blank">'.$dataFromJs.'</a>';
        echo "<h2>ваш короткий вариант</h2>";
        echo  "новая ссылка " . '<a href="'.$newUrl.'" target="_blank">'.$newUrl.'</a>';
        echo "<br>";
        echo "новая ссылка " . "<input type='text' value='$newUrl' id = 'shortUrl'></a>" . " <button onclick = copyFunc()>скопировать</button>";
    }
}

