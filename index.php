<?php

// /etc/php/8.1/apache2/php.ini
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "mail.php";

define("NAMESIZE", 30);
define("EMAILSIZE", 40);
define("SUBJECTSIZE", 50);
define("MESSAGESIZE", 500);
define("FORMERROR", "formError");
define("OK", "ok");

/**
 * Returns "ok" if all the parameters are not empty,
 * otherwise returns "dataFormError".
 */
function info_recived(...$fields) {
    /* It isn't the most eficient implementation
    but it is just for practice. */
    $status = true;
    foreach ($fields as $f) {
        $status = (!empty($f) && $status);
    }
    return $status ? OK : FORMERROR;
}

/**
 * Verify the length and returns a sanitized string
 * or false in case the string it's too long.
 */
function validate_string(string $string, int $len): string|bool {
    if (strlen($string) > $len)
        return false;
    return filter_var($string, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

/**
 * Verify the length and if it's a correct mail address and
 * returns a sanitized mail. Otherwise returns false.
 */
function validate_email(string $email, int $len): string|bool {
    if (strlen($email) > $len)
      return false;
    $email = (filter_var($email, FILTER_SANITIZE_EMAIL));
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

$status = "";
if (isset($_POST["form"])) {
    // The form was sended
    $status = info_recived(...$_POST);

    if ($status === OK) {
        // Form fields are not empty
        $toName = validate_string($_POST["name"], NAMESIZE);
        $toMail = validate_email($_POST["mail"], EMAILSIZE);
        $subject = validate_string($_POST["subject"], SUBJECTSIZE);
        $message = validate_string($_POST["message"], MESSAGESIZE);

        if ($toName && $toMail && $subject && $message)
            // All the parameters are correct
            $status = send_mail($toName, $subject, $toMail, $message);
        else
            $status = FORMERROR;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php if($status===OK): ?>
        <p>Se recibieron los datos correctamente </p>

    <?php elseif($status===FORMERROR): ?>
        <p>Error en los datos enviados</p>

    <?php elseif(!empty($status)): ?>
        <p>Error al enviar mail: <?=$status?></p>

    <?php endif; ?>

    <form action="./" method="post">
        <h1>Contactame</h1>
        <!-- Campo nombre -->
        <label for="name">Nombre: </label>
        <input type="text" name="name" id="name"
               size=<?=NAMESIZE?> required>

        <!-- Campo email -->
        <label for="mail">Email: </label>
        <input type="email" name="mail" id="mail"
               size=<?=EMAILSIZE?> required>

        <!-- Campo asunto -->
        <label for="subject">Asunto: </label>
        <input type="text" name="subject" id="subject"
               size=<?=SUBJECTSIZE?> required>

        <!-- Campo mensaje -->
        <label for="message">Mensaje: </label>
        <textarea name="message" id="message"
                  size=<?=MESSAGESIZE?> required></textarea>

        <button type="submit" name="form" value="enviado">
            Enviar
        </button>
        <!-- The field value is requiered, if it is sended
        empty all the form validation will be false -->
    </form>
</body>
</html>