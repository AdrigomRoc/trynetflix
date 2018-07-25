<!DOCTYPE html>
<html>
<body>
<?php
session_start();
    $servername = "localhost";
    $username = "root";
    $password = "";
    $pasuser = null;
    $usermail = null;
    $conn = null;
    if(isset($_POST["type"]) || isset($_SESSION["guarda"])){
        $conn = new mysqli($servername, $username, $password, "bd_php");
    }
    
    if(isset($_POST["type"])){
        if($_POST["type"] === "registre"){
            if(isset($_POST["password"])&&isset($_POST["email"]) ){
                $pasuser = password_hash($_POST["password"], PASSWORD_DEFAULT);
                $usermail = $_POST["email"];
                
                //fer registre amb bd
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $sql = "INSERT INTO users (email,password) VALUES ('". $usermail ."', '". $pasuser ."')";
                                
                if (mysqli_query($conn,$sql)) {
                    echo "Usuari registrat en la bbdd";
                } else {
                    echo "Error: ".$sql."<br>".mysqli_error($conn);
                }
            }else{
                echo "Falten dades";
            }
        }elseif($_POST["type"] === "login"){
            if(isset($_POST["password"]) && isset($_POST["email"]) ){
                $guardaemail= $_POST["email"];
                $guardapassword = $_POST["password"];
                $sql = "SELECT * FROM users where email = '".$guardaemail."' ";
                $result = mysqli_fetch_all(mysqli_query($conn, $sql));
                
                if(count($result)>0){
                    if(password_verify($guardapassword,$result[0][2])){
                        $_SESSION["guarda"] = $result[0][0];
                    }else{
                        echo "contrasenya incorrecta";
                    }
                }else{
                    echo "primer registret";
                }
            
            }
        }elseif($_POST["type"] === "form"){
            $sql = "SELECT * FROM users where id = '".$_SESSION["guarda"]."' ";
            $result = mysqli_fetch_all(mysqli_query($conn, $sql));
            if(isset($_POST["email"]) && $_POST["email"] !== ""){
                $sqlu="UPDATE users SET email='".$_POST["email"]."' where id = ". $_SESSION["guarda"]."; ";
                if ($conn->query($sqlu) === TRUE) {
                    echo "Record updated successfully";
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            }
            if(isset($_POST["password"]) && $_POST["password"] !== ""){
                $hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
                $sqlu = "UPDATE users SET password='".$hash."' where id = ". $_SESSION["guarda"].";";
                if ($conn->query($sqlu) === TRUE) {
                    echo "Record updated successfully";
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            }
            if(isset($_POST["telefono"]) && $_POST["telefono"] !== ""){
                $sqlu="UPDATE users SET telefono='".$_POST["telefono"]."' where id = ". $_SESSION["guarda"].";";
                if ($conn->query($sqlu) === TRUE) {
                    echo "Record updated successfully";
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            }
            if(isset($_POST["nombre"]) && $_POST["nombre"] !== ""){
                $sqlu="UPDATE users SET nombre='".$_POST["nombre"]."' where id = ". $_SESSION["guarda"].";";
                if ($conn->query($sqlu) === TRUE) {
                    echo "Record updated successfully";
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            }
            if(isset($_POST["dni"]) && $_POST["dni"] !== ""){
                $sqlu="UPDATE users SET dni='".$_POST["dni"]."' where id = ". $_SESSION["guarda"].";";
                if ($conn->query($sqlu) === TRUE) {
                    echo "Record updated successfully";
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            }
        }elseif($_POST["type"] === "logout"){
            
            session_destroy();
            unset($_SESSION["guarda"]);
            session_unset();
        }
    }
    
?>
<?php  
    if(isset($_SESSION["guarda"]) ){
        //mostrare login
        $sql = "SELECT * FROM users where id = ".$_SESSION["guarda"];
        $user = mysqli_fetch_all(mysqli_query($conn, $sql))[0];
?>

    <form action="login.php" method="post">
        <input type="hidden" name="type" value="form">
        <br>
        Modifica la teva informació si vols
        <br>
        Correu: 
        <input type="email" name="email" value="<?php echo $user[1]; ?>">
        <br>
        Password: 
        <input type="password" name="password">
        <br>
        Telefon: 
        <input type="text" name="telefono" value="<?php echo $user[3]; ?>">
        <br>
        Nom: 
        <input type="text" name="nombre" value="<?php echo $user[4]; ?>">
        <br>
        DNI: 
        <input type="text" name="dni" value="<?php echo $user[5]; ?>">
        <br>
        <input type="submit" value="update">
        <br>
        
    </form>
    <form action="login.php" method="post">
        <input type="hidden" name="type" value="logout">
        <hr/>
        <input type="submit" value="logout">
        <br/>
    </form>
<?php
    }else{
?>
    <h1>LOGIN</h1>
    <form action="login.php" method="post">
        <input type="hidden" name="type" value="login">
        Correu: 
        <input type="email" name="email">
        <br />
        Contrasenya: 
        <input type="password" name="password">
        <br />
        <input type="submit" value="login">
        <br/>
    </form>
    <hr />
    <h1>REGISTRE</h1>
    <form action="login.php" method="post">
        <input type="hidden" name="type" value="registre">
        Correu: 
        <input type="email" name="email">
        <br />
        Contrasenya: 
        <input type="password" name="password">
        <br />
        <input type="submit" value="register">
        <br/>
    </form>
<?php
    }
?>
</body>
</html>

<?php
    if($conn){
        $conn->close();
    }
?>