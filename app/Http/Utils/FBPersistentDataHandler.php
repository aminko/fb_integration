<?php

namespace App\Http\Utils;

use Facebook\PersistentData\PersistentDataInterface;

/**
 * 
 * source - https://developers.facebook.com/docs/php/PersistentDataInterface/5.0.0
 * 
 */

class FBPersistentDataHandler implements PersistentDataInterface
{
  /**
   * @var string Prefix to use for session variables.
   */
  protected $sessionPrefix = 'FBRLH_';

  /**
   * @inheritdoc
   */
  public function get($key)
  {
    return \Session::get($this->sessionPrefix . $key);
  }

  /**
   * @inheritdoc
   */
  public function set($key, $value)
  {
    \Session::put($this->sessionPrefix . $key, $value);
  }
}
