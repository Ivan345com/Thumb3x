<?php
namespace Thumb3x\Model;

use xPDO\xPDO;

/**
 * Class Thumb3xFile
 *
 * @property string $hash
 * @property string $source_path
 * @property string $cache_path
 * @property array $params
 * @property integer $cache_filesize
 * @property integer $cache_width
 * @property integer $cache_height
 * @property string $error_message
 * @property string $createdon
 * @property string $accessedon
 *
 * @package Thumb3x\Model
 */
class Thumb3xFile extends \xPDO\Om\xPDOSimpleObject
{
}
