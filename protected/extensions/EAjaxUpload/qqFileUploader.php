<?php

/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {

    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);

        if ($realSize != $this->getSize()) {
            return false;
        }

        $target = fopen($path, "w");
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);

        return true;
    }

    public function getContent() {
        $fileName = $_FILES['userfile']['name'];
        $tmpName = $_FILES['userfile']['tmp_name'];
        $fileSize = $_FILES['userfile']['size'];
        $fileType = $_FILES['userfile']['type'];

        $fp = fopen($tmpName, 'r');
        $content = fread($fp, filesize($tmpName));
        $content = addslashes($content);
        fclose($fp);
    }

    function getName() {
        return $_GET['qqfile'];
    }

    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])) {
            return (int) $_SERVER["CONTENT_LENGTH"];
        } else {
            throw new Exception('Getting content length is not supported.');
        }
    }

}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {

    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        if (!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)) {
            return false;
        }
        return true;
    }

    function getContent() {

        return $_FILES['qqfile']['tmp_name'];
    }

    function getName() {
        return $_FILES['qqfile']['name'];
    }

    function getSize() {
        return $_FILES['qqfile']['size'];
    }

}

class qqFileUploader {

    private $allowedExtensions = array();
    private $sizeLimit = 10485760;
    private $file;

    function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760) {
        $allowedExtensions = array_map("strtolower", $allowedExtensions);

        $this->allowedExtensions = $allowedExtensions;
        $this->sizeLimit = $sizeLimit;

        $this->checkServerSettings();

        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } else {
            $this->file = false;
        }
    }

    function getName() {
        return $_GET['qqfile'];
    }

    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])) {
            return (int) $_SERVER["CONTENT_LENGTH"];
        } else {
            throw new Exception('Getting content length is not supported.');
        }
    }

    private function checkServerSettings() {
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));

        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit) {
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");
        }
    }

    private function toBytes($str) {
        $val = trim($str);
        $last = strtolower($str[strlen($str) - 1]);
        switch ($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val;
    }

    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $replaceOldFile = FALSE) {
        if (!is_writable($uploadDirectory)) {
            return array('error' => Yii::t('texts', 'ERROR_UPLOAD_DIRECTORY_NOT_WRITABLE'));
        }

        if (!$this->file) {
            return array('error' => Yii::t('texts', 'ERROR_UPLOAD_NO_FILES_UPLOADED'));
        }

        $size = $this->file->getSize();

        if ($size == 0) {
            return array('error' => Yii::t('texts', 'ERROR_UPLOAD_FILE_EMPTY'));
        }

        if ($size > $this->sizeLimit) {
            return array('error' => Yii::t('texts', 'ERROR_UPLOAD_FILE_TOO_LARGE'));
        }

        $pathinfo = pathinfo($this->file->getName());
        //$filename = $pathinfo['filename'];
        $filename = md5(uniqid());
        $ext = $pathinfo['extension'];

        if ($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)) {
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => Yii::t('texts', 'ERROR_UPLOAD_INVALID_EXTENSION') . $these . '.');
        }

        if (!$replaceOldFile) {
            /// don't overwrite previous files that were uploaded
            while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
                $filename .= rand(10, 99);
            }
        }

        if ($this->file->save($uploadDirectory . $filename . '.' . $ext)) {
            return array('success' => true, 'filename' => $filename . '.' . $ext, 'extension' => $ext);
        } else {
            return array('error' => Yii::t('texts', 'ERROR_UPLOAD_CAN_NOT_SAVE_OR_CANCELLED'));
        }
    }

    function handleUpload2() {

        return $this->file;
//
//        if (!$this->file) {
//            return array('error' => Yii::t('texts', 'ERROR_UPLOAD_NO_FILES_UPLOADED'));
//        }
//
//        $size = $this->file->getSize();
//
//        if ($size == 0) {
//            return array('error' => Yii::t('texts', 'ERROR_UPLOAD_FILE_EMPTY'));
//        }
//
//        if ($size > $this->sizeLimit) {
//            return array('error' => Yii::t('texts', 'ERROR_UPLOAD_FILE_TOO_LARGE'));
//        }
//
//        $pathinfo = pathinfo($this->file->getName());
//        $filename = $pathinfo['filename'];
//        //$filename = md5(uniqid());
//        $ext = $pathinfo['extension'];
//
//        if ($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)) {
//            $these = implode(', ', $this->allowedExtensions);
//            return array('error' => Yii::t('texts', 'ERROR_UPLOAD_INVALID_EXTENSION') . $these . '.');
//        }
//
//        if ($this->file->save($uploadDirectory . $filename . '.' . $ext)) {
//            return array('success' => true, 'file' => $this->file);
//        } else {
//            return array('error' => Yii::t('texts', 'ERROR_UPLOAD_CAN_NOT_SAVE_OR_CANCELLED'));
//        }
    }

    function myHandleUpload() {

        if (!$this->file) {
            return array('error' => Yii::t('texts', 'ERROR_UPLOAD_NO_FILES_UPLOADED'));
        }

        $size = $this->file->getSize();

        if ($size == 0) {
            return array('error' => Yii::t('texts', 'ERROR_UPLOAD_FILE_EMPTY'));
        }

        if ($size > $this->sizeLimit) {
            return array('error' => Yii::t('texts', 'ERROR_UPLOAD_FILE_TOO_LARGE'));
        }

        $pathinfo = pathinfo($this->file->getName());
        $filename = $pathinfo['filename'];
        //$filename = md5(uniqid());
        $ext = $pathinfo['extension'];

        if ($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)) {
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => Yii::t('texts', 'ERROR_UPLOAD_INVALID_EXTENSION') . $these . '.');
        }

        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);
        return $temp;
    }

}