<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ImageController
 *
 * @author I031360
 */
class ImageController extends Controller {

    private function getImage($filePath) {

        if (file_exists($filePath)) {
            header('Content-Type: image/jpeg');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
        } else {
            return "";
        }
    }

    public function actionFavicon() {
        $filename = "favicon.ico";
        $path = Yii::getPathOfAlias('webroot.images') . '/';
        $filePath = $path . $filename;

        return $this->getImage($filePath);
    }

    public function missingAction($actionID) {
        echo 'You are trying to execute action: ' . $actionID;
    }

    public function actionAppleIcon57() {
        
    }

    public function actionAppleIcon114() {

        $filename = "apple-touch-icon-114×114.png";
        $path = Yii::getPathOfAlias('webroot.images.apple') . '/';
        $filePath = $path . $filename;

        return $this->getImage($filePath);

//    apple-touch-icon-57x57-precomposed.png
//    apple-touch-icon-57x57.png
//    apple-touch-icon-precomposed.png
//    apple-touch-icon.png
//    apple-touch-icon-120x120.png        
    }

}

?>
