<?php
/**
 * Description of my_mailer
 *
 * @author Arif sTalKer Majid
 */
Class My_mailer {
    
    function forgot_password_mail($userinfo)
    {
        $CI =& get_instance();
        $data['username'] = $userinfo['userName'];
        $data['password'] = $userinfo['password'];
        $data['fullName'] = ucfirst($userinfo['firstName']." ".$userinfo['lastName']);
        $data['base_url'] = $userinfo['base_url'];
        
        
        $msg = $CI->load->view("mail/password_retrieval.html",$data,true);
        $config = array();
        $config['mailtype'] = 'html';
        $CI->load->library('email',$config);

        $CI->email->from('noreply@ccfap.com', 'CCFAP ADMINISTRATOR');
        $CI->email->to($userinfo['email']);                        

        $CI->email->subject('CCFAP Password Retrieval');
        $CI->email->message($msg);

        //@$CI->email->send();
        
        //return $msg;
        //echo $this->email->print_debugger();
        $to = $userinfo['email'];
        $subject = "CCFAP Password Retrieval";
        $message = $msg;
        
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: CCFAP ADMINISTRATOR'. "\r\n" .
                    'Reply-To: noreply@ccfap.com'. "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
        
        @mail($to,$subject,$message,$headers);
    }
    
    function sendMail($subject,$message,$recipients)
    {
        $CI =& get_instance();
        $CI->load->library("phpmailer",true);
        try {
        // Now you only need to add the necessary stuff
        $CI->phpmailer->AddAddress($recipients);
        $CI->phpmailer->Subject = $subject;
        $CI->phpmailer->Body    = $message;
        
        $CI->phpmailer->AddReplyTo('info@binary-elites.com', 'BE Schools');
        $CI->phpmailer->SetFrom('info@binary-elites.com', 'BE Schools');
        $CI->phpmailer->AddReplyTo('info@binary-elites.com', 'BE Schools');
        
        //$CI->phpmailer->AddAttachment("c:/temp/11-10-00.zip", "new_name.zip");  // optional name

        $CI->phpmailer->Send();        
        
        } catch (phpmailerException $e) {
            return false;
        //echo $e->errorMessage(); //Pretty error messages from PHPMailer
        } catch (Exception $e) {
            return false;
            //echo $e->getMessage(); //Boring error messages from anything else!
        }


        return true;
    }
    
}

?>
