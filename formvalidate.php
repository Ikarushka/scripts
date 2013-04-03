<?php
        error_reporting(0);//отключаем сообщения об ошибках
        
        require_once 'assets/snippets/SetUpShopSnip/gdb_config.php';//подключаем конфиги GDB
        require_once 'assets/snippets/SetUpShopSnip/validation_error_ms.php';//подключаем  сообщения об ошибках
        require_once 'assets/snippets/SetUpShopSnip/formvalidate_class.php';//подключаем класс
        
        if($_POST['action'] && $_POST['action'] == 'sendform'){
          
                  //ВСТРЕЧАЕМ ДАННЫЕ ИЗ ФОРМЫ
                  $DATA['subdomain'] = array('id' => 'subdomain', 'value' => mb_strtolower(trim($_POST['subdomain'])));
                  $DATA['email']     = array('id' => 'email',     'value' => mb_strtolower(trim($_POST['email'])));
                  $DATA['pass1']     = array('id' => 'pass1',     'value' => trim($_POST['pass1']));
                  $DATA['pass2']     = array('id' => 'pass2',     'value' => trim($_POST['pass2']));
                  $DATA['agree']     = array('id' => 'agree',     'value' => trim($_POST['agree']));

                  $validate = new FormValidate();
                  $validate->GDBConf = $GDBConf;
                  $errArr = "";//инициируем массив под сообщение об ошибках

                  //ПРОВЕРЯЕМ SUBDOMAIN
                  $ValidationRulls[0] = array('typeCheck'=>'obligate','param'=>'', 'errMs'=>$errMs['necessaryCheck']);
                  $ValidationRulls[1] = array('typeCheck'=>'minNumSimbol','param'=>'3', 'errMs'=>$errMs['minCheck']);
                  $ValidationRulls[2] = array('typeCheck'=>'maxNumSimbol','param'=>'50', 'errMs'=>$errMs['maxCheck']);
                  $ValidationRulls[3] = array('typeCheck'=>'format','param'=>'/(^[a-zA-Z0-9]+([a-zA-Z0-9\-]*))$/', 'errMs'=>$errMs['formatCheck']); 
                  $ValidationRulls[4] = array('typeCheck'=>'uniq','param'=>array('TableName'=>'sp_user', 'FieldName'=>'subdomain'), 'errMs'=>$errMs['formatCheck']);
                  
                  $resultCheck = $validate->oneFieldValidater($DATA['subdomain'], $ValidationRulls); 
                  if($resultCheck) $errArr[] = $resultCheck;

                  //ПРОВЕРЯЕМ EMAIL
                  $ValidationRulls = '';
                  $ValidationRulls[0] = array('typeCheck'=>'obligate','param'=>'', 'errMs'=>$errMs['necessaryCheck']);
                  $ValidationRulls[1] = array('typeCheck'=>'minNumSimbol','param'=>'6', 'errMs'=>$errMs['minCheck']);
                  $ValidationRulls[2] = array('typeCheck'=>'maxNumSimbol','param'=>'254', 'errMs'=>$errMs['maxCheck']);
                  $ValidationRulls[3] = array('typeCheck'=>'format','param'=>'/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i', 'errMs'=>$errMs['formatCheck']); 
                  $ValidationRulls[4] = array('typeCheck'=>'uniq','param'=>array('TableName'=>'sp_user', 'FieldName'=>'email'), 'errMs'=>$errMs['formatCheck']);
                  
                  $resultCheck = $validate->oneFieldValidater($DATA['email'], $ValidationRulls); 
                  if($resultCheck) $errArr[] = $resultCheck;
                  
                  //ПРОВЕРЯЕМ PASS1
                  $ValidationRulls = '';
                  $ValidationRulls[0] = array('typeCheck'=>'obligate','param'=>'', 'errMs'=>$errMs['necessaryCheck']);
                  $ValidationRulls[1] = array('typeCheck'=>'minNumSimbol','param'=>'6', 'errMs'=>$errMs['minCheck']);
                  $ValidationRulls[2] = array('typeCheck'=>'maxNumSimbol','param'=>'32', 'errMs'=>$errMs['maxCheck']);
                  $ValidationRulls[3] = array('typeCheck'=>'format','param'=>'/(^[a-zA-Z0-9]+([a-zA-Z0-9]*))$/', 'errMs'=>$errMs['formatCheck']);
                  
                  $resultCheck = $validate->oneFieldValidater($DATA['pass1'], $ValidationRulls); 
                  if($resultCheck) $errArr[] = $resultCheck;
                  
                  //ПРОВЕРЯЕМ PASS2
                  $ValidationRulls = '';
                  $ValidationRulls[0] = array('typeCheck'=>'obligate','param'=>'', 'errMs'=>$errMs['necessaryCheck']);
                  $ValidationRulls[1] = array('typeCheck'=>'minNumSimbol','param'=>'6', 'errMs'=>$errMs['minCheck']);
                  $ValidationRulls[2] = array('typeCheck'=>'maxNumSimbol','param'=>'32', 'errMs'=>$errMs['maxCheck']);
                  $ValidationRulls[3] = array('typeCheck'=>'format','param'=>'/(^[a-zA-Z0-9]+([a-zA-Z0-9]*))$/', 'errMs'=>$errMs['formatCheck']);
                  $ValidationRulls[4] = array('typeCheck'=>'equal','param'=>$DATA['pass1'], 'errMs'=>$errMs['equalCheck']);
                  
                  $resultCheck = $validate->oneFieldValidater($DATA['pass2'], $ValidationRulls);
                  if($resultCheck) $errArr[] = $resultCheck;
                 
                  //ПРОВЕРЯЕМ AGREE
                  $ValidationRulls = '';
                  $ValidationRulls[0] = array('typeCheck'=>'obligate','param'=>'', 'errMs'=>$errMs['agreeCheck']);
                  $ValidationRulls[1] = array('typeCheck'=>'equal','param'=>'agree', 'errMs'=>$errMs['agreeCheck']);
                  
                  $resultCheck = $validate->oneFieldValidater($DATA['agree'], $ValidationRulls);
                  if($resultCheck) $errArr[] = $resultCheck;
                  
                  if($errArr){//есть ошибки

                        $serverReport = array(
                            'validFlg'  =>'err1',
                            'errFieldMs'=>$errArr,
                            'errTitle'  =>$errMs['titleErr1'],
                            'errWish'   =>$errMs['wishErr1']);

                    }else{//нет ошибок

                        $serverReport = array(
                            'validFlg'  =>'ok',
                            'errFieldMs'=>'',
                            'errTitle'  =>'',
                            'errWish'   =>''
                            );
                    }
                  
          
        }else{//если данные не пришли
            
                  $serverReport = array(
                      'validFlg'  =>'err2',
                      'errFieldMs'=>'',
                      'errTitle'  =>$errMs['titleErr2'],
                      'errWish'   =>$errMs['wishErr2']); 
            
        }
        
        //ШЛЕМ ОТВЕТ        
        echo htmlspecialchars(json_encode($serverReport), ENT_NOQUOTES);
?>
