<?php

/**
 * This is the model class for table "tbl_carer_document".
 *
 * The followings are the available columns in table 'tbl_carer_document':
 * @property integer $id
 * @property integer $id_document
 * @property integer $id_carer
 * @property integer $year_obtained 
 * @property integer $status
 * @property integer $active
 * @property integer $id_content 
 * @property string $text
 * @property string $reject_reason
 *
 * The followings are the available model relations:
 * @property Document $idDocument
 * @property Carer $idCarer
 */
class CarerDocument extends ActiveRecord {

    const STATUS_NONE = 0;
    const STATUS_UNAPPROVED = 1;
    const STATUS_APPROVED = 2;
    const STATUS_REJECTED = 3;

    //Active
    const UNACTIVE = 0;
    const ACTIVE = 1;

    //Special case for texts
    const TEXT_PERSONALITY = 'text_personality';
    const TEXT_MOTIVATION = 'text_motivation';

    public static $statusLabels = array(
        self::STATUS_NONE => 'None',
        self::STATUS_UNAPPROVED => 'Waiting for approval',
        self::STATUS_APPROVED => 'Verified',
        self::STATUS_REJECTED => 'Rejected',
    );
    public $uploadedFile;

    /**
     * Returns the static model of the specified AR class.
     * @return CarerDocument the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_carer_document';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
        return array(
            array('id_document, id_carer', 'required'),
            array('id_document, id_carer, year_obtained, status', 'numerical', 'integerOnly' => true),
            array('id_document', 'compare', 'operator' => '>', 'compareValue' => 0, 'message' => Yii::t('texts', 'ERROR_SELECT_A_DOCUMENT')),
            array('year_obtained', 'checkYearObtained', 'on' => 'insertDiplomaCRB'),
            array('text', 'filter', 'filter' => array($this, 'filterText')),
            // Please remove those attributes that should not be searched.
            array('id, id_document, id_carer, year_obtained, status, id_content', 'safe', 'on' => 'search'),
        );
    }

    //Add upper case and points for sentencs
    public function filterText($value) {

        $trimmedText = trim($value);

        $value = strip_tags($trimmedText);

        if ($value != '') {

            $result = preg_replace('/([.!?])\s*(\w)/e', "strtoupper('\\1 \\2')", ucfirst($value));

            $lastCharacter = substr($result, -1);

            if ($lastCharacter != '.' && $lastCharacter != '!' && $lastCharacter != '?') {
                $result .= '.';
            }

            return $result;
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
// NOTE: you may need to adjust the relation name and the related
// class name for the relations automatically generated below.
        return array(
            'documentType' => array(self::BELONGS_TO, 'Document', 'id_document'),
            'carer' => array(self::BELONGS_TO, 'Carer', 'id_carer'),
                //'file' => array(self::BELONGS_TO, 'FileContent', 'id_content'),
        );
    }

    public function getCropFile() {

        if (isset($this->id_content_crop)) {

            try {
                $file = FileContentPhoto::loadModel($this->id_content_crop);
                return $file;
            } catch (CException $e) {
                throw new CException('No Crop');
            }
        }
    }

    public function getFile() {

        $docType = $this->documentType;

        try {

            switch ($docType->type) {

                case Document::TYPE_CRIMINAL:

                    return FileContentCriminal::loadModel($this->id_content);
                    break;

                case Document::TYPE_DIPLOMA:

                    return FileContentDiploma::loadModel($this->id_content);
                    break;

                case Document::TYPE_DRIVING_LICENCE:

                    return FileContentDrivingLicence::loadModel($this->id_content);
                    break;

                case Document::TYPE_IDENTIFICATION:

                    return FileContentIdentification::loadModel($this->id_content);
                    break;

                case Document::TYPE_PHOTO:

                    return FileContentPhoto::loadModel($this->id_content);
                    break;
            }
        } catch (CException $e) {
            throw new CException('No File');
        }
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'id_document' => 'Id Document',
            'id_carer' => 'Id Carer',
            'year_obtained' => 'Year Obtained',
            'status' => 'Status',
            'id_content' => 'Id Content',
        );
    }

    //restrict access to owner
//    public function defaultScope() {
//
//        if (Yii::app()->user->roles == 'admin') {
//            return array();
//        } else {
//            return array(
//                'condition' => 'id_carer = ' . Yii::app()->user->id,
//            );
//        }
//    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
// Warning: Please modify the following code to remove attributes that
// should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('id_document', $this->id_document);
        $criteria->compare('id_carer', $this->id_carer);
        $criteria->compare('year_obtained', $this->year_obtained);
        $criteria->compare('status', $this->status);
        $criteria->compare('id_content', $this->id_content, true);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    public function beforeSave() {

//        $type = Document::getType($this->id_document);
//        if ($type == Document::TYPE_PHOTO || $type == Document::TYPE_IDENTIFICATION
//                || $type == Document::TYPE_DRIVING_LICENCE) {
//
//            //if photo or ID check if record exists if it does delete
//            $record = self::model()->find('id_carer=:id_carer AND id_document=:id_document', array(':id_carer' => $this->id_carer, ':id_document' => $this->id_document));
//
//            if (isset($record)) {
//
//                $id = $record->id;
//
//                self::deleteDocument($id);
//            }
//        }

        return parent::beforeSave();
    }

//    public static function deleteDiplomas($id_carer) {
//
//        self::deleteDocuments($id_carer, Document::TYPE_DIPLOMA);
//    }
//    public static function deleteDocuments($id_carer, $type, $active) {
//
//        $sql = "SELECT * FROM tbl_document JOIN tbl_carer_document ON tbl_document.id = tbl_carer_document.id_document" .
//                " WHERE tbl_document.type = " . $type . " AND tbl_carer_document.id_carer = " . $id_carer . " AND tbl_carer_document.active = " . $active;
//
//        $rows = Yii::app()->db->createCommand($sql)->queryAll();
//
//        foreach ($rows as $row) {
//
//            self::model()->deleteByPk($row['id']);
//            //self::deleteDocument($row['id']);
//        }
//    }

    public function delete() {

//        try {
//            $file = $this->getFile();
//
//            if (isset($file)) {
//                $file->delete();
//            }
//        } catch (CException $e) {
//            
//        }
//
//        try {
//            $cropFile = $this->getCropFile();
//
//            if (isset($cropFile)) {
//                $cropFile->delete();
//            }
//        } catch (CException $e) {
//            
//        }

        parent::delete();
    }

//    //delete documents both active and unactive
//    public static function deleteDocumentBothVersions($id) {
//
//        //delete both active and unactive documents
//
//        $document = self::model()->findByPk((int) $id);
//
//        if (isset($document)) {
//
//            $sql = "SELECT id, id_content FROM `tbl_carer_document` WHERE `id_content` = " . $document->id_content;
//
//            $carerDocumentsRows = Yii::app()->db->createCommand($sql)->queryAll();
//
//            foreach ($carerDocumentsRows as $row) {
//
//                self::model()->deleteByPk($row['id']);
//            }
//
//            $file = $document->getFile();
//
//            if (isset($file)) {
//                $file->delete();
//            }
//        }
//    }

    public function checkYearObtained($attribute, $params) {

        if ($this->year_obtained == 0) {

            $this->addError($attribute, Yii::t('texts', 'ERROR_SELECT_A_YEAR'));
        }
    }

    public function displayDocumentStatusWithStyle() {
        return self::displayStatusWithStyle($this->status);
    }

    public static function displayStatusWithStyle($status) {

        switch ($status) {

            case self::STATUS_NONE:

                $class = 'rc-waitingforappoval';
                break;

            case self::STATUS_UNAPPROVED:

                $class = 'rc-waitingforappoval';
                break;

            case self::STATUS_APPROVED:

                $class = 'rc-verified';
                break;

            case self::STATUS_REJECTED:

                $class = 'rc-rejected';
                break;
        }

        return "<span class='$class'>" . self::$statusLabels[$status] . "</span>";
    }

    public function getType() {

        $doc = $this->documentType;

        return $doc->type;
    }

    public function getImageHeight() {

        $file = $this->getFile();
        if (isset($file)) {
            return $file->height;
        }
    }

    public function getImageWidth() {

        $file = $this->getFile();
        if (isset($file)) {
            return $file->width;
        }
    }

    public function getName() {

        return $this->documentType->getNameLabel();
    }

    public function getActiveVersion() {

        if ($this->active == 1) {
            return $this;
        } else {
            $sql = "SELECT tbl_carer_document.id FROM tbl_carer_document LEFT JOIN tbl_document ON tbl_carer_document.id_document = tbl_document.id" .
                    " WHERE tbl_document.id = " . $this->id_document . " AND tbl_carer_document.id_carer = {$this->id_carer} AND tbl_carer_document.active = 1";

            $row = Yii::app()->db->createCommand($sql)->queryRow();

            if ($row === false) {
                return null;
            } else {
                $model = CarerDocument::model()->findByPk($row['id']);

                return $model;
            }
        }
    }

    public function getInactiveVersion() {

        if ($this->active == 0) {
            return $this;
        } else {
            $sql = "SELECT tbl_carer_document.id FROM tbl_carer_document LEFT JOIN tbl_document ON tbl_carer_document.id_document = tbl_document.id" .
                    " WHERE tbl_document.id = " . $this->id_document . " AND tbl_carer_document.id_carer = {$this->id_carer} AND tbl_carer_document.active = 0";

            $row = Yii::app()->db->createCommand($sql)->queryRow();

            if ($row === false) {
                return null;
            } else {
                $model = CarerDocument::model()->findByPk($row['id']);

                return $model;
            }
        }
    }

    public static function getDiplomas($carerId, $active) {

        //get all diplomas for current carer 
        $sql = "SELECT tbl_carer_document.id FROM tbl_carer_document LEFT JOIN tbl_document ON tbl_carer_document.id_document = tbl_document.id" .
                " WHERE tbl_document.type = " . Document::TYPE_DIPLOMA . " AND tbl_carer_document.id_carer = $carerId AND tbl_carer_document.active = $active ";

        $rows = Yii::app()->db->createCommand($sql)->queryAll();

        $diplomas = array();

        foreach ($rows as $row) {

            $diplomas[] = CarerDocument::loadModel($row['id']);
        }

        return $diplomas;
    }

    public static function getPhoto($carerId, $active, $forClient = false) {

        $sql = "SELECT tbl_carer_document.id FROM tbl_carer_document LEFT JOIN tbl_document ON tbl_carer_document.id_document = tbl_document.id" .
                " WHERE tbl_document.type = " . Document::TYPE_PHOTO . " AND tbl_carer_document.id_carer = $carerId AND tbl_carer_document.active = $active ";

        //cache for 5 seconds, if same carer to display there's no point retrieving it all the time
        //$row = Yii::app()->db->cache(5)->createCommand($sql)->queryRow();

        $row = Yii::app()->db->createCommand($sql)->queryRow();

        if ($row != false) {
            if ($forClient) {
                return CarerDocument::loadModelAdmin($row['id']);
            } else {
                return CarerDocument::loadModel($row['id']);
            }
        }
    }

    public static function getIdentification($carerId, $active) {

        $sql = "SELECT tbl_carer_document.id FROM tbl_carer_document LEFT JOIN tbl_document ON tbl_carer_document.id_document = tbl_document.id" .
                " WHERE tbl_document.type = " . Document::TYPE_IDENTIFICATION . " AND tbl_carer_document.id_carer = $carerId AND tbl_carer_document.active = $active ";

        $row = Yii::app()->db->createCommand($sql)->queryRow();

        if ($row != false) {
            return CarerDocument::loadModel($row['id']);
        }
    }

    /**
     *  Create new text 
     * @param type CarerDocument::TEXT_PERSONALITY or CarerDocument::TEXT_MOTIVATION
     * @param type $carerId
     * @param type $text
     * @return type
     */
    public static function createNewTextDocument($type, $carerId, $text) {

        $textDocument = new CarerDocument();
        $textDocument->id_document = Document::getId($type);
        $textDocument->id_carer = $carerId;

        $textDocument->status = self::STATUS_UNAPPROVED;
        $textDocument->text = $text;

        $textDocument->validate();
        $errors = $textDocument->errors;
        $textDocument->save();

        return $textDocument;
    }

    public static function getPersonalityText($carerId, $active) {

        $sql = "SELECT tbl_carer_document.id FROM tbl_carer_document LEFT JOIN tbl_document ON tbl_carer_document.id_document = tbl_document.id" .
                " WHERE tbl_document.name = '" . self::TEXT_PERSONALITY . "' AND tbl_carer_document.id_carer = $carerId AND tbl_carer_document.active = $active ";

        $row = Yii::app()->db->createCommand($sql)->queryRow();

        if ($row) {
            return CarerDocument::loadModel($row['id']);
        } else {
            return null;
        }
    }

    public static function getMotivationText($carerId, $active) {

        $sql = "SELECT tbl_carer_document.id FROM tbl_carer_document LEFT JOIN tbl_document ON tbl_carer_document.id_document = tbl_document.id" .
                " WHERE tbl_document.name = '" . self::TEXT_MOTIVATION . "' AND tbl_carer_document.id_carer = $carerId AND tbl_carer_document.active = $active ";

        $row = Yii::app()->db->createCommand($sql)->queryRow();

        if ($row) {
            return CarerDocument::loadModel($row['id']);
        } else {
            return null;
        }
    }

    public static function getDrivingLicence($carerId, $active) {

        //get all diplomas for current carer 
        $sql = "SELECT tbl_carer_document.id FROM tbl_carer_document LEFT JOIN tbl_document ON tbl_carer_document.id_document = tbl_document.id" .
                " WHERE tbl_document.type = " . Document::TYPE_DRIVING_LICENCE . " AND tbl_carer_document.id_carer = $carerId AND tbl_carer_document.active = $active ";

        $row = Yii::app()->db->createCommand($sql)->queryRow();

        if ($row != false) {
            return CarerDocument::loadModel($row['id']);
        }
    }

    /*
     * return documents used to check carer first name and last name
     */

    public static function getCarerNameVerifiedDocument($carerId) {

        $sql = "SELECT tbl_carer_document.id FROM tbl_carer_document LEFT JOIN tbl_document ON tbl_carer_document.id_document = tbl_document.id" .
                " WHERE tbl_document.type IN ( " . Document::TYPE_IDENTIFICATION . "," . Document::TYPE_DRIVING_LICENCE . "," . Document::TYPE_DIPLOMA . " ) " .
                " AND tbl_carer_document.id_carer = $carerId AND tbl_carer_document.active =  " . CarerDocument::ACTIVE;

        return self::model()->findBySql($sql);
    }

    public static function getCriminalRecords($carerId, $active) {

        //get all diplomas for current carer 
        $sql = "SELECT tbl_carer_document.id FROM tbl_carer_document LEFT JOIN tbl_document ON tbl_carer_document.id_document = tbl_document.id" .
                " WHERE tbl_document.type = " . Document::TYPE_CRIMINAL . " AND tbl_carer_document.id_carer = $carerId AND tbl_carer_document.active = $active ";

        $rows = Yii::app()->db->createCommand($sql)->queryAll();

        $criminal = array();

        foreach ($rows as $row) {

            $criminal[] = CarerDocument::loadModel($row['id']);
        }

        return $criminal;
    }

    /**
     * To be used for Photo, driving licence and ID
     */
    public static function getUniqueDocument($carerId, $type, $active) {

        $sql = "SELECT tbl_carer_document.id FROM tbl_carer_document LEFT JOIN tbl_document ON tbl_carer_document.id_document = tbl_document.id" .
                " WHERE tbl_document.type = $type AND tbl_carer_document.id_carer = $carerId AND tbl_carer_document.active = $active ";

        $row = Yii::app()->db->createCommand($sql)->queryRow();

        if ($row != false) {
            return CarerDocument::loadModel($row['id']);
        }
    }

    public static function getExistingApprovedDocument($carerId) {

        $sql = "SELECT id, id_document FROM tbl_carer_document" .
                " WHERE tbl_carer_document.id_carer = $carerId
                  AND   tbl_carer_document.active = 1 
                  GROUP BY id_document ";

        $rows = Yii::app()->db->createCommand($sql)->queryAll();

        $ids = array();

        foreach ($rows as $row) {

            $ids[] = $row['id'];
        }

        return self::model()->findAllByPk($ids);
    }

    public static function getExistingDocument($carerId) {

        $sql = "SELECT id, id_document FROM tbl_carer_document" .
                " WHERE tbl_carer_document.id_carer = $carerId GROUP BY id_document ";

        $rows = Yii::app()->db->createCommand($sql)->queryAll();

        $ids = array();

        foreach ($rows as $row) {

            $ids[] = $row['id'];
        }

        return self::model()->findAllByPk($ids);
    }

    public function getTypeLabel() {

        $doc = $this->documentType;

        return $doc->getTypeLabel();
    }

    public function getStatusLabel() {

        return self::$statusLabels[$this->status];
    }

    public function getActiveLabel() {
        if ($this->active == 0) {
            return 'Unactive';
        } else {
            return 'Active';
        }
    }

    public function getRequirements() {

        $sql = "SELECT requirement FROM tbl_document_requirement WHERE id_document_type = " . $this->documentType->type;
        $rows = Yii::app()->db->createCommand($sql)->queryAll();

        $result = array();

        foreach ($rows as $row) {

            $requirement = $row['requirement'];
            $text = Yii::t('documentRequirements', $requirement);
            $result[$text] = $text;
        }

        return $result;
    }

    public function showImageForAdmin($document, $width = 300, $crop = false) {

        $url = Yii::app()->createAbsoluteUrl('admin/carerAdmin/getImageAdmin', array('documentId' => $document->id, 'crop' => $crop));
        echo '<img src="' . $url . '" alt="Photo" width="' . $width . '" />';
    }

    public function showImageForGuest($cssClass, $style = "", $consoleCommand = null) {

        if ($this->type == Document::TYPE_PHOTO) {
            $file = $this->getCropFile();
            $crop = true;
        }

        if (isset($file)) {

            if (isset($consoleCommand)) {
                $documentId = $this->id;
                $url = $consoleCommand->createAbsoluteUrl("site/getImageForGuest/documentId/$documentId/crop/$crop");

                $output = "<img class='$cssClass' style='$style' src='$url' alt='Photo' />";

                //$output = $consoleCommand->renderFile(Yii::app()->basePath . '/views/clientManageBookings/_showImageForClient.php', array('documentId' => $this->id, 'crop' => $crop, 'cssClass' => $cssClass, 'style' => $style)
                //        , true, false);
            } else {
                $output = Yii::app()->controller->renderPartial('application.views.clientManageBookings._showImageForClient', array('documentId' => $this->id, 'crop' => $crop, 'cssClass' => $cssClass, 'style' => $style)
                        , true, false);
            }
            return $output;
        } else {
            return Yii::t('texts', 'ERROR_NO_IMAGE');
        }
    }

    public function showImageForClient($cssClass, $style = "") {

        if ($this->type == Document::TYPE_PHOTO) {
            $file = $this->getCropFile();
            $crop = true;
        } else {
            $file = $this->getFile();
            $crop = false;
        }

        if (isset($file)) {

            $output = Yii::app()->controller->renderPartial('application.views.clientManageBookings._showImageForClient', array('documentId' => $this->id, 'crop' => $crop, 'cssClass' => $cssClass, 'style' => $style)
                    , true, false);

            return $output;
        } else {
            return Yii::t('texts', 'ERROR_NO_IMAGE');
        }
    }

    public function showCrop($height = "148", $width = "288") {

        $fileContent = $this->getCropFile();

        return $this->showImg($fileContent, $height, $width, true);
    }

    public function showImage($height = "148", $width = "288", $visible = true, $class = 'rc-image-profile') {

        $fileContent = $this->getFile();

        return $this->showImg($fileContent, $height, $width, $visible, $class);
    }

    private function showImg($fileContent, $height = "148", $width = "288", $visible = true, $class = 'rc-image-profile') {

        if ($width > 800) {
            $width = 800;
        }

        if (isset($fileContent)) {

            $output = Yii::app()->controller->renderPartial('application.views.carer._showImage', array('class' => $class, 'fileContent' => $fileContent, 'doc' => $this, 'width' => $width, 'visible' => $visible), true, false);

            return $output;
        } else {
            return 'No image';
        }
    }

    public static function approve($documentId) {

        $unactiveModel = self::model()->findByPk((int) $documentId);

        if ($unactiveModel->active == self::UNACTIVE) {

            $activeModel = self::getModelObject($unactiveModel->id_document, $unactiveModel->id_carer, self::ACTIVE);

            if (!isset($activeModel)) {

                $activeModel = new CarerDocument();
                $activeModel->id_document = $unactiveModel->id_document;
                $activeModel->id_carer = $unactiveModel->id_carer;
                $activeModel->active = self::ACTIVE;
                $activeModel->status = self::STATUS_APPROVED;
            }

            $activeModel->id_content = $unactiveModel->id_content;
            $activeModel->text = $unactiveModel->text;
            $activeModel->id_content_crop = $unactiveModel->id_content_crop;

            $activeModel->year_obtained = $unactiveModel->year_obtained;

            $activeModel->save();

            //upadte unacive model
            $unactiveModel->status = self::STATUS_APPROVED;
            $unactiveModel->reject_reason = null;
            $unactiveModel->save();
        }
    }

    public static function reject($documentId, $reject_reason) {

        $document = self::model()->findByPk((int) $documentId);
        $document->status = self::STATUS_REJECTED;
        $document->reject_reason = $reject_reason;
        $document->save();
    }

    public static function waiting($documentId) {

        $document = self::model()->findByPk((int) $documentId);
        $document->reject_reason = null;
        $document->status = self::STATUS_UNAPPROVED;
        $document->save();
    }

    public static function getModelObject($documentId, $carerId, $active) {

        $sql = "SELECT * FROM tbl_carer_document WHERE id_document = $documentId AND id_carer = $carerId AND active = $active";

        $result = self::model()->findBySql($sql);

        return $result;
    }

    /**
     * Publish temp photo for croping, return array of url and file name
     * @param type $id
     */
    public static function publishTempImage($id) {

        $carerDocument = CarerDocument::loadModel($id);

        $fileContent = $carerDocument->getFile();

        $content = $fileContent->content;
        $extension = $fileContent->extension;

        //$file = base64_decode($content);  

        $rootFolder = Yii::getPathOfAlias('application');

        $fileName = Random::getRandomLetters(10) . '.' . $extension;

        $fullFileName = $rootFolder . '/../assets/' . $fileName;

        file_put_contents($fullFileName, $content);

        $url = Yii::app()->request->hostInfo . Yii::app()->baseUrl . '/assets/' . $fileName;

        return array('url' => $url, 'fileName' => $fileName, 'width' => $fileContent->width, 'height' => $fileContent->height);
    }

}