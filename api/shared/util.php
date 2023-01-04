<?php

class Util {
    // Method of input value sanitization
    public function testInput($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      $data = strip_tags($data);

      return $data;
    }

    public function isNullOrEmpty($param) {
      return !isset($param) || trim($param) == '';
    }

  }