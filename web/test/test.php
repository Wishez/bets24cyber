<?php

class A
{
    public $prop = 28;
}

function someFunc1($a)
{
    $a->prop++;
    echo $a->prop . "<br>";
}

$a = new A();
echo "1:";
someFunc1($a);
echo "2:";
someFunc1(clone($a));
echo "3:";
echo $a->prop;

$a = "<p>123 'test f\unct\ion'</p>";

echo quotemeta(htmlspecialchars(trim($a)));
var_dump(quotemeta(htmlspecialchars(trim($a), ENT_QUOTES)));
echo "<br>";
$string = "123sdfwdf 4.6wef5werfgwer f7.1we7. 1fwef";

$b = preg_replace("/[\D]+[^\d]+/iu", "/", $string);
var_dump($b);

$string = "{Я помню|Не помню|Какое} чудное {мгновенье|затменье|творенье}";

//preg_match("/(\{{1}\w*|?\w*\}})/iu",$a,$res);
preg_match_all("/[{](.+?)[}]|(\w+)/i", $string, $res, PREG_SET_ORDER);
var_dump("<pre>", $res);

function reaplace($string)
{
    preg_match_all("/[{](.+?)[}]|(\w+)/i", $string, $res, PREG_SET_ORDER);
    foreach ($res as $key => $el) {
        $arr = explode("|", $el[1]);
        $string = str_replace($el[0], $arr[array_rand($arr)], $string);
    }
    return $string;
}

echo reaplace($string);
//echo($res[1]);

// Не менять
interface MultiplierInterface
{
    // Не менять
    public function multiply($x, $y);
}

// Не менять
final class Multiplier implements MultiplierInterface
{
    // Не менять
    public function multiply($x, $y)
    {
        return $x * $y;
    }
}

class decorator
{
    protected $class;

    public function __construct($class)
    {
        $this->class = $class;
    }

    public function __call($method, $arguments = [])
    {
        $arguments = array_map(function ($val) {
            return abs($val);
        }, $arguments);

        return call_user_func_array([$this->class, $method], $arguments);
    }
}

$origin = new Multiplier();
$multiplier = new decorator($origin);

// Не менять
echo $multiplier->multiply(2, -5);


?>


<script>
    var a = "12344321.5678";
    str = String(a);
    str = str.replace(".", ",");
    indx = str.indexOf(',');
    tmp_str = str.substring(0, indx);
    end_str = str.substr(indx);
    str_tmp = "";
    for (i = 0; i < tmp_str.length; i++) {
        str_tmp += tmp_str[i];
        if (((i + 1) % 3) == 0) {
            str_tmp += " ";
        }
    }
    res_str = str_tmp + end_str.substr(0, 3);
    console.log(res_str);
</script>


