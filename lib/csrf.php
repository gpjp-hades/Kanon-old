<?php

namespace lib;

class csrf {

  //always call all checks before getValues, also call wipe before displaying
  function __construct() {
    if (!isset($_SESSION['csrf']))
      $_SESSION['csrf'] = [];
  }

  static function getValues() {
    $vals = [bin2hex(\openssl_random_pseudo_bytes(5)), bin2hex(\openssl_random_pseudo_bytes(15))];
    array_push($_SESSION['csrf'], $vals);
    return $vals;
  }

  static function wipe() {
    $_SESSION['csrf'] = [];
  }

  static function check() {
    if (!isset($_POST['csrf'])) {
      \lib\csrf::wipe();
      return false;
    }
    foreach ($_SESSION['csrf'] as $test) {
      if (isset($_POST[$test[0]]) && $_POST[$test[0]] == $test[1])
        return true;
    }
    \lib\csrf::wipe();
    return false;
  }

  static function printHTML() {
    $vals = \lib\csrf::getValues();
    return "<input type='hidden' name='csrf' value='csrf' />
    <input type='hidden' name='$vals[0]' value='$vals[1]' />
";
  }
}