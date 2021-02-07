<!DOCTYPE html>
<html lang="en">

<style>

       *{
        padding:0;
        margin:0;
        box-sizing: border-box;
    }

    main{

        min-height: 100vh;
        font-family: 'Roboto', sans-serif;
        background: linear-gradient(to top left, #74D1EB, #644FD8);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .container{
        background: linear-gradient(to top left, rgba(255,255,255, 0.25), rgba(255,255,255, 0.5));
        min-height: 80vh;
        min-width: 60%;
        text-align: center;
        border-radius: 2rem;
        display: grid;
    }

    textarea{
        resize: none;
        overflow: auto;
        border-radius: 0.5rem;
        font-size: 20px;
        margin: auto;
        grid-column-start: 1;
        grid-column-end: 3;
        width: 80%;
        padding: 5px;
    }

    .dropdown-content {
        z-index: 1;
        width: 40%;
        height: 50px;
        margin: auto;
    }

    .dropdown-content a {

        text-decoration: none;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    }

    .dropdown-container{
        grid-column-start: 1;
        grid-column-end: 3;
    }

    input[type = "submit"]{
	
        min-width: 50%;
        min-height: 50px;
        background: linear-gradient(to top, #704DDB, #A149F2);
        margin: auto;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.3);
    }

    .submit_container{

        grid-column-start: 1;
        grid-column-end: 3;
    }

    h1{
        padding: 40px;
        color: #522D8D;
        font-family: 'Fira Sans', sans-serif;
        grid-column-start: 1;
        grid-column-end: 3;

    }


    form{
        grid-column-start: 1;
        grid-column-end: 3;
        display: grid;

    }
</style>

<head>
    <meta charset="UTF-8">
    <title>POLY SUGGEST</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans&family=Roboto&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="favicon.io" />



</head>

<body>

<main>
    <div class="container">
        <h1>FPU submission box</h1>
        <form method ="GET">

            <div class="dropdown-container">
                <select name ="dropdown" class="dropdown-content">
                    <option value = "success@floridapoly.edu"> Acedemic Sucess</option>
                    <option value = "magentaautumn@gmail.com"> PolySuggest </option>
                    <option value = "president@floridapoly.edu"> President</option>
                </select>
            </div>

            <textarea id="subject" name="subject"  placeholder="Subject"></textarea>

            <textarea id="suggestion" name="suggestion" rows="10" cols="30" placeholder="Give your wonderful suggestion here...." style="resize: none"></textarea><br>
            <div class="submit_container"><input type="submit" value="Submit"></div>
        </form>
    </div>

</main>

<?php 

if(!empty($_GET['suggestion'])){
# Instantiate the client.
require_once './lib/Mailgun/Client.php';
require_once './lib/Mailgun/Mail.php';
// add Mailgun domain like \Mailgun\Client::add('<mailgun-domain>', '<mailgun-key>');
\Mailgun\Client::add('sandbox452727853f0d45f8b4746e065abb5ce9.mailgun.org', 'c0ad1bf8969d93e6830d86d9b66ba9d3-07bc7b05-709007c7');

// debug mode - will print errors - use on development servers only
\Mailgun\Client::$debug = true;

// setup mail
$mail = new \Mailgun\Mail;
$mail->to = $_GET['dropdown'];
$mail->to_name = 'David Kozdra';
$mail->from = 'magentaautumn@gmail.com';
$mail->from_name = 'Poly Suggest';
$mail->subject = $_GET['subject'];
$mail->html = $_GET['suggestion'];
// use $mail->text instead of $mail->html for plain text messages 
//$_GET['suggestion'];
// send mail to Mailgun API

if($mail->send() !== false) // success
{
      // mail sent
}
else // fail
{
print "error";
       // print error (if debug mode is on errors will be trigger automatically)
}
}
?>

</body>
</html>
