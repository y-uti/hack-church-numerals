<?php
// require_once ($GLOBALS['HACKLIB_ROOT']);
$zero = function ($f) {
  return function ($x) {
    return $x;
  };
};
$one = function ($f) {
  return function ($x) use ($f) {
    return $f($x);
  };
};
$two = function ($f) {
  return function ($x) use ($f) {
    return $f($f($x));
  };
};
$three = function ($f) {
  return function ($x) use ($f) {
    return $f($f($f($x)));
  };
};
class Dog {
  public function bowwow() {
    echo ('ワン');
    return $this;
  }
}
$bowwow = function ($dog) {
  return $dog->bowwow();
};
$dog = new Dog();
$_1 = $zero($bowwow);
$_1($bowwow);
echo ("\n");
$_1 = $one($bowwow);
$_1($dog);
echo ("\n");
$_1 = $two($bowwow);
$_1($dog);
echo ("\n");
$_1 = $three($bowwow);
$_1($dog);
echo ("\n");
$succ = function ($n) {
  return function ($f) use ($n) {
    return function ($x) use ($f, $n, $f) {
      $_1 = $n($f);
      return $f($_1($x));
    };
  };
};
$_1 = $succ($three);
$_2 = $_1($bowwow);
$_2($dog);
echo ("\n");
$four = $succ($three);
$five = $succ($four);
$_1 = $four($bowwow);
$_1($dog);
echo ("\n");
$_1 = $five($bowwow);
$_1($dog);
echo ("\n");
$plus = function ($m) {
  return function ($n) use ($m) {
    return function ($f) use ($m, $n) {
      return function ($x) use ($m, $f, $n, $f) {
        $_1 = $m($f);
        $_2 = $n($f);
        return $_1($_2($x));
      };
    };
  };
};
$mult = function ($m) {
  return function ($n) use ($m) {
    return function ($f) use ($m, $n) {
      return function ($x) use ($m, $n, $f) {
        $_1 = $m($n($f));
        return $_1($x);
      };
    };
  };
};
function to_int($n) {
  $_1 = $n(
    function ($i) {
      return $i + 1;
    }
  );
  return $_1(0);
}
$_1 = $plus($five);
echo ('5 + 3 = '.to_int($_1($three))."\n");
$_1 = $mult($five);
echo ('5 * 3 = '.to_int($_1($three))."\n");
$pred = function ($n) {
  return function ($f) use ($n) {
    return function ($x) use ($n, $f) {
      $_1 = $n(
        function ($g) use ($f) {
          return function ($h) use ($g, $f) {
            return $h($g($f));
          };
        }
      );
      $_2 = $_1(
        function ($u) use ($x) {
          return $x;
        }
      );
      return $_2(
        function ($u) {
          return $u;
        }
      );
    };
  };
};
echo ('pred(5) = '.to_int($pred($five))."\n");
echo ('pred(1) = '.to_int($pred($one))."\n");
echo ('pred(0) = '.to_int($pred($zero))."\n");
$ifzero = function ($n) {
  return function ($t) use ($n) {
    return function ($f) use ($n, $t) {
      $_1 = $n(
        function ($x) use ($f) {
          return $f;
        }
      );
      return $_1($t);
    };
  };
};
$_1 = $ifzero($zero);
$_2 = $_1($one);
echo ('ifzero(0) = '.to_int($_2($zero))."\n");
$_1 = $ifzero($three);
$_2 = $_1($one);
echo ('ifzero(3) = '.to_int($_2($zero))."\n");
$fix = function ($f) {
  $_1 = function ($x) use ($f) {
            return $f(
              function ($y) use ($x) {
                $_1 = $x($x);
                return $_1($y);
              }
            );
          };
  return $_1(
    function ($x) use ($f) {
      return $f(
        function ($y) use ($x) {
          $_1 = $x($x);
          return $_1($y);
        }
      );
    }
  );
};
$fibonacci = $fix(
  function (
    $fib
  ) use ($ifzero, $zero, $ifzero, $pred, $succ, $zero, $plus, $pred) {
    return function (
      $n
    ) use (
    $ifzero,
    $zero,
    $ifzero,
    $pred,
    $succ,
    $zero,
    $plus,
    $fib,
    $pred,
    $fib,
    $pred
    ) {
      $_1 = $ifzero($n);
      $_2 = $_1(
        function ($u) use ($zero) {
          return $zero;
        }
      );
      $_3 = $_2(
        function (
          $u
        ) use (
        $ifzero,
        $pred,
        $n,
        $succ,
        $zero,
        $plus,
        $fib,
        $pred,
        $n,
        $fib,
        $pred,
        $n
        ) {
          $_1 = $ifzero($pred($n));
          $_2 = $_1(
              function ($u) use ($succ, $zero) {
                return $succ($zero);
              }
            );
          $_3 = $_2(
              function (
                $u
              ) use ($plus, $fib, $pred, $n, $fib, $pred, $n) {
                $_1 = $plus($fib($pred($n)));
                return $_1($fib($pred($pred($n))));
              }
            );
          return $_3(
              function ($u) {
                return $u;
              }
            );
        }
      );
      return $_3(
        function ($u) {
          return $u;
        }
      );
    };
  }
);
for ($n = $zero; to_int($n) <= 10; $n = $succ($n)) {
  echo ('fibonacci('.to_int($n).') = '.to_int($fibonacci($n))."\n");
}
$factorial = $fix(
  function ($fact) use ($ifzero, $succ, $zero, $mult, $pred) {
    return function ($n) use ($ifzero, $succ, $zero, $mult, $fact, $pred) {
      $_1 = $ifzero($n);
      $_2 = $_1(
        function ($u) use ($succ, $zero) {
          return $succ($zero);
        }
      );
      $_3 = $_2(
        function ($u) use ($mult, $n, $fact, $pred, $n) {
          $_1 = $mult($n);
          return $_1($fact($pred($n)));
        }
      );
      return $_3(
        function ($u) {
          return $u;
        }
      );
    };
  }
);
for ($n = $zero; to_int($n) <= 5; $n = $succ($n)) {
  echo ('factorial('.to_int($n).') = '.to_int($factorial($n))."\n");
}
