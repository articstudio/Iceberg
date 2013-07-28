<?php
/*
interface TestInterface
{
    static public function Show($a, $b);
    static public function ShowStatic();
}

abstract class Test implements TestInterface
{
    static public $DEFAULT = 'DEFAULT';
    static public $VAR = 'VAR1';
    
    static protected function PrintShow($str)
    {
        print $str;
    }
    
    static public function Exec()
    {
        print 'Executed' . "\n";
    }
}

class Testing extends Test{
    
    static public $VAR = 'VAR2';
    
    static protected function PrintShow($str)
    {
        print '=> ' . $str;
    }
    
    static public function Show($a, $b)
    {
       static::PrintShow('Show: '. $a . ' / ' . $b . "\n");
    }
    
    static public function SetStatic($v)
    {
        static::$VAR = $v;
    }
    
    static public function ShowStatic()
    {
        print 'ShowStatic: '. self::$VAR . "\n";
    }
}

Testing::Exec();
Testing::Show('a', 'b');
Testing::SetStatic('aaaaaaaaaaa');
Testing::ShowStatic();
*/
/*
$arr = array(
    '0.2.1.3' => 'aaaaaaaaaaaaa',
    '1.0.1.2' => 'aaaaaaaaaaaaa',
    '0.1.1.2' => 'aaaaaaaaaaaaa',
    '0.2.1.2' => 'aaaaaaaaaaaaa',
    '1.1.1.2' => 'aaaaaaaaaaaaa'
);

var_dump($arr);

krsort($arr, SORT_NUMERIC);

var_dump($arr);
*/

function reOrderArray($arr, $from, $to)
{
    if (is_array($arr) && $from!=$to)
    {
        var_dump($arr);
        var_dump($from);
        $el = array_splice($arr, $from, 1);
        var_dump($el);
        $begin = array_splice($arr, 0, $to);
        $arr = array_merge($begin, $el, $arr);
    }
    return $arr;
}
/*
$arr = array(
    'ç' => 0,
    'a' => 1,
    'b' => 2,
    'c' => 3,
    'd' => 4,
    'e' => 5,
    'f' => 6,
    'g' => 7,
    'h' => 8,
    'i' => 9,
    'j' => 10,
);

var_dump($arr);

$new = reOrderArray($arr, 'b', 6, 3);

var_dump($new);
*/

$arr = array(
    0 => 'aaaaaaaaaaaaaaa',
    1 => 'bbbbbbbbbbbbbbbbbb',
    2 => 'ccccccccccccccccccccc',
    3 => 'dddddddddddddddddd'
);

var_dump($arr);

$new = reOrderArray($arr, 1, 2);

var_dump($new);