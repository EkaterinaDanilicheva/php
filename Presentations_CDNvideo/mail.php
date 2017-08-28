<?php

$path = "/home/clients/w_gar/public_html/IBC2017";
$files_arr = scandir($path);
$str = "
<style type='text/css'>
body {
     font-family: Arial, sans-serif;
    }
p{
  text-align: center;
}
form{
  margin:0 auto;
  max-width:95%;
  box-sizing:border-box;
  padding:40px;
  border-radius:5px;
  background:RGBA(255,255,255,1);
  -webkit-box-shadow:  0px 0px 15px 0px rgba(0, 0, 0, .45);
  box-shadow:  0px 0px 15px 0px rgba(0, 0, 0, .45);
}
.textbox{
  height:50px;
  width:50%;
  border-radius:3px;
  border:rgba(0,0,0,.3) 2px solid;
  box-sizing:border-box;
  font-family: 'Open Sans', sans-serif;
  font-size:18px;
  padding:10px;
  margin-bottom:30px;
}
.textbox:focus{
  outline:none;
   border:rgba(24,149,215,1) 2px solid;
   color:rgba(24,149,215,1);
}
.button{
  height:50px;
  width:30%;
  border-radius:3px;
  border:rgba(0,0,0,.3) 0px solid;
  box-sizing:border-box;
  padding:10px;
  background:#90c843;
  color:#FFF;
  font-family: 'Open Sans', sans-serif;
  font-weight:400;
  font-size: 16pt;
  transition:background .4s;
  cursor:pointer;
}
.button:hover{
  background:#80b438;
}
</style>
        <form action='mail.php' method='get'>
          <p><b>Form to send</b></p>
          <p>Email <input type='text' name='email' class='textbox'  required > </p>
          <p>";

foreach ( $files_arr as $file_name ) {
        if(strpos($file_name, '.pdf') ){
                $str = $str . "<input type='checkbox' name='files[]' value='$file_name'/>$file_name</br>
                </div>";
        }
}

$str = $str . "</p><p><input type='submit' class='button' value='Send'></p>
 </form>";
if ($_GET) {
        //print_r($_GET);
        $to          = "a.garusev@cdnvideo.com, ". $_GET['email'];
        $from        = "a.garusev@cdnvideo.com";
        $subject     = "Presentations_CDNvideo";

        $un = strtoupper(uniqid(time()));
        $head = "From: $from\n";
        $head .= "To: $to\n";
        $head .= "Subject: $subject\n";
        $head .= "X-Mailer: PHPMail Tool\n";
        $head .= "Mime-Version: 1.0\n";
        $head .= "Content-Type:multipart/mixed;";
        $head .= "boundary=\"----------".$un."\"\n\n";
        $zag = "------------".$un."\nContent-Type:text/html;\n";
        $zag .= "Content-Transfer-Encoding: 8bit\n\n$text\n\n";

        foreach( $_GET['files'] as $file_name ) {
                $filename = $path . "/" . $file_name;
                $f = fopen($filename,"rb");

                $zag .= "------------".$un."\n";
                $zag .= "Content-Type: application/octet-stream;";
                $zag .= "name=\"".basename($filename)."\"\n";
                $zag .= "Content-Transfer-Encoding:base64\n";
                $zag .= "Content-Disposition:attachment;";
                $zag .= "filename=\"".basename($filename)."\"\n\n";
                $zag .= chunk_split(base64_encode(fread($f,filesize($filename))))."\n";

        }

        if( mail($to, $subject, $zag, $head) )
        {
		$str = $str . "<script type='text/javascript'>
                        (function () {
                                alert('msage has been sent');
                        }());
                </script>";
        } else {
        	$str = $str . "<script type='text/javascript'>
                        (function () {
                                alert('attention!!!\nmsage WAS NOT sent');
                        }());
                </script>";
	}
        print $str;
} else {
        print $str;
}
?>
