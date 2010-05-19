<?php
class Uri extends object {
	private $var = array();
	
	static function getInstance(){
		return parent::getInstance(__CLASS__);
	}
	
	public function __static(){
		
	}
	
	function parse(){
		$result = array('uri'=>'', 'domain'=>'', 'path'=>'');
		$uri = self::get_uri_string();
	}
	
	function get_request_uri(){
		return $_SESSION['REQUEST_URI'];
	}
	
	//获取域名
	function get_domain(){
		$list = array('HTTP_HOST', 'SERVER_NAME');
		$domain = "";
		foreach ($list as $k){
			if (isset($_SERVER[$k])){
				$domain = $_SERVER[$k];
				break;
			}
		}
		
		return $domain;
	}

	

    //获取rf
    static function getRf()
    {
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    }

    //获取ua
    static function getUa()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    }

    //获取客户端ip
    static function get_client_ip()
    {
        static $realip = NULL;

        if ($realip !== NULL)
        {
            return $realip;
        }

        if (isset($_SERVER))
        {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

                /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
                foreach ($arr AS $ip)
                {
                    $ip = trim($ip);

                    if ($ip != 'unknown')
                    {
                        $realip = $ip;

                        break;
                    }
                }
            }
            elseif (isset($_SERVER['HTTP_CLIENT_IP']))
            {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            }
            else
            {
                if (isset($_SERVER['REMOTE_ADDR']))
                {
                    $realip = $_SERVER['REMOTE_ADDR'];
                }
                else
                {
                    $realip = '0.0.0.0';
                }
            }
        }
        else
        {
            if (getenv('HTTP_X_FORWARDED_FOR'))
            {
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            }
            elseif (getenv('HTTP_CLIENT_IP'))
            {
                $realip = getenv('HTTP_CLIENT_IP');
            }
            else
            {
                $realip = getenv('REMOTE_ADDR');
            }
        }

        preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
        $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';

        return $realip;
    }

    static function get_uri_string()
    {
        $uri_string = '';
        if (!empty($_GET))
        {
            if (!empty($_GET['__path__']))
            {
                $uri_string = trim($_GET['__path__'], '/');
            }
        }
        elseif (isset($_SERVER['PATH_INFO']) && trim($_SERVER['PATH_INFO'], '/') != '')
        {
            $uri_string = trim($_SERVER['PATH_INFO'], '/');
        }
        return $uri_string;
    }
}
?>