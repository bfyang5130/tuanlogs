<?php
/**
 * Created by PhpStorm.
 * User: wuxin
 * Date: 16-2-22
 * Time: 下午4:31
 */

namespace backend\services;


class UserAgentService{
    var $platforms = array (
        'windows nt 10.0'	=> 'Windows 10',
        'windows nt 6.3'	=> 'Windows 8.1',
        'windows nt 6.2'	=> 'Windows 8',
        'windows nt 6.1'	=> 'Windows 7',
        'windows nt 6.0'	=> 'Windows Vista',
        'windows nt 5.2'	=> 'Windows 2003',
        'windows nt 5.1'	=> 'Windows XP',
        'windows nt 5.0'	=> 'Windows 2000',
        'windows nt 4.0'	=> 'Windows NT 4.0',
        'winnt4.0'			=> 'Windows NT 4.0',
        'winnt 4.0'			=> 'Windows NT',
        'winnt'				=> 'Windows NT',
        'windows 98'		=> 'Windows 98',
        'win98'				=> 'Windows 98',
        'windows 95'		=> 'Windows 95',
        'win95'				=> 'Windows 95',
        'windows phone'			=> 'Windows Phone',
        'windows'			=> 'Unknown Windows OS',
        'android'			=> 'Android',
        'blackberry'		=> 'BlackBerry',
        'iphone'			=> 'iOS',
        'ipad'				=> 'iOS',
        'ipod'				=> 'iOS',
        'os x'				=> 'Mac OS X',
        'ppc mac'			=> 'Power PC Mac',
        'freebsd'			=> 'FreeBSD',
        'ppc'				=> 'Macintosh',
        'linux'				=> 'Linux',
        'debian'			=> 'Debian',
        'sunos'				=> 'Sun Solaris',
        'beos'				=> 'BeOS',
        'apachebench'		=> 'ApacheBench',
        'aix'				=> 'AIX',
        'irix'				=> 'Irix',
        'osf'				=> 'DEC OSF',
        'hp-ux'				=> 'HP-UX',
        'netbsd'			=> 'NetBSD',
        'bsdi'				=> 'BSDi',
        'openbsd'			=> 'OpenBSD',
        'gnu'				=> 'GNU/Linux',
        'unix'				=> 'Unknown Unix OS',
        'symbian' 			=> 'Symbian OS'
    );


    // The order of this array should NOT be changed. Many browsers return
    // multiple browser types so we want to identify the sub-type first.
    var $browsers = array(
        'OPR'			=> 'Opera',
        'Flock'			=> 'Flock',
        'Edge'			=> 'Spartan',
        'Chrome'		=> 'Chrome',
        // Opera 10+ always reports Opera/9.80 and appends Version/<real version> to the user agent string
        'Opera.*?Version'	=> 'Opera',
        'Opera'			=> 'Opera',
        'MSIE'			=> 'Internet Explorer',
        'Internet Explorer'	=> 'Internet Explorer',
        'Trident.* rv'	=> 'Internet Explorer',
        'Shiira'		=> 'Shiira',
        'Firefox'		=> 'Firefox',
        'Chimera'		=> 'Chimera',
        'Phoenix'		=> 'Phoenix',
        'Firebird'		=> 'Firebird',
        'Camino'		=> 'Camino',
        'Netscape'		=> 'Netscape',
        'OmniWeb'		=> 'OmniWeb',
        'Safari'		=> 'Safari',
        'Mozilla'		=> 'Mozilla',
        'Konqueror'		=> 'Konqueror',
        'icab'			=> 'iCab',
        'Lynx'			=> 'Lynx',
        'Links'			=> 'Links',
        'hotjava'		=> 'HotJava',
        'amaya'			=> 'Amaya',
        'IBrowse'		=> 'IBrowse',
        'Maxthon'		=> 'Maxthon',
        'Ubuntu'		=> 'Ubuntu Web Browser'
    );

    var $mobiles = array(
        // legacy array, old values commented out
        'mobileexplorer'	=> 'Mobile Explorer',
        //					'openwave'			=> 'Open Wave',
        //					'opera mini'		=> 'Opera Mini',
        //					'operamini'			=> 'Opera Mini',
        //					'elaine'			=> 'Palm',
        'palmsource'		=> 'Palm',
        //					'digital paths'		=> 'Palm',
        //					'avantgo'			=> 'Avantgo',
        //					'xiino'				=> 'Xiino',
        'palmscape'			=> 'Palmscape',
        //					'nokia'				=> 'Nokia',
        //					'ericsson'			=> 'Ericsson',
        //					'blackberry'		=> 'BlackBerry',
        //					'motorola'			=> 'Motorola'

        // Phones and Manufacturers
        'motorola'		=> 'Motorola',
        'nokia'			=> 'Nokia',
        'palm'			=> 'Palm',
        'iphone'		=> 'Apple iPhone',
        'ipad'			=> 'iPad',
        'ipod'			=> 'Apple iPod Touch',
        'sony'			=> 'Sony Ericsson',
        'ericsson'		=> 'Sony Ericsson',
        'blackberry'	=> 'BlackBerry',
        'cocoon'		=> 'O2 Cocoon',
        'blazer'		=> 'Treo',
        'lg'			=> 'LG',
        'amoi'			=> 'Amoi',
        'xda'			=> 'XDA',
        'mda'			=> 'MDA',
        'vario'			=> 'Vario',
        'htc'			=> 'HTC',
        'samsung'		=> 'Samsung',
        'sharp'			=> 'Sharp',
        'sie-'			=> 'Siemens',
        'alcatel'		=> 'Alcatel',
        'benq'			=> 'BenQ',
        'ipaq'			=> 'HP iPaq',
        'mot-'			=> 'Motorola',
        'playstation portable'	=> 'PlayStation Portable',
        'playstation 3'		=> 'PlayStation 3',
        'playstation vita'  	=> 'PlayStation Vita',
        'hiptop'		=> 'Danger Hiptop',
        'nec-'			=> 'NEC',
        'panasonic'		=> 'Panasonic',
        'philips'		=> 'Philips',
        'sagem'			=> 'Sagem',
        'sanyo'			=> 'Sanyo',
        'spv'			=> 'SPV',
        'zte'			=> 'ZTE',
        'sendo'			=> 'Sendo',
        'nintendo dsi'	=> 'Nintendo DSi',
        'nintendo ds'	=> 'Nintendo DS',
        'nintendo 3ds'	=> 'Nintendo 3DS',
        'wii'			=> 'Nintendo Wii',
        'open web'		=> 'Open Web',
        'openweb'		=> 'OpenWeb',

        // Operating Systems
        'android'		=> 'Android',
        'symbian'		=> 'Symbian',
        'SymbianOS'		=> 'SymbianOS',
        'elaine'		=> 'Palm',
        'series60'		=> 'Symbian S60',
        'windows ce'	=> 'Windows CE',

        // Browsers
        'obigo'			=> 'Obigo',
        'netfront'		=> 'Netfront Browser',
        'openwave'		=> 'Openwave Browser',
        'mobilexplorer'	=> 'Mobile Explorer',
        'operamini'		=> 'Opera Mini',
        'opera mini'	=> 'Opera Mini',
        'opera mobi'	=> 'Opera Mobile',
        'fennec'		=> 'Firefox Mobile',

        // Other
        'digital paths'	=> 'Digital Paths',
        'avantgo'		=> 'AvantGo',
        'xiino'			=> 'Xiino',
        'novarra'		=> 'Novarra Transcoder',
        'vodafone'		=> 'Vodafone',
        'docomo'		=> 'NTT DoCoMo',
        'o2'			=> 'O2',

        // Fallback
        'mobile'		=> 'Generic Mobile',
        'wireless'		=> 'Generic Mobile',
        'j2me'			=> 'Generic Mobile',
        'midp'			=> 'Generic Mobile',
        'cldc'			=> 'Generic Mobile',
        'up.link'		=> 'Generic Mobile',
        'up.browser'	=> 'Generic Mobile',
        'smartphone'	=> 'Generic Mobile',
        'cellphone'		=> 'Generic Mobile'
    );

    // There are hundreds of bots but these are the most common.
    var $robots = array(
        'googlebot'			=> 'Googlebot',
        'msnbot'			=> 'MSNBot',
        'slurp'				=> 'Inktomi Slurp',
        'yahoo'				=> 'Yahoo',
        'askjeeves'			=> 'AskJeeves',
        'fastcrawler'		=> 'FastCrawler',
        'infoseek'			=> 'InfoSeek Robot 1.0',
        'lycos'				=> 'Lycos'
    );


    var $agent		= NULL;

    var $is_browser	= FALSE;
    var $is_robot	= FALSE;
    var $is_mobile	= FALSE;

    var $languages	= array();
    var $charsets	= array();

    var $platform	= '';
    var $browser	= '';
    var $version	= '';
    var $mobile		= '';
    var $robot		= '';

    /**
     * Constructor
     *
     * Sets the User Agent and runs the compilation routine
     *
     * @access	public
     * @return	void
     */
    public function __construct($agent = '')
    {
        if ($agent != '')
        {
            $this->agent = $agent;
        }
        else if (isset($_SERVER['HTTP_USER_AGENT']))
        {
            $this->agent = trim($_SERVER['HTTP_USER_AGENT']);
        }

        if ( ! is_null($this->agent))
        {
            $this->_compile_data();
        }

        //log_message('debug', "User Agent Class Initialized");
    }

    // --------------------------------------------------------------------

    /**
     * Compile the User Agent Data
     *
     * @access	private
     * @return	bool
     */
    private function _compile_data()
    {
        $this->_set_platform();

        foreach (array('_set_robot', '_set_browser', '_set_mobile') as $function)
        {
            if ($this->$function() === TRUE)
            {
                break;
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * Set the Platform
     *
     * @access	private
     * @return	mixed
     */
    private function _set_platform()
    {
        if (is_array($this->platforms) AND count($this->platforms) > 0)
        {
            foreach ($this->platforms as $key => $val)
            {
                if (preg_match("|".preg_quote($key)."|i", $this->agent))
                {
                    $this->platform = $val;
                    return TRUE;
                }
            }
        }
        $this->platform = 'Unknown Platform';
    }

    // --------------------------------------------------------------------

    /**
     * Set the Browser
     *
     * @access	private
     * @return	bool
     */
    private function _set_browser()
    {
        if (is_array($this->browsers) AND count($this->browsers) > 0)
        {
            foreach ($this->browsers as $key => $val)
            {
                if (preg_match("|".preg_quote($key).".*?([0-9\.]+)|i", $this->agent, $match))
                {
                    $this->is_browser = TRUE;
                    $this->version = $match[1];
                    $this->browser = $val;
                    $this->_set_mobile();
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    // --------------------------------------------------------------------

    /**
     * Set the Robot
     *
     * @access	private
     * @return	bool
     */
    private function _set_robot()
    {
        if (is_array($this->robots) AND count($this->robots) > 0)
        {
            foreach ($this->robots as $key => $val)
            {
                if (preg_match("|".preg_quote($key)."|i", $this->agent))
                {
                    $this->is_robot = TRUE;
                    $this->robot = $val;
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    // --------------------------------------------------------------------

    /**
     * Set the Mobile Device
     *
     * @access	private
     * @return	bool
     */
    private function _set_mobile()
    {
        if (is_array($this->mobiles) AND count($this->mobiles) > 0)
        {
            foreach ($this->mobiles as $key => $val)
            {
                if (FALSE !== (strpos(strtolower($this->agent), $key)))
                {
                    $this->is_mobile = TRUE;
                    $this->mobile = $val;
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    // --------------------------------------------------------------------

    /**
     * Set the accepted languages
     *
     * @access	private
     * @return	void
     */
    private function _set_languages()
    {
        if ((count($this->languages) == 0) AND isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) AND $_SERVER['HTTP_ACCEPT_LANGUAGE'] != '')
        {
            $languages = preg_replace('/(;q=[0-9\.]+)/i', '', strtolower(trim($_SERVER['HTTP_ACCEPT_LANGUAGE'])));

            $this->languages = explode(',', $languages);
        }

        if (count($this->languages) == 0)
        {
            $this->languages = array('Undefined');
        }
    }

    // --------------------------------------------------------------------

    /**
     * Set the accepted character sets
     *
     * @access	private
     * @return	void
     */
    private function _set_charsets()
    {
        if ((count($this->charsets) == 0) AND isset($_SERVER['HTTP_ACCEPT_CHARSET']) AND $_SERVER['HTTP_ACCEPT_CHARSET'] != '')
        {
            $charsets = preg_replace('/(;q=.+)/i', '', strtolower(trim($_SERVER['HTTP_ACCEPT_CHARSET'])));

            $this->charsets = explode(',', $charsets);
        }

        if (count($this->charsets) == 0)
        {
            $this->charsets = array('Undefined');
        }
    }

    // --------------------------------------------------------------------

    /**
     * Is Browser
     *
     * @access	public
     * @return	bool
     */
    public function is_browser($key = NULL)
    {
        if ( ! $this->is_browser)
        {
            return FALSE;
        }

        // No need to be specific, it's a browser
        if ($key === NULL)
        {
            return TRUE;
        }

        // Check for a specific browser
        return array_key_exists($key, $this->browsers) AND $this->browser === $this->browsers[$key];
    }

    // --------------------------------------------------------------------

    /**
     * Is Robot
     *
     * @access	public
     * @return	bool
     */
    public function is_robot($key = NULL)
    {
        if ( ! $this->is_robot)
        {
            return FALSE;
        }

        // No need to be specific, it's a robot
        if ($key === NULL)
        {
            return TRUE;
        }

        // Check for a specific robot
        return array_key_exists($key, $this->robots) AND $this->robot === $this->robots[$key];
    }

    // --------------------------------------------------------------------

    /**
     * Is Mobile
     *
     * @access	public
     * @return	bool
     */
    public function is_mobile($key = NULL)
    {
        if ( ! $this->is_mobile)
        {
            return FALSE;
        }

        // No need to be specific, it's a mobile
        if ($key === NULL)
        {
            return TRUE;
        }

        // Check for a specific robot
        return array_key_exists($key, $this->mobiles) AND $this->mobile === $this->mobiles[$key];
    }

    // --------------------------------------------------------------------

    /**
     * Is this a referral from another site?
     *
     * @access	public
     * @return	bool
     */
    public function is_referral()
    {
        if ( ! isset($_SERVER['HTTP_REFERER']) OR $_SERVER['HTTP_REFERER'] == '')
        {
            return FALSE;
        }
        return TRUE;
    }

    // --------------------------------------------------------------------

    /**
     * Agent String
     *
     * @access	public
     * @return	string
     */
    public function agent_string()
    {
        return $this->agent;
    }

    // --------------------------------------------------------------------

    /**
     * Get Platform
     *
     * @access	public
     * @return	string
     */
    public function platform()
    {
        return $this->platform;
    }

    // --------------------------------------------------------------------

    /**
     * Get Browser Name
     *
     * @access	public
     * @return	string
     */
    public function browser()
    {
        return $this->browser;
    }

    // --------------------------------------------------------------------

    /**
     * Get the Browser Version
     *
     * @access	public
     * @return	string
     */
    public function version()
    {
        return $this->version;
    }

    // --------------------------------------------------------------------

    /**
     * Get The Robot Name
     *
     * @access	public
     * @return	string
     */
    public function robot()
    {
        return $this->robot;
    }
    // --------------------------------------------------------------------

    /**
     * Get the Mobile Device
     *
     * @access	public
     * @return	string
     */
    public function mobile()
    {
        return $this->mobile;
    }

    // --------------------------------------------------------------------

    /**
     * Get the referrer
     *
     * @access	public
     * @return	bool
     */
    public function referrer()
    {
        return ( ! isset($_SERVER['HTTP_REFERER']) OR $_SERVER['HTTP_REFERER'] == '') ? '' : trim($_SERVER['HTTP_REFERER']);
    }

    // --------------------------------------------------------------------

    /**
     * Get the accepted languages
     *
     * @access	public
     * @return	array
     */
    public function languages()
    {
        if (count($this->languages) == 0)
        {
            $this->_set_languages();
        }

        return $this->languages;
    }

    // --------------------------------------------------------------------

    /**
     * Get the accepted Character Sets
     *
     * @access	public
     * @return	array
     */
    public function charsets()
    {
        if (count($this->charsets) == 0)
        {
            $this->_set_charsets();
        }

        return $this->charsets;
    }

    // --------------------------------------------------------------------

    /**
     * Test for a particular language
     *
     * @access	public
     * @return	bool
     */
    public function accept_lang($lang = 'en')
    {
        return (in_array(strtolower($lang), $this->languages(), TRUE));
    }

    // --------------------------------------------------------------------

    /**
     * Test for a particular character set
     *
     * @access	public
     * @return	bool
     */
    public function accept_charset($charset = 'utf-8')
    {
        return (in_array(strtolower($charset), $this->charsets(), TRUE));
    }


}