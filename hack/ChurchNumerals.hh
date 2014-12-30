<?hh

/*
 * チャーチ数を用いてフィボナッチ数を計算する
 */

/*
 * いくつかのチャーチ数を定義する
 *
 *   CN = (T -> T) -> T -> T   (ただし T は任意の型)
 * 
 *   $zero  : CN
 *   $one   : CN
 *   $two   : CN
 *   $three : CN
 */

$zero  = $f ==> $x ==> $x;
$one   = $f ==> $x ==> $f($x);
$two   = $f ==> $x ==> $f($f($x));
$three = $f ==> $x ==> $f($f($f($x)));

/*
 * サンプル (チャーチ数が表す自然数の回数だけ犬が吠える)
 *
 *   $bowwow : Dog -> Dog
 *   $dog    : Dog
 */

class Dog {
    public function bowwow() {
        echo 'ワン';
        return $this;
    }
}

$bowwow = $dog ==> $dog->bowwow();
$dog = new Dog();

($zero ($bowwow))($dog); echo "\n"; // 吠えない
($one  ($bowwow))($dog); echo "\n"; // ワン
($two  ($bowwow))($dog); echo "\n"; // ワンワン
($three($bowwow))($dog); echo "\n"; // ワンワンワン

/*
 * 与えられたチャーチ数の「次の」チャーチ数を戻す関数
 *
 *   $succ : CN -> CN
 *
 *   $four : CN
 *   $five : CN
 */

$succ = $n ==> $f ==> $x ==> $f(($n($f))($x));

(($succ($three))($bowwow))($dog); echo "\n"; // ワンワンワンワン

$four = $succ($three);
$five = $succ($four);

($four($bowwow))($dog); echo "\n"; // ワンワンワンワン
($five($bowwow))($dog); echo "\n"; // ワンワンワンワンワン

/*
 * 加算と乗算
 *
 *   $plus : CN -> CN -> CN
 *   $mult : CN -> CN -> CN
 */

$plus = $m ==> $n ==> $f ==> $x ==> ($m($f))(($n($f))($x));
$mult = $m ==> $n ==> $f ==> $x ==> ($m($n($f)))($x);

// $plus = $m ==> $n ==> ($m($succ))($n);        // $n に $succ を $m 回適用する
// $mult = $m ==> $n ==> ($m($plus($n)))($zero); // $zero に $plus($n) を $m 回適用する

/*
 * チャーチ数から通常の自然数に変換する関数 (動作確認用)
 *
 *   to_int : CN -> int
 */

function to_int($n) {
    return ($n($i ==> $i + 1))(0);
}

echo '5 + 3 = ' . to_int(($plus($five))($three)) . "\n"; // 8
echo '5 * 3 = ' . to_int(($mult($five))($three)) . "\n"; // 15

/*
 * 与えられたチャーチ数の「一つ前の」チャーチ数を戻す関数
 *
 *   $pred : CN -> CN
 *
 * ------------------------------------------------------------------------------------------------------
 *
 * 1. $zero の場合
 *
 * $pred($zero)
 *   =   $f ==> $x ==> (($zero($g ==> $h ==> $h($g($f))))($u ==> $x))($u ==> $u)
 *   =   $f ==> $x ==> ($u ==> $x)($u ==> $u)
 *   =   $f ==> $x ==> $x
 *   =   $zero
 *
 * 2. $succ($n) の場合
 *
 * // $succ = $n ==> $f ==> $x ==> $f(($n($f))($x));
 *
 * $pred($succ($n))
 *   =   $f ==> $x ==> ((($succ($n))($g ==> $h ==> $h($g($f))))($u ==> $x))($u ==> $u)
 *   =   $f ==> $x ==> (($g ==> $h ==> $h($g($f)))(($n($g ==> $h ==> $h($g($f))))($u ==> $x)))($u ==> $u)
 *   =   $f ==> $x ==> ($h ==> $h((($n($g ==> $h ==> $h($g($f))))($u ==> $x))($f)))($u ==> $u)
 *                                ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 *                                この波線部の式に注目する
 *
 * 2-A. $n == $zero の場合
 *
 *       (($n($g ==> $h ==> $h($g($f))))($u ==> $x))($f)
 *   =   (($zero($g ==> $h ==> $h($g($f))))($u ==> $x))($f)
 *   =   ($u ==> $x)($f)
 *   =   $x
 *   =   ($zero($f))($x)
 *   =   ($n($f))($x)
 *
 * 2-B. $n == $succ($m) の場合
 *
 *       (($n($g ==> $h ==> $h($g($f))))($u ==> $x))($f)
 *   =   ((($succ($m))($g ==> $h ==> $h($g($f))))($u ==> $x))($f)
 *   =   (($g ==> $h ==> $h($g($f)))(($m($g ==> $h ==> $h($g($f))))($u ==> $x)))($f)
 *   =   ($h ==> $h((($m($g ==> $h ==> $h($g($f))))($u ==> $x))($f)))($f)
 *   =   $f((($m($g ==> $h ==> $h($g($f))))($u ==> $x))($f))
 *          ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 *          $n が $m に変わっただけで上の波線部の式そのもの
 *
 *   =   $f($f( ... $f($x))))
 *       ~~~~~~~~~~~~~
 *       $n が表す自然数の回数だけ $f が現れる
 *
 *   =   ($n($f))($x)
 *
 * したがって全体の式は
 *
 * $pred($succ($n))
 *   =   $f ==> $x ==> ($h ==> $h((($n($g ==> $h ==> $h($g($f))))($u ==> $x))($f)))($u ==> $u)
 *   =   $f ==> $x ==> ($h ==> $h(($n($f))($x)))($u ==> $u)
 *   =   $f ==> $x ==> ($n($f))($x)
 *   =   $n
 */
$pred = $n ==> $f ==> $x ==> (($n($g ==> $h ==> $h($g($f))))($u ==> $x))($u ==> $u);

echo 'pred(5) = ' . to_int($pred($five)) . "\n"; // 4
echo 'pred(1) = ' . to_int($pred($one )) . "\n"; // 0
echo 'pred(0) = ' . to_int($pred($zero)) . "\n"; // 0

/*
 * チャーチ数が $zero かそれ以外かで違う値を返す関数
 *
 *   $ifzero : CN -> T -> T -> T
 *
 * 備考
 *   - $t と $f は異なる型でもよいが、その場合は $ifzero に型が付かない
 *   - 今回のサンプルの範囲では T は CN として使っている
 */

$ifzero = $n ==> $t ==> $f ==> ($n($x ==> $f))($t);

echo 'ifzero(0) = ' . to_int((($ifzero($zero ))($one))($zero)) . "\n"; // 1
echo 'ifzero(3) = ' . to_int((($ifzero($three))($one))($zero)) . "\n"; // 0

/*
 * 不動点演算子 (Z コンビネータ)
 *
 *   $fix : 型付け不可能
 *
 * ------------------------------------------------------------------------------------------------------
 *
 * $RecursiveFun = ($f ==> 何かの処理をする。その処理の中で $f を呼び出す) として
 *
 * $fix($RecursiveFun)
 *   =   ($x ==> $RecursiveFun($y ==> ($x($x))($y)))($x ==> $RecursiveFun($y ==> ($x($x))($y)))
 *       ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 *       これを $Z と置く
 *
 *   =   $Z($Z)
 *   =   $RecursiveFun($y ==> ($Z($Z))($y))
 *   =   $RecursiveFun($y ==> ($fix($RecursiveFun))($y))
 *   =   何かの処理をする。仮引数 $f は実引数 ($y ==> ($fix($RecursiveFun))($y)) で呼ばれている
 *
 * この処理の中で $f($m) という形の関数呼び出しが発生すると
 *
 * $f($m)
 *   =   ($y ==> ($fix($RecursiveFun))($y))($m)
 *   =   ($fix($RecursiveFun))($m)
 *        ~~~~~~~~~~~~~~~~~~~
 *        再帰呼び出しになる
 */

$fix = $f ==> ($x ==> $f($y ==> ($x($x))($y)))($x ==> $f($y ==> ($x($x))($y)));

/*
 * フィボナッチ数
 *
 *   $fibonacci : CN -> CN
 *
 * 備考
 *   $ifzero の then, else 部分を ($u ==> ...) としているのは遅延評価を実現するため。
 *   (($ifzero($c))($u ==> $t))($u ==> $f) とすることで、$c の値によって ($u ==> $t) または ($u ==> $f) が戻される。
 *   これに適当な実引数を与えることで、$t と $f の一方のみが計算されるようにしている。
 *   もし (($ifzero($c))($t))($f) と書いてしまうと、(多くのプログラミング言語では) $c の値によらず $t も $f も計算してしまう。
 */

$fibonacci = $fix(
  $fib ==> $n ==>
    ((($ifzero($n))
      ($u ==> $zero))
      ($u ==>
        ((($ifzero($pred($n)))
          ($u ==> $succ($zero)))
          ($u ==> ($plus($fib($pred($n))))($fib($pred($pred($n))))))
        ($u ==> $u)))
    ($u ==> $u)
);

for ($n = $zero; to_int($n) <= 10; $n = $succ($n)) {
    echo 'fibonacci(' . to_int($n) . ') = ' . to_int($fibonacci($n)) . "\n";
}

/*
 * 階乗
 *
 *   $factorial : CN -> CN
 */

$factorial = $fix(
  $fact ==> $n ==>
    ((($ifzero($n))
      ($u ==> $succ($zero)))
      ($u ==> ($mult($n))($fact($pred($n)))))
    ($u ==> $u)
);

for ($n = $zero; to_int($n) <= 5; $n = $succ($n)) {
    echo 'factorial(' . to_int($n) . ') = ' . to_int($factorial($n)) . "\n";
}
