<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 04/07/16
 * Time: 3:21 PM
 */

namespace NS\PurearthBundle\Service;


use Doctrine\Common\Cache\PhpFileCache;

class FileCacheStorage implements CartStorageInterface
{
    /**
     * @var int
     */
    private $lifetime;

    /**
     * @var PhpFileCache
     */
    private $fileCache;

    /**
     * FileCacheStorage constructor.
     * @param PhpFileCache $fileCache
     */
    public function __construct(PhpFileCache $fileCache)
    {
        $this->fileCache = $fileCache;
    }

    /**
     * @param string $namespace
     * @param int $lifetime
     */
    public function initialize($namespace, $lifetime)
    {
//        $this->fileCache->setNamespace($namespace);
        $this->lifetime = $lifetime;
    }

    /**
     * @param $id
     * @return false|mixed
     */
    public function get($id)
    {
        $data = $this->fileCache->fetch($id);
        if($data) {
            return unserialize($data);
        }

        return false;

    }

    /**
     * @inheritDoc
     */
    public function save($id, $data, $lifeTime = 0)
    {
        if($lifeTime == 0) {
            $lifeTime = $this->lifetime;
        }

        return $this->fileCache->save($id, serialize($data), $lifeTime);
    }

    public function delete($id)
    {
        return $this->fileCache->delete($id);
    }
}