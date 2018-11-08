<style>
    .rc-waitingforappoval {
        line-height:1.67em;   
        cursor:default;
        color:#333333;
        background:#DFBE62;
        font-family:Arial;
        font-weight:700;
        font-size:0.9em;
        text-decoration:none;
        text-align:center;
        padding:0.25em 0.4694em 0.20em 0.4694em;
        margin:0;
        white-space:nowrap; 
    }
    .rc-verified {
        line-height:1.67em;                           
        cursor:default;
        color:#FCFCFC;
        background:#91C958;
        font-family:Arial;
        font-weight:700;
        font-size:0.9em;
        text-decoration:none;
        text-align:center;
        padding:0.25em 0.4694em 0.20em 0.4694em;
        margin:0;
        white-space:nowrap; 
    }
    .rc-rejected {
        line-height:1.67em;                           
        cursor:default;
        color:#8A1F11;
        background:#FBE3E4;
        font-family:Arial;
        font-weight:700;
        font-size:0.9em;
        text-decoration:none;
        text-align:center;
        padding:0.25em 0.4694em 0.20em 0.4694em;
        margin:0;
        white-space:nowrap; 
    }
</style>

<?php
echo '<b>First name: </b>' . $carer->first_name . '<br />';
echo '<b>Last name: </b>' . $carer->last_name . '<br />';
echo '<b>Date of birth: </b>' . Calendar::convert_DBDateTime_DisplayDate($carer->date_birth) . '<br />';
echo '<b>Gender: </b>' . $carer->getGenderLabel() . '<br />';
echo '<b>Nationality: </b>' . $carer->nationality . '<br />';
?>
<hr />
<table style="width:100%;">
    <tr>
        <td style="border:1px solid #000000;width:50%;text-align:center;font-size:20px;font-weight:700;">Carer view</td>
        <td style="border:1px solid #000000;width:50%;text-align:center;font-size:20px;font-weight:700;">Client view</td>
    </tr>
    <?php
    //$documents = $carer->resetScope()->carerDocuments;

    $documents = CarerDocument::getExistingDocument($carer->id);

    foreach ($documents as $document) {

        $unactive = null;
        $active = null;

        $debugType = $document->type;
        //$debugType = $document->name;
        //only show carer's document
        if ($document->active == CarerDocument::UNACTIVE) {

            $unactive = $this->renderPartial('/carer/_showDocumentAdmin', array('document' => $document, 'carer' => $carer), true, false);
            $activeDocument = $document->getActiveVersion();

            if (isset($activeDocument)) {

                $debugId = $activeDocument->id;

                $active = $this->renderPartial('/carer/_showDocumentAdmin', array('document' => $activeDocument, 'carer' => $carer), true, false);
            }
        } else {
            $active = $this->renderPartial('/carer/_showDocumentAdmin', array('document' => $document, 'carer' => $carer), true, false);
            $unactiveDocument = $document->getInactiveVersion();

            if (isset($unactiveDocument)) {

                $debugId = $unactiveDocument->id;

                $unactive = $this->renderPartial('/carer/_showDocumentAdmin', array('document' => $unactiveDocument, 'carer' => $carer), true, false);
            }
        }
        ?>
        <tr>
            <td style="border:1px solid #000000;vertical-align:top;">
                <?php
                if (isset($unactive)) {
                    echo $unactive;
                }
                ?>
            </td>
            <td style="border:1px solid #000000;vertical-align:top;">
                <?php
                if (isset($active)) {
                    echo $active;
                }
                ?>
            </td>
        </tr>
        <?php
    }
    ?>
</table>
<div id="hook" style="display:none">
    <div id="dialog"></div>
</div>