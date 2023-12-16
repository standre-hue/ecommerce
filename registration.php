<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $banner_registration = $row['banner_registration'];
}
?>

<?php
if (isset($_POST['form1'])) {

    $valid = 1;

    if(empty($_POST['cust_name'])) {
        $valid = 0;
        $error_message .= "Veuillez entrer un nom"."<br>";
    }

    if(empty($_POST['cust_email'])) {
        $valid = 0;
        $error_message .= "Veuillez entrer une address email valide"."<br>";
    } else {
        if (filter_var($_POST['cust_email'], FILTER_VALIDATE_EMAIL) === false) {
            $valid = 0;
            $error_message .= "Veuillez entrer une address email valide"."<br>";
        } else {
            $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_email=?");
            $statement->execute(array($_POST['cust_email']));
            $total = $statement->rowCount();                            
            if($total) {
                $valid = 0;
                $error_message .= "Veuillez utiliser une autre address email "."<br>";
            }
        }
    }

    if(empty($_POST['cust_phone'])) {
        $valid = 0;
        $error_message .= "Veuillez entrer un numero de telephone"."<br>";
    }

    if(empty($_POST['cust_address'])) {
        $valid = 0;
        $error_message .= "Veuillez entrer une address "."<br>";
    }

    if(empty($_POST['cust_country'])) {
        $valid = 0;
        $error_message .= "Veuillez choisir un pays"."<br>";
    }

    if(empty($_POST['cust_city'])) {
        $valid = 0;
        $error_message .= "Veuillez choisir une ville"."<br>";
    }

    if(empty($_POST['cust_state'])) {
        $valid = 0;
        $error_message .= "Veuillez entrer votre etat ou ville"."<br>";
    }

    if(empty($_POST['cust_zip'])) {
        $valid = 0;
        $error_message .= "Veuillez entrer votre code zip"."<br>";
    }

    if( empty($_POST['cust_password']) || empty($_POST['cust_re_password']) ) {
        $valid = 0;
        $error_message .= "Veuillez entrer un mot de pass"."<br>";
    }

    if( !empty($_POST['cust_password']) && !empty($_POST['cust_re_password']) ) {
        if($_POST['cust_password'] != $_POST['cust_re_password']) {
            $valid = 0;
            $error_message .= "Les mot de pass ne correspondent pas"."<br>";
        }
    }

    if($valid == 1) {

        $token = md5(time());
        $cust_datetime = date('Y-m-d h:i:s');
        $cust_timestamp = time();

        // saving into the database
        $statement = $pdo->prepare("INSERT INTO tbl_customer (
                                        cust_name,
                                        cust_cname,
                                        cust_email,
                                        cust_phone,
                                        cust_country,
                                        cust_address,
                                        cust_city,
                                        cust_state,
                                        cust_zip,
                                        cust_b_name,
                                        cust_b_cname,
                                        cust_b_phone,
                                        cust_b_country,
                                        cust_b_address,
                                        cust_b_city,
                                        cust_b_state,
                                        cust_b_zip,
                                        cust_s_name,
                                        cust_s_cname,
                                        cust_s_phone,
                                        cust_s_country,
                                        cust_s_address,
                                        cust_s_city,
                                        cust_s_state,
                                        cust_s_zip,
                                        cust_password,
                                        cust_token,
                                        cust_datetime,
                                        cust_timestamp,
                                        cust_status
                                    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $statement->execute(array(
                                        strip_tags($_POST['cust_name']),
                                        strip_tags($_POST['cust_cname']),
                                        strip_tags($_POST['cust_email']),
                                        strip_tags($_POST['cust_phone']),
                                        strip_tags($_POST['cust_country']),
                                        strip_tags($_POST['cust_address']),
                                        strip_tags($_POST['cust_city']),
                                        strip_tags($_POST['cust_state']),
                                        strip_tags($_POST['cust_zip']),
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        $_POST['cust_password'],
                                        $token,
                                        $cust_datetime,
                                        $cust_timestamp,
                                        1
                                    ));

        // Send email for confirmation of the account
        $to = $_POST['cust_email'];
        
        $subject = LANG_VALUE_150;
        $verify_link = BASE_URL.'verify.php?email='.$to.'&token='.$token;
        $message = '
'.LANG_VALUE_151.'<br><br>

<a href="'.$verify_link.'">'.$verify_link.'</a>';

        $headers = "From: noreply@" . BASE_URL . "\r\n" .
                   "Reply-To: noreply@" . BASE_URL . "\r\n" .
                   "X-Mailer: PHP/" . phpversion() . "\r\n" . 
                   "MIME-Version: 1.0\r\n" . 
                   "Content-Type: text/html; charset=ISO-8859-1\r\n";
        
        // Sending Email
        //mail($to, $subject, $message, $headers);

        unset($_POST['cust_name']);
        unset($_POST['cust_cname']);
        unset($_POST['cust_email']);
        unset($_POST['cust_phone']);
        unset($_POST['cust_address']);
        unset($_POST['cust_city']);
        unset($_POST['cust_state']);
        unset($_POST['cust_zip']);

        $success_message = LANG_VALUE_152;
    }
}
?>

<div class="page-banner" style="">
    <h1 style="text-align:center;color:black">S'inscrire</h1>
</div>

<div class="page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="user-content">

                    

                    <form action="" method="post">
                        <?php $csrf->echoInputField(); ?>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                
                                <?php
                                if($error_message != '') {
                                    echo "<div class='error' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>"."Une erreur est survenue veuillez ressayer :".$error_message."</div>";
                                }
                                if($success_message != '') {
                                    echo "<div class='success' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>"."Inscription effectuer avec succes"."</div>";
                                }
                                ?>

                                <div class="col-md-6 form-group">
                                    <label for="">Nom Complet *</label>
                                    <input type="text" class="form-control" name="cust_name" value="<?php if(isset($_POST['cust_name'])){echo $_POST['cust_name'];} ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="">Entreprise</label>
                                    <input type="text" class="form-control" name="cust_cname" value="<?php if(isset($_POST['cust_cname'])){echo $_POST['cust_cname'];} ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="">Email *</label>
                                    <input type="email" class="form-control" name="cust_email" value="<?php if(isset($_POST['cust_email'])){echo $_POST['cust_email'];} ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="">Numero de telephone *</label>
                                    <input type="text" class="form-control" name="cust_phone" value="<?php if(isset($_POST['cust_phone'])){echo $_POST['cust_phone'];} ?>">
                                </div>
                                <div class="col-md-12 form-group">
                                    <label for="">Address *</label>
                                    <textarea name="cust_address" class="form-control" cols="30" rows="10" style="height:70px;"><?php if(isset($_POST['cust_address'])){echo $_POST['cust_address'];} ?></textarea>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="">Pays *</label>
                                    <select name="cust_country" class="form-control select2">
                                        <option value="">Select country</option>
                                    <?php
                                    $statement = $pdo->prepare("SELECT * FROM tbl_country ORDER BY country_name ASC");
                                    $statement->execute();
                                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
                                    foreach ($result as $row) {
                                        ?>
                                        <option value="<?php echo $row['country_id']; ?>"><?php echo $row['country_name']; ?></option>
                                        <?php
                                    }
                                    ?>    
                                    </select>                                    
                                </div>
                                
                                <div class="col-md-6 form-group">
                                    <label for="">Ville *</label>
                                    <input type="text" class="form-control" name="cust_city" value="<?php if(isset($_POST['cust_city'])){echo $_POST['cust_city'];} ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="">Etat *</label>
                                    <input type="text" class="form-control" name="cust_state" value="<?php if(isset($_POST['cust_state'])){echo $_POST['cust_state'];} ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="">Code Zip *</label>
                                    <input type="text" class="form-control" name="cust_zip" value="<?php if(isset($_POST['cust_zip'])){echo $_POST['cust_zip'];} ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="">Mot de pass *</label>
                                    <input type="password" class="form-control" name="cust_password">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="">Confirmer le mot de pass *</label>
                                    <input type="password" class="form-control" name="cust_re_password">
                                </div>
                                <div class="col-md-6 form-group" style="width:100%; display:flex; justify-content:center; align-items:center">
                                    <label for=""></label>
                                    <input style="width:100%" type="submit" class="btn btn-primary" value="Enregister" name="form1">
                                </div>
                            </div>
                        </div>                        
                    </form>
                </div>                
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>