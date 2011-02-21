<?php

/*
 * This file is part of the teleserv package.
 * (c) Bryan O. Zarzuela <bryan@teleserv.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfNginxPushPluginConfiguration
 *
 * @package teleserv
 * @author Bryan Zarzuela
 */
class sfNginxPushPluginConfiguration extends sfPluginConfiguration
{
  /**
   * Sets the configuration of the singleton from the settings.yml file.
   *
   * @return void
   * @author Bryan Zarzuela
   */
  public function initialize()
  {
    if (sfConfig::has('app_nginxpush_endpoint')) {
      $push = sfNginxPush::getInstance();
      $push->setConfig('endpoint', sfConfig::get('app_nginxpush_endpoint'));
    }
  }
}
