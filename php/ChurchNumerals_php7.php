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
$zero($bowwow)($dog);
echo ("\n");
$one($bowwow)($dog);
echo ("\n");
$two($bowwow)($dog);
echo ("\n");
$three($bowwow)($dog);
echo ("\n");
$succ = function ($n) {
  return function ($f) use ($n) {
    return function ($x) use ($f, $n, $f) {
      return $f($n($f)($x));
    };
  };
};
$succ($three)($bowwow)($dog);
echo ("\n");
$four = $succ($three);
$five = $succ($four);
$four($bowwow)($dog);
echo ("\n");
$five($bowwow)($dog);
echo ("\n");
$plus = function ($m) {
  return function ($n) use ($m) {
    return function ($f) use ($m, $n) {
      return function ($x) use ($m, $f, $n, $f) {
        return $m($f)($n($f)($x));
      };
    };
  };
};
$mult = function ($m) {
  return function ($n) use ($m) {
    return function ($f) use ($m, $n) {
      return function ($x) use ($m, $n, $f) {
        return $m($n($f))($x);
      };
    };
  };
};
function to_int($n) {
  return $n(
    function ($i) {
      return $i + 1;
    }
  )(0);
}
echo ('5 + 3 = '.to_int($plus($five)($three))."\n");
echo ('5 * 3 = '.to_int($mult($five)($three))."\n");
$pred = function ($n) {
  return function ($f) use ($n) {
    return function ($x) use ($n, $f) {
      return $n(
        function ($g) use ($f) {
          return function ($h) use ($g, $f) {
            return $h($g($f));
          };
        }
      )(
        function ($u) use ($x) {
          return $x;
        }
      )(
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
      return $n(
        function ($x) use ($f) {
          return $f;
        }
      )($t);
    };
  };
};
echo ('ifzero(0) = '.to_int($ifzero($zero)($one)($zero))."\n");
echo ('ifzero(3) = '.to_int($ifzero($three)($one)($zero))."\n");
$fix = function ($f) {
  return (function ($x) use ($f) {
            return $f(
              function ($y) use ($x) {
                return $x($x)($y);
              }
            );
          })(
    function ($x) use ($f) {
      return $f(
        function ($y) use ($x) {
          return $x($x)($y);
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
      return $ifzero($n)(
        function ($u) use ($zero) {
          return $zero;
        }
      )(
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
          return
            $ifzero($pred($n))(
              function ($u) use ($succ, $zero) {
                return $succ($zero);
              }
            )(
              function (
                $u
              ) use ($plus, $fib, $pred, $n, $fib, $pred, $n) {
                return $plus($fib($pred($n)))($fib($pred($pred($n))));
              }
            )(
              function ($u) {
                return $u;
              }
            );
        }
      )(
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
      return $ifzero($n)(
        function ($u) use ($succ, $zero) {
          return $succ($zero);
        }
      )(
        function ($u) use ($mult, $n, $fact, $pred, $n) {
          return $mult($n)($fact($pred($n)));
        }
      )(
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
