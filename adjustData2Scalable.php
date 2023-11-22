<?php 

    $LOCAL_FILE_MY_CNF_MODEL = '/etc/my.cnf.model';

    if(!is_file($LOCAL_FILE_MY_CNF_MODEL)) {
        copy("https://raw.githubusercontent.com/lucaswdm/mysql-php-fpm-scalable/main/my.cnf.model", $LOCAL_FILE_MY_CNF_MODEL);
    }

    if(!is_file($LOCAL_FILE_MY_CNF_MODEL)) { exit('$LOCAL_FILE_MY_CNF_MODEL #404'); }

    $MODEL = file_get_contents($LOCAL_FILE_MY_CNF_MODEL);

    $MEMORY = floor(intval(shell_exec('free -m | grep Mem | awk \'{print $2}\'')) / 1024);

    $MEMORY = max(2,$MEMORY);

    $MEDIUM = ceil($MEMORY / 2);

    #echo $MEMORY . PHP_EOL; echo $MEDIUM . PHP_EOL;

    if(!empty($MODEL))
    {
        #echo $MODEL;
        $NEW = str_replace('{MEMORY}', $MEDIUM, $MODEL);
        file_put_contents('/etc/my.cnf', $NEW);
        system("/usr/sbin/service mysql restart");
        system("/usr/sbin/service mysqld restart");
        system("/usr/sbin/service mariadb restart");
        echo $NEW . PHP_EOL;
    }




    $QTDE_CORES = intval(shell_exec('cat /proc/cpuinfo | grep processor | wc -l'));

    #echo $QTDE_CORES . PHP_EOL; exit;

    $PHP_FPM_WWW_CONF_FILE = '/etc/php-fpm.d/www.conf.model';

    
    if(!is_file($PHP_FPM_WWW_CONF_FILE)) {
        copy("https://raw.githubusercontent.com/lucaswdm/mysql-php-fpm-scalable/main/www.conf.model", $PHP_FPM_WWW_CONF_FILE);
    }

    if(!is_file($PHP_FPM_WWW_CONF_FILE)) { exit('$LOCAL_FILE_MY_CNF_MODEL #404'); }

    $MODEL_FPM = file_get_contents($PHP_FPM_WWW_CONF_FILE);

    if(!empty($MODEL_FPM))
    {
        #echo $MODEL_FPM;
        $NEW_FPM = str_replace('{CORES64}', ($QTDE_CORES*64), $MODEL_FPM);
        $NEW_FPM = str_replace('{CORES}', ($QTDE_CORES), $NEW_FPM);
        file_put_contents('/etc/php-fpm.d/www.conf', $NEW_FPM);
        system("/usr/sbin/service php-fpm restart");
        echo $NEW_FPM . PHP_EOL;
    }
