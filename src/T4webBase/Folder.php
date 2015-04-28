<?php

namespace T4webBase;

class Folder
{

    /**
     * @var
     */
    private $basePath;

    /**
     * @return mixed
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * @param mixed $basePath
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    public function __construct()
    {
        $this->setBasePath(getcwd());
    }


    /**
     * make folder path like /00/08/25/22/91
     *
     * @param int $objectID
     * @param int $groupBy
     * @param int $step
     * @return string
     */
    public function makePath($objectId, $groupBy = 10, $step = 2)
    {
        if (empty($objectId) || !is_numeric($objectId) || (strlen($objectId) > $groupBy)) {
            throw new \Exception('bad param $objectID:' . print_r($objectId, true) . ' (' . __CLASS__ . ' / ' . __METHOD__ . ')');
        }

        //Дорисуем необходимое кол-во нолей, получим: "0008252291"
        $stringId = str_repeat('0', ($groupBy - strlen($objectId))) . $objectId;

        //Формируем строку вида "/00/08/25/22/91"
        $path = '';
        for ($i = 0; $i < $groupBy; $i += $step) {
            $path .= '/' . substr($stringId, $i, $step);
        }

        //Выход
        return $path;
    }

    /**
     * create folder with empty index.html and 0775 rights
     *
     * @param string $path
     * @param string $base path WWW_PATH/
     */
    public function prepare($path)
    {
        if (file_exists($this->getBasePath() . '/' . $path) || empty($path)) return true;

        $dirs = explode('/', $path);
        $path = '/';

        foreach ($dirs as $dir) {
            $path .= $dir . '/';

            if (file_exists($this->getBasePath() . $path)) continue;
            mkdir($this->getBasePath() . $path, 0775);
            chmod($this->getBasePath() . $path, 0775);

            @file_put_contents($this->getBasePath() . $path . 'index.html', ' ');
        }
    }

    /**
     *
     * @param string $path
     * @return bool
     */
    public function clear($path)
    {

        if (empty($path) || mb_strlen($this->getBasePath(), 'utf-8') < 5) {
            throw new \Exception('A fatal error occurred while cleaning folder');
        }

        if (!file_exists($this->getBasePath() . '/' . $path) || !is_dir($this->getBasePath() . '/' . $path)) return false;

        $dirHandle = opendir($this->getBasePath() . '/' . $path);
        while (false !== ($file = readdir($dirHandle))) {
            if ($file != '.' && $file != '..' && $file != 'index.html') {// исключаем папки с назварием '.' и '..'
                $tmpPath = $this->getBasePath() . '/' . $path . '/' . $file;
                @chmod($tmpPath, 0777);
                // удаляем файл
                @unlink($tmpPath);
            }
        }
        closedir($dirHandle);
        return true;
    }

    /**
     *
     * @param string $path
     * @return bool
     */
    public function remove($path)
    {
        if (empty($path) || mb_strlen($this->getBasePath(), 'utf-8') < 5) {
            throw new \Exception('A fatal error occurred while deleting a folder "' . $path . '"');
        }

        if (!file_exists($this->getBasePath() . '/' . $path)) {
            return true;
        }

        if (!is_dir($this->getBasePath() . '/' . $path)) {
            @unlink($this->getBasePath() . '/' . $path);
            return true;
        }

        $dirHandle = opendir($this->getBasePath() . '/' . $path);

        if (!$dirHandle) {
            throw new \Exception('A fatal error occurred while deleting a folder "' . $path . '"');
        }

        while (false !== ($file = readdir($dirHandle))) {
            if ($file != '.' && $file != '..') {// исключаем папки с назварием '.' и '..'
                $tmpPath = $path . '/' . $file;
                @chmod($this->getBasePath() . '/' . $tmpPath, 0777);

                if (is_dir($this->getBasePath() . '/' . $tmpPath)) {  // если папка
                    self::removeFolder($tmpPath);
                } else {
                    @unlink($this->getBasePath() . '/' . $tmpPath);
                }
            }
        }
        closedir($dirHandle);

        if (file_exists($this->getBasePath() . '/' . $path)) {
            @chmod($this->getBasePath() . '/' . $path, 0777);
            @rmdir($this->getBasePath() . '/' . $path);
        }
        return true;
    }

    /**
     * remove folder/
     *
     * @param string $path
     * @return bool
     */
    public static function removeFolder($path)
    {
        if (file_exists($path)) {
            @chmod($path, 0777);
            @rmdir($path);
        }
        return true;
    }

}