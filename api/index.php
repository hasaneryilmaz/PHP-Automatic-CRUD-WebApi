<?php
require_once("DbConnect.php");
$tab =  $baglan->query("SHOW TABLES");
$tab->execute();
$tablolar = $tab->fetchAll(PDO::FETCH_COLUMN);
$t = explode("/", @$_GET["tur"]);
$tur = @$t[0];
$method = @$t[1];
$param = @$t[1]."/".@$t[2];
function http($param)
{
    $code;
    switch ($param)
    {
        case "OK":
            $code =  200;
            break;
        case "NOT_FOUND":
            $code =  404;
            break;
        case "BAD_REQUEST":
            $code =  400;
            break;
        case "ADD_OK":
            $code =  201;
            break;

    }
    return http_response_code($code);
}
function jsoncikti($p){
    header('Content-Type: application/json');
    echo json_encode($p);
}
foreach($tablolar as $tablo)
{
    ${$tablo}["Get"] = function($page) use ($baglan,$tablo,$perpageitem)
    {
        $toplamVeri = $baglan->query("SELECT COUNT(*) FROM ".$tablo)->fetchColumn();
        $toplamSayfa = ceil($toplamVeri / $perpageitem);
        if($page < 1) $page = 1;
        if($page > $toplamSayfa){
            $page = (int)$toplamSayfa;
        }
        $limit = ($page - 1) * $perpageitem;
        $sql = "SELECT * FROM ".$tablo." LIMIT ".$limit.",".$perpageitem;
        $sorgu = $baglan->query($sql);
        $sorgu->execute();
        if($sorgu->rowCount() >0 )
        {
            http("OK");
            return ["success" => true, "this_page" => $page , "total_page" => $toplamSayfa , "data" => $sorgu->fetchAll(PDO::FETCH_ASSOC) ];
        }else
        {
            http("BAD_REQUEST");
            return ["error"=>"There are no records in this table","success"=>false];
        }
    };
    ${$tablo}["GetBy"] = function($param,$page) use ($baglan,$tablo,$perpageitem)
    {
        $param = str_replace(array("=","'","or","and",";","SHOW","DROP","UNION","SELECT","WHERE"),"",$param);
        $p = explode("/",$param);
        $where = $p[0];
        $what = $p[1];
        $toplamVeri = $baglan->query("SELECT COUNT(*) FROM ". $tablo ." WHERE ".$where." = '".$what."'")->fetchColumn();
        $toplamSayfa = ceil($toplamVeri / $perpageitem);
        if($page < 1) $page = 1;
        if($page > $toplamSayfa){
            $page = (int)$toplamSayfa;
        }
        $limit = ($page - 1) * $perpageitem;
        $sql = "SELECT * FROM ".$tablo." WHERE ".$where." = '".$what."' LIMIT ".$limit.",".$perpageitem;
        $sorgu = $baglan->prepare($sql);
        $sorgu->execute();
        if($sorgu->rowCount() > 0)
        {
            http("OK");
            return ["success" => true, "this_page" => $page , "total_page" => $toplamSayfa , "data" => $sorgu->fetchAll(PDO::FETCH_ASSOC) ];
        }else
        {
            http("NOT_FOUND");
            return ["error"=>"There is no such record","success"=>false];
        }
    };
    ${$tablo}["Delete"] = function($param)  use ($baglan,$tablo)
    {
        $param = str_replace(array("=","'","or","and",";","SHOW","DROP","UNION","SELECT","WHERE"),"",$param);
        $p = explode("/",$param);
        $where = $p[0];
        $what = $p[1];
        $sql = "DELETE FROM ". $tablo ." WHERE ".$where." = '".$what."'";
        $sorgu = $baglan->prepare($sql);
        $sorgu->execute();
        if($sorgu->rowCount() > 0 )
        {
            http("OK");
            return ["success"=>true,"message"=>"Deletion successful"];
        }else
        {
            http("NOT_FOUND");
            return ["error"=>"You tried to delete a record that does not exist","success"=>false];
        }
    };
    ${$tablo}["DeleteAll"] = function()  use ($baglan,$tablo)
    {
        $sql = "DELETE FROM ". $tablo;
        $sorgu = $baglan->prepare($sql);
        $sorgu->execute();
        http("OK");
        return ["success"=>true,"message"=>"All records in the table have been deleted successfully."];
    };
    ${$tablo}["Insert"] = function($param) use ($baglan,$tablo){
        $param = str_replace(array("=","'","or","and",";","SHOW","DROP","UNION","SELECT","WHERE"),"",$param);
        $data = json_decode(file_get_contents("php://input"), true);
        $i=0;
        foreach ($data as $key => $value) {
            $newdata[$i] = $key." = '".$value."'";
            $i++;
        }
        $newdata = implode(" , ",$newdata);
        $sql = "INSERT INTO ". $tablo ." SET {$newdata}";
        $sorgu = $baglan->prepare($sql);
        $sorgu->execute();
        if($sorgu->rowCount() > 0 )
        {
            http("ADD_OK");
            return ["success"=>true,"message"=>"Adding successful"];
        }else
        {
            http("NOT_FOUND");
            return ["error"=>"A problem occurred while adding","success"=>false];
        }
    };
    ${$tablo}["Update"] = function($param) use ($baglan,$tablo){
        $param = str_replace(array("=","'","or","and",";","SHOW","DROP","UNION","SELECT","WHERE"),"",$param);
        $data = json_decode(file_get_contents("php://input"), true);
        $i=0;
        foreach ($data as $key => $value) {
            $newdata[$i] = $key." = '".$value."'";
            $i++;
        }
        $newdata = implode(" , ",$newdata);
        $p = explode("/",$param);
        $where = $p[0];
        $what = $p[1];
        $sql = "UPDATE ". $tablo ." SET {$newdata} WHERE ".$where." = '".$what."'";
        $sorgu = $baglan->prepare($sql);
        $sorgu->execute();
        if($sorgu->rowCount() > 0 )
        {
            http("OK");
            return ["success"=>true,"message"=>"Update process successful"];
        }else
        {
            http("NOT_FOUND");
            return ["error"=>"There was a problem during the update","success"=>false];
        }
    };
};
    if(@$_GET["auth_token"])
    {
        $page = @$_GET["page"] ? @$_GET["page"] : 1;
        $hascoding_controller = $hascoding_api_auth["GetBy"]("auth_token/".$_GET["auth_token"],1);
        if($hascoding_controller["success"]==1)
        {
            $auto_token= $hascoding_controller["data"][0];
            $last_date = $auto_token["last_date"];
            $now_date = date("Y-m-d H:i:s");
            if($last_date<$now_date)
            {
                echo "You tried to access it with an expired token <br>";
                $goster = 0;
            }
            else {
                $goster =1;
                foreach ($tablolar as $tablo) {
                    switch ($tur) {
                        case $tablo:
                            $httprequest = $_SERVER['REQUEST_METHOD'];
                            switch ($httprequest) {
                                case "GET":
                                    if ($param != "/") {
                                        jsoncikti(${$tablo}["GetBy"]($param, $page));
                                    } else {
                                        jsoncikti(${$tablo}["Get"]($page));
                                    }
                                    break;
                                case "DELETE":
                                    $p = explode("/", $param);
                                    if ($p[0] == "delete") {
                                        jsoncikti(${$tablo}["DeleteAll"]());
                                    } else {
                                        jsoncikti(${$tablo}["Delete"]($param));
                                    }
                                    break;
                                case "POST":
                                    jsoncikti(${$tablo}["Insert"]($param));
                                    break;
                                case "PUT":
                                    jsoncikti(${$tablo}["Update"]($param));
                                    break;
                            }
                            break;
                    }
                }
            }
            if($goster == 1)
            {
                if($tur == ""){
                    echo "Please choose a table for the api";
                }
            }
        }else
        {
            echo "No such access token";
        }
    }else
    {
        echo "You must specify an Access Token";
    }
?>

