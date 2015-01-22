<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of home
 *
 * @author USER
 */
Class Vcard extends MX_Controller {
    function __construct() {
        parent::__construct();        
        $this->load->library('my_session'); 
        $this->my_session->checkSession();
    }
    
    function index()
    {
        /** initialization **/
		die("aaa");
        $data['css'] = "";        
        $data['js'] = "";        
        $data['base_url'] = base_url();
        
        $data['css_files'] = array();
        $data['js_files'] = array();
        
        $data['pageTitle'] = "vCard";
        $data['body_template'] = "vcard_admin.php";
        $this->load->view(MAIN_TEMPLATE_FILE,$data);
    }
    
    function process_vcard()
    {
        $userId = (int)$this->input->post("userId",true);
        
        $prefix = $this->input->post("prefix",true);
        $prefix = (trim($prefix) != "")?$prefix:"";
        
        $firstname = $this->input->post("firstname",true);
        $lastname = $this->input->post("lastname",true);
        $middlename = $this->input->post("middlename",true);
        $middlename = (trim($middlename) != "")?" ".$middlename." ":"";
        
        $displayname = $this->input->post("displayname",true);
        
        $suffix = $this->input->post("suffix",true);
        $suffix = (trim($suffix != "")?$suffix:"");
        
        
        $organization = $this->input->post("organization",true);
        $title = $this->input->post("title",true);
        $telephonehome = $this->input->post("telephonehome",true);
        $telephoneoffice = $this->input->post("telephoneoffice",true);
        $mobile = $this->input->post("mobile",true);
        $fax = $this->input->post("fax",true);
        $website = ltrim($this->input->post("website",true),"http://");
        $email = $this->input->post("email",true);
        
        $street = $this->input->post("street",true);
        $city = $this->input->post("city",true);
        $country = $this->input->post("country",true);
        $state = $this->input->post("state",true);
        $zip = $this->input->post("zip",true);
        
        $streethome = $this->input->post("streethome",true);
        $cityhome = $this->input->post("cityhome",true);
        $countryhome = $this->input->post("countryhome",true);
        $statehome = $this->input->post("statehome",true);
        $ziphome = $this->input->post("ziphome",true);
        
        $version = $this->input->post("version",true);
        $qrsize = $this->input->post("qrsize",true);        
        $qrwidth = (int)$qrsize[0];
        $qrheight = (int)$qrsize[1];
        $imageformat = $this->input->post("imageformat",true);
        
        
        $this->load->library("form_validation");
        $this->form_validation->set_rules("prefix","Prefix", "trim|xss_clean");
        $this->form_validation->set_rules("firstname","First Name", "trim|xss_clean|required");
        $this->form_validation->set_rules("lastname","Last Name", "trim|xss_clean|required");
        $this->form_validation->set_rules("suffix","Suffix", "trim|xss_clean");
        $this->form_validation->set_rules("organization","Organization", "trim|xss_clean|required");
        $this->form_validation->set_rules("title","Title", "trim|xss_clean|required");
        $this->form_validation->set_rules("telephonehome","Home Phone", "trim|xss_clean");
        $this->form_validation->set_rules("telephoneoffice","Office Phone", "trim|xss_clean|required");
        $this->form_validation->set_rules("mobile","Mobile Number", "trim|xss_clean");
        $this->form_validation->set_rules("fax","Fax Number", "trim|xss_clean");
        $this->form_validation->set_rules("website","Website", "trim|xss_clean|valid_url");
        $this->form_validation->set_rules("email","Email", "trim|xss_clean|valid_email|required");
        
        $this->form_validation->set_rules("street","Street", "trim|xss_clean|required");
        $this->form_validation->set_rules("city","City", "trim|xss_clean|required");
        $this->form_validation->set_rules("country","Country", "trim|xss_clean|required");
        $this->form_validation->set_rules("zip","Zip", "trim|xss_clean|required");
        $this->form_validation->set_rules("state","State", "trim|xss_clean");
        
        $this->form_validation->set_rules("streethome","Home Street", "trim|xss_clean");
        $this->form_validation->set_rules("cityhome","Home City", "trim|xss_clean");
        $this->form_validation->set_rules("countryhome","Home Country", "trim|xss_clean");
        $this->form_validation->set_rules("ziphome","Home Zip", "trim|xss_clean");
        $this->form_validation->set_rules("statehome","Home State", "trim|xss_clean");
        
        $this->form_validation->set_rules("version","Vcard Version", "trim|xss_clean|required");
        $this->form_validation->set_rules("qrsize[]","QR Image Size", "trim|xss_clean|required");
        $this->form_validation->set_rules("imageformat","QR Image Format", "trim|xss_clean|required");
        
        if($this->form_validation->run() == FALSE)
        {
            $json['success'] = false;
            $json['msg'] = validation_errors();
            echo json_encode($json);
            die();
        }
        
        $time = time();
        $vdata = "";
        
        switch ($version):
            case "3":
                $prefixSpace = ($prefix == "")?"": " ";
                $suffixSpace = ($suffix == "")?"": ", ";
                $vdata = "BEGIN:VCARD\nVERSION:3.0\nN:". $lastname .";". $firstname .";".$middlename.";".$prefix.";".$suffix 
                    . "\nFN:".$prefix . $prefixSpace . $firstname .$middlename. $lastname . $suffixSpace . $suffix     
                    . "\nORG:" . $organization 
                    . "\nTITLE:" . $title 
                    . "\nTEL;TYPE=WORK,VOICE:+" . $telephoneoffice 
                    . "\nTEL;TYPE=HOME,VOICE:+" . $telephonehome 
                    . "\nTEL;TYPE=CELL,VOICE:+" . $mobile 
                    . "\nTEL;TYPE=FAX,VOICE:+" . $fax     
                    . "\nURL:http://" . $website         
                    /* addressoffice*/
                    . "\nADR;TYPE=WORK:;;" . $street . ";" 
                    . $city . ";" 
                    . $state . ";" 
                    . $zip . ";" 
                    . $country
                    /* labeloffice*/
                    . "\nLABEL;TYPE=WORK:;;" . $street . '\n,' 
                    . $city . '\n,' 
                    . $state . " " 
                    . $zip . '\n,' 
                    . $country
                
                     /* address home*/
                    . "\nADR;TYPE=HOME:;;" . $streethome . ";" 
                    . $cityhome . ";" 
                    . $statehome . ";" 
                    . $ziphome . ";" 
                    . $countryhome
                    /* label HOME*/
                    . "\nLABEL;TYPE=HOME:;;" . $streethome . '\n,' 
                    . $cityhome . '\n,' 
                    . $statehome . " " 
                    . $ziphome . '\n,' 
                    . $countryhome
                    
                    . "\nEMAIL;TYPE=PREF,INTERNET:" . $email                     
                    . "\nREV:".date('Y-m-d',$time)."T".date('H:i:s',$time)."Z" 
                    . "\nEND:VCARD";
                    break;
                case "2.1":
                    $prefixSpace = ($prefix == "")?"": " ";
                    $suffixSpace = ($suffix == "")?"": ", ";
                    $vdata = "BEGIN:VCARD\nVERSION:2.1\nN:". $lastname .";". $firstname .";".$middlename.";".$prefix.";".$suffix 
                    . "\nFN:".$prefix . $prefixSpace . $firstname .$middlename. $lastname . $suffixSpace . $suffix 
                    . "\nORG:" . $organization 
                    . "\nTITLE:" . $title 
                    . "\nTEL;TYPE=WORK,VOICE:+" . $telephoneoffice 
                    . "\nTEL;TYPE=HOME,VOICE:+" . $telephonehome 
                    . "\nTEL;TYPE=CELL,VOICE:+" . $mobile 
                    . "\nTEL;WORK;FAX:+" . $fax
                    . "\nURL;WORK:http://" . $website
                    /* addressoffice*/
                    . "\nADR;WORK:;;" . $street . ";" 
                    . $city . ";" 
                    . $state . ";" 
                    . $zip . ";" 
                    . $country
                    /* labeloffice*/
                    . "\nLABEL;WORK;ENCODING=QUOTED-PRINTABLE:" . $street . "=0D=0A" 
                    . $city . ", " 
                    . $state . " " 
                    . $zip . "=0D=0A" 
                    . $country
                
                     /* address home*/
                    . "\nADR;HOME:;;" . $streethome . ";" 
                    . $cityhome . ";" 
                    . $statehome . ";" 
                    . $ziphome . ";" 
                    . $countryhome
                    /* label HOME*/
                    . "\nLABEL;HOME;ENCODING=QUOTED-PRINTABLE:" . $streethome . "=0D=0A" 
                    . $cityhome . ", " 
                    . $statehome . " " 
                    . $ziphome . "=0D=0A" 
                    . $countryhome
                    
                    . "\nEMAIL;PREF;INTERNET:" . $email                      
                    . "\nREV:".date('Y-m-d',$time)."T".date('H:i:s',$time)."Z" 
                    . "\nEND:VCARD";
                    break;
                case "4":
                    $prefixSpace = ($prefix == "")?"": " ";
                    $suffixSpace = ($suffix == "")?"": ", ";
                    $vdata = "BEGIN:VCARD\nVERSION:4.0"
                    . "\nN:". $lastname .";". $firstname .";".$middlename.";".$prefix.";".$suffix 
                    . "\nFN:".$prefix . $prefixSpace . $firstname .$middlename. $lastname . $suffixSpace . $suffix 
                    . "\nORG:" . $organization 
                    . "\nTITLE:" . $title 
                    . "\nTEL;TYPE=\"work,voice\";VALUE=uri:tel:+" . $telephoneoffice 
                    . "\nTEL;TYPE=\"home,voice\";VALUE=uri:tel:+" . $telephonehome 
                    . "\nTEL;TYPE=\"cell,voice\";VALUE=uri:tel:+" . $mobile 
                    . "\nTEL;TYPE=\"fax\";VALUE=uri:tel:+" . $fax         
                    /* addressoffice*/
                    . "\nADR;TYPE=work:LABEL=\"" . $street . '\n' 
                    . $city . ", " 
                    . $state . " " 
                    . $zip . '\n' 
                    . $country.'"'
                    /* labeloffice*/
                    . ":;;" . $street . ";" 
                    . $city . ";" 
                    . $state . ";" 
                    . $zip . ";" 
                    . $country
                            
                    /* addresshome*/
                    . "\nADR;TYPE=home:LABEL=\"" . $streethome . '\n' 
                    . $cityhome . ", " 
                    . $statehome . " " 
                    . $ziphome . '\n' 
                    . $countryhome.'"'
                    /* labeloffice*/
                    . ":;;" . $streethome . ";" 
                    . $cityhome . ";" 
                    . $statehome . ";" 
                    . $ziphome . ";" 
                    . $countryhome
                
                    . "\nEMAIL;TYPE=WORK:" . $email
                    . "\nURL:http://" . $website
                    . "\nREV:".date('Y-m-d',$time)."T".date('H:i:s',$time)."Z"   
                    . "\nEND:VCARD";
                    break;                    
        endswitch; 
        
        //$vdata = $this->convertToQrData();
        
        $this->load->helper("string");
        $qrName = random_string("alpha",5).time();
        $qrImageName  = $qrName.".png";
        $qrFolder =  ABS_SERVER_PATH.SITE_FOLDER.ASSETS_FOLDER.TEMPORARY_FOLDER.$qrImageName;
        
        //ini_set('allow_url_fopen', 'on');
        $http = "http://chart.googleapis.com/chart?cht=qr&chs=".$qrwidth."x".$qrheight."&chl=".urlencode($vdata);
        
        //$content = file_get_contents($http);
        //file_put_contents($qrFolder, $content);
        
        if(copy($http, $qrFolder))
        {
            
           $udata = array("userName" => $email, "prefix" => $prefix, "firstName" => $firstname, "lastName" => $lastname, "middleName" => $middlename,
                          "displayName" => $displayname, "suffix" => $suffix, "organization" => $organization,
                          "title" => $title, "telephoneHome" => $telephonehome, "telephoneOffice" => $telephoneoffice,
                          "mobile" => $mobile, "fax" => $fax, "website" => $website, "email" => $email,
                          "modifyId" => $this->my_session->userId);
           $addressdata = array("street" => $street, "city" => $city, "state" => $state, "zip" => $zip, "country" => $country,
                                "streetHome" => $streethome, "cityHome" => $cityhome, "stateHome" => $statehome, "zipHome" => $ziphome, "countryHome" => $countryhome,
                                "modifyId" => $this->my_session->userId);
           
           $this->load->model("users_model");
           $newUserCreated = $this->users_model->addNewUserData($udata,$addressdata,$userId);
           
           if($this->input->post("imageformat",true) == "jpg")
           {
                $this->load->library('image_lib');
                $qrImageName  = $qrName.".jpg";
                
                $conf['source_image'] = $qrFolder;
                $conf['create_thumb'] = false;
                $conf['maintain_ratio'] = TRUE;
                $conf['quality'] = "100%";
                $conf['new_image'] = ABS_SERVER_PATH.SITE_FOLDER.ASSETS_FOLDER.TEMPORARY_FOLDER.$qrImageName;    
                
                $this->image_lib->initialize($conf);
                if ( !$this->image_lib->resize() )
                {
                    $qrImageName = $qrName.".png";
                }
            }
            
            $json['success'] = true;
            $json['src'] = base_url().ASSETS_FOLDER.TEMPORARY_FOLDER.$qrImageName;
            $json['vd'] = $vdata;
            if((int)$newUserCreated > 0 && !is_bool($newUserCreated))
            {
                $json['newUserId'] = $newUserCreated;
            }            
            echo json_encode($json);
            die();
        }
        
        $json['success'] = false;
        $json['msg'] = "Problem Copying the image";
        echo json_encode($json);
        die();
        
    }
    
    function download()
    {
        $userId = (int)$this->input->get("userid",true);
        if($userId <= 0):
            show_404();            
        endif;
        
        $this->load->model("users_model");
        
        $result = $this->users_model->getVcardInfo($userId);
        if(!$result)
        {
            show_404();
        }
        
        $row = $result->row();
        
        $version = $this->input->get("version",true);
        $version = (in_array($version, array('2.1','3','4')))?$version:"3";
        
        $vdata = "";
        
        $prefixSpace = (trim($row->prefix) == "")?"": " ";
        $suffixSpace = (trim($row->suffix) == "")?"": ", ";

        $middlename = (trim($row->middleName) != "")?" ".$row->middleName." ":"";

        $prefix = (trim($row->prefix) == "")?"": $row->prefix ;
        $suffix = (trim($row->suffix) == "")?"": $row->suffix ;
        
        switch ($version):
            case "3":                
                $vdata = "BEGIN:VCARD\nVERSION:3.0\nN:". $row->lastName .";". $row->firstName .";".$row->middleName.";".$row->prefix.";".$row->suffix 
                    . "\nFN:".$row->prefix . $prefixSpace . $row->firstName .$middlename. $row->lastName . $suffixSpace . $row->suffix     
                    . "\nORG:" . $row->organization 
                    . "\nTITLE:" . $row->title 
                    . "\nTEL;TYPE=WORK,VOICE:+" . $row->telephoneOffice 
                    . "\nTEL;TYPE=HOME,VOICE:+" . $row->telephoneHome 
                    . "\nTEL;TYPE=CELL,VOICE:+" . $row->mobile 
                    . "\nTEL;TYPE=FAX,VOICE:+" . $row->fax     
                    . "\nURL:http://" . $row->website         
                    /* addressoffice*/
                    . "\nADR;TYPE=WORK:;;" . $row->street . ";" 
                    . $row->city . ";" 
                    . $row->state . ";" 
                    . $row->zip . ";" 
                    . $row->country
                    /* labeloffice*/
                    . "\nLABEL;TYPE=WORK:;;" . $row->street . '\n,' 
                    . $row->city . '\n,' 
                    . $row->state . " " 
                    . $row->zip . '\n,' 
                    . $row->country
                
                     /* address home*/
                    . "\nADR;TYPE=HOME:;;" . $row->streetHome . ";" 
                    . $row->cityHome . ";" 
                    . $row->stateHome . ";" 
                    . $row->zipHome . ";" 
                    . $row->countryHome
                    /* label HOME*/
                    . "\nLABEL;TYPE=HOME:;;" . $row->streetHome . '\n,' 
                    . $row->cityHome . '\n,' 
                    . $row->stateHome . " " 
                    . $ziphome . '\n,' 
                    . $row->countryHome
                    
                    . "\nEMAIL;TYPE=PREF,INTERNET:" . $row->email                     
                    . "\nREV:".date('Y-m-d',$row->updateDtTm)."T".date('H:i:s',$row->updateDtTm)."Z"   
                    . "\nEND:VCARD";
                    break;
                case "2.1":
                    $vdata = "BEGIN:VCARD\nVERSION:2.1\nN:". $row->lastName .";". $row->firstName.";".$row->middleName.";".$prefix.";".$suffix 
                    . "\nFN:".$prefix.$prefixSpace. $row->firstName . $middlename . $row->lastName. $suffixSpace .$suffix
                    . "\nORG:" . $row->organization 
                    . "\nTITLE:" . $row->title 
                    . "\nTEL;TYPE=WORK,VOICE:+" . $row->telephoneOffice 
                    . "\nTEL;TYPE=HOME,VOICE:" . $row->telephoneHome 
                    . "\nTEL;TYPE=CELL,VOICE:+" . $row->mobile 
                    . "\nTEL;WORK;FAX:+" . $row->fax
                    . "\nURL;WORK:http://" . $row->website        
                    /* addressoffice*/
                    . "\nADR;WORK:;;" . $row->street . ";" 
                    . $row->city . ";" 
                    . $row->state . ";" 
                    . $row->zip . ";" 
                    . $row->country
                    /* labeloffice*/
                    . "\nLABEL;WORK;ENCODING=QUOTED-PRINTABLE:" . $row->street . "=0D=0A" 
                    . $row->city . ", " 
                    . $row->state . " " 
                    . $row->zip . "=0D=0A" 
                    . $row->country
                
                     /* address home*/
                    . "\nADR;HOME:;;" . $row->streetHome . ";" 
                    . $row->cityHome . ";" 
                    . $row->stateHome . ";" 
                    . $row->zipHome . ";" 
                    . $row->countryHome
                    /* label HOME*/
                    . "\nLABEL;HOME;ENCODING=QUOTED-PRINTABLE:" . $row->streetHome . "=0D=0A" 
                    . $row->cityHome . ", " 
                    . $row->stateHome . " " 
                    . $row->zipHome . "=0D=0A" 
                    . $row->countryHome
                    
                    . "\nEMAIL;PREF;INTERNET:" . $row->email                      
                    . "\nREV:".date('Y-m-d',$row->updateDtTm)."T".date('H:i:s',$row->updateDtTm)."Z"   
                    . "\nEND:VCARD";
                    break;
                case "4":
                    $vdata = "BEGIN:VCARD\nVERSION:4.0"
                    . "\nN:". $row->lastName .";". $row->firstName .";".$row->middleName.";".$prefix.";".$suffix 
                    . "\nFN:".$prefix . $prefixSpace . $row->firstName .$middlename. $row->lastName . $suffixSpace . $suffix 
                    . "\nORG:" . $row->organization 
                    . "\nTITLE:" . $row->title 
                    . "\nTEL;TYPE=\"work,voice\";VALUE=uri:tel:+" . $row->telephoneOffice 
                    . "\nTEL;TYPE=\"home,voice\";VALUE=uri:tel:+" . $row->telephoneHome 
                    . "\nTEL;TYPE=\"cell,voice\";VALUE=uri:tel:+" . $row->mobile 
                    . "\nTEL;TYPE=\"fax\";VALUE=uri:tel:+" . $row->fax         
                    /* addressoffice*/
                    . "\nADR;TYPE=work:LABEL=\"" . $row->street . '\n' 
                    . $row->city . ", " 
                    . $row->state . " " 
                    . $row->zip . '\n' 
                    . $row->country.'"'
                    /* labeloffice*/
                    . ":;;" . $row->street . ";" 
                    . $row->city . ";" 
                    . $row->state . ";" 
                    . $row->zip . ";" 
                    . $row->country
                            
                    /* addresshome*/
                    . "\nADR;TYPE=home:LABEL=\"" . $row->streetHome . '\n' 
                    . $row->cityHome . ", " 
                    . $row->stateHome . " " 
                    . $row->zipHome . '\n' 
                    . $row->countryHome.'"'
                    /* labeloffice*/
                    . ":;;" . $row->streetHome . ";" 
                    . $row->cityHome . ";" 
                    . $row->stateHome . ";" 
                    . $row->zipHome . ";" 
                    . $row->countryHome
                
                    . "\nEMAIL;TYPE=WORK:" . $row->email
                    . "\nURL:http://" . $row->website
                    . "\nREV:".date('Y-m-d',$row->updateDtTm)."T".date('H:i:s',$row->updateDtTm)."Z"  
                    . "\nEND:VCARD";
                    break;                    
        endswitch; 
        
        $this->load->helper('download');        
        $name = $row->firstName."_".$row->lastName.".vcf";
        force_download($name, $vdata); 
        
    }
    
}
?>