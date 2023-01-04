<?php

/***********************************************************************************************
 *   apiCRM.php --
 *   Version: 1.0.0
 * Copyright 2022 MarketPlace by Waldir Borba Junior
 ************************************************************************************************/

/*
 * @function thumb
 * @parameter $_POST, $_REMOTE
 *
 */
function thumb($srcFile, $sideInPx)
{

  $image = imagecreatefromjpeg($srcFile);
  $width = imagesx($image);
  $height = imagesy($image);

  $thumb = imagecreatetruecolor($sideInPx, $sideInPx);

  imagecopyresized($thumb, $image, 0, 0, 0, 0, $sideInPx, $sideInPx, $width, $height);

  imagejpeg($thumb, str_replace(".jpg", "-thumb.jpg", $srcFile), 85);

  imagedestroy($thumb);
  imagedestroy($image);
}

/*
 * @function removeAcento
 * @parameter $param
 *
 */
function removeAcento($param)
{
  $acentos = array(
    'á', 'ã', 'à', 'â', 'ä', '', 'a', 'a',
    'é', 'è',
    'í', 'ì',
    'ó', 'õ', 'ö', 'ò', 'ô', 'o',
    'ú', 'ù',
    'ç', 'c',
    'ë', 'ê',
    'ü', 'û',
  );
  $remove_acentos = array(
    'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
    'e', 'e',
    'i', 'i',
    'o', 'o', 'o', 'o', 'o', 'o',
    'u', 'u',
    'c', 'c',
    'e', 'e',
    'u', 'u',
  );
  return str_replace($acentos, $remove_acentos, urldecode($param));
}

/*
 * @function humanToMysql
 * @parameter $param
 *
 */
function humanToMysql($param)
{
  if (strlen(trim($param)) > 10) {
    $ret = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $param)));
  } else {
    $ret = date('Y-m-d', strtotime(str_replace('/', '-', $param)));
  }

  return $ret;
}

/*
 * @function mysqlToHuman
 * @parameter $param
 *
 */
function mysqlToHuman($param, $withtime = true)
{
  if ($withtime) {
    $ret = date('d/m/Y H:i:s', strtotime(str_replace('-', '/', $param)));
  } else {
    $ret = date('d/m/Y', strtotime(str_replace('-', '/', $param)));
  }

  return $ret;
}

/*
 * @function toUTF8
 * @parameter $_POST, $_REMOTE
 *
 */
function trimupper($param)
{
  // return strlen($param) == 0 ? $param : strtoupper(addslashes(trim(removeAcento($param))));
  return strlen($param) == 0 ? $param : utf8_decode(addslashes(trim($param)));
}

/*
 * @function toUTF8
 * @parameter $_POST, $_REMOTE
 *
 */
function toUTF8($param)
{
  return strlen($param) == 0 ? $param : trim(utf8_encode($param));
}

/*
 * @function trimUTF8
 * @parameter $_POST, $_REMOTE
 *
 */
function trimUTF8($param)
{
  return strlen($param) == 0 ? $param : utf8_decode(trim($param));
}

/*
 * @function hasContent
 * @parameter $_POST, $_REMOTE
 *
 */
function isNullOrEmpty($param)
{
  return !isset($param) || trim($param) == '';
}

/*
 * @function formataCEP
 * @parameter $param
 *
 */
function formataCEP($param)
{
  return strlen($param);
}

/*
 * @function currentValue
 * @parameter $_POST, $_REMOTE
 *
 */
function currentValue($param)
{
  return (isNullOrEmpty($param) == 0 ? 'NULL' : $param);
}

/*
 * @function retornaMes
 * @parameter $param
 *
 */
function retornaMes($param)
{

  $meses = array(
    'JANEIRO' => '01/01',
    'FEVEREIRO' => '01/02',
    'MAR�O' => '01/03',
    'ABRIL' => '01/04',
    'MAIO' => '01/05',
    'JUNHO' => '01/06',
    'JULHO' => '01/07',
    'AGOSTO' => '01/08',
    'SETEMBRO' => '01/9',
    'OUTUBRO' => '01/10',
    'NOVEMBRO' => '01/11',
    'DEZEMBRO' => '01/12'
  );

  $mes = explode('/', $param);

  $v1 = $meses[strtoupper($mes[0])];
  $v2 = $mes[1];

  return "$v1/$v2";
}


/*
 * @function query
 * @parameter $_POST, $_REMOTE
 *
 */
function query($sql)
{

  // setup db connection
  $link = mysqli_connect("localhost", "workpl00_mkuser", "@senha", "workpl00_mkplace");

  if (mysqli_connect_errno()) {
    return array('error' => 'Erro de conexão com o banco de dados %s' . mysqli_connect_error());
    exit();
  }

  $result = mysqli_query($link, $sql);
  $newid  = mysqli_insert_id($link);

  if (mysqli_errno($link) == 0 && $result) {

    $rows = array();

    if ($result !== true)
      while ($content = mysqli_fetch_assoc($result)) {
        $content = array_map('utf8_encode', $content);
        // $content = htmlentities($content, "UTF-8");
        array_push($rows, $content);
      }

    if ($newid != 0) {
      $rows = array('newid' => $newid);
    }

    $ret = $rows;
  } else {
    //error
    $ret = array('error' => 'Erro executando Instrução SQL: ' . $result);
  }

  mysqli_close($link);

  return $ret;
}

function returnJson($code, $msg = '', $data = array())
{
  // https://www.moesif.com/blog/technical/api-design/Which-HTTP-Status-Code-To-Use-For-Every-CRUD-App/

  // case 100: $text = 'Continue'; break;
  // case 101: $text = 'Switching Protocols'; break;
  // case 200: $text = 'OK'; break;
  // case 201: $text = 'Created'; break;
  // case 202: $text = 'Accepted'; break;
  // case 203: $text = 'Non-Authoritative Information'; break;
  // case 204: $text = 'No Content'; break;
  // case 205: $text = 'Reset Content'; break;
  // case 206: $text = 'Partial Content'; break;
  // case 300: $text = 'Multiple Choices'; break;
  // case 301: $text = 'Moved Permanently'; break;
  // case 302: $text = 'Moved Temporarily'; break;
  // case 303: $text = 'See Other'; break;
  // case 304: $text = 'Not Modified'; break;
  // case 305: $text = 'Use Proxy'; break;
  // case 400: $text = 'Bad Request'; break;
  // case 401: $text = 'Unauthorized'; break;
  // case 402: $text = 'Payment Required'; break;
  // case 403: $text = 'Forbidden'; break;
  // case 404: $text = 'Not Found'; break;
  // case 405: $text = 'Method Not Allowed'; break;
  // case 406: $text = 'Not Acceptable'; break;
  // case 407: $text = 'Proxy Authentication Required'; break;
  // case 408: $text = 'Request Time-out'; break;
  // case 409: $text = 'Conflict'; break;
  // case 410: $text = 'Gone'; break;
  // case 411: $text = 'Length Required'; break;
  // case 412: $text = 'Precondition Failed'; break;
  // case 413: $text = 'Request Entity Too Large'; break;
  // case 414: $text = 'Request-URI Too Large'; break;
  // case 415: $text = 'Unsupported Media Type'; break;
  // case 500: $text = 'Internal Server Error'; break;
  // case 501: $text = 'Not Implemented'; break;
  // case 502: $text = 'Bad Gateway'; break;
  // case 503: $text = 'Service Unavailable'; break;
  // case 504: $text = 'Gateway Time-out'; break;
  // case 505: $text = 'HTTP Version not supported'; break;

  $msg = toUTF8($msg);

  http_response_code($code);

  print json_encode(array(array('message' => $msg, 'data' => $data)));
  exit();
}

