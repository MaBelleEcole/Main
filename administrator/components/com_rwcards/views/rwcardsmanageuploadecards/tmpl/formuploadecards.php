<?php
/**
 * RWCards Template for RWCards
 *
 * @author Ralf Weber  <ralf@weberr.de>
 * @version 3.0
 * @copyright Copyright (c) 2007, LoadBrain
 * @link http://www.weberr.de/
 *
 * @license GNU/GPL
 */
defined('_JEXEC') or die ('Restricted access');

$suffix = '@' . $this->params->get("thumbnail_suffix", 'rwcards' );

?>
<script src="http://www.google.com/jsapi"></script>
<script>google.load("jquery", "1");</script>
<script type="text/javascript" src="./components/com_rwcards/js/jquery.upload.js"></script>
<script type="text/javascript" src="./components/com_rwcards/js/jquery.com_rwcards.js"></script>
<style type="text/css">
    table.admintable td.key {
        background-color: #f6f6f6;
        text-align: left;
        width: 100%;
        color: #666;
        font-weight: bold;
        border-bottom: 1px solid #e9e9e9;
        border-right: 1px solid #e9e9e9;
    }
    
    /*file upload */
    div.button {
        height: 29px;
        width: 133px;
        background: #000000;
        font-weight: bold;
        font-size: 14px;
        color: #C7D92C;
        text-align: center;
        padding-top: 15px;
    }
    
    /*
     We can't use ":hover" preudo-class because we have
     invisible file input above, so we have to simulate
     hover effect with javascript.
     */
    div.button.hover {
        height: 39px;
        width: 133px;
        background: #000000;
        color: #EFFF5F;
        font-size: 22px;
        font-weight: bold;
        text-align: center;
    }
</style>
<form action="index.php" method="post" name="adminForm" id="adminForm">
    <div class="col100">
            <table class="admintable" border="0">
                <tr>
                    <td>
                        <div id="upload">
                            <div id="uploadLeft">
                                <h3>
                                    <?php
                                    echo JText::_('UPLOAD_WORKING_DIRECTORY');
                                    ?>
                                    /images/stories/cards</h3>
                                <div id="button1" class="button">
                                    Upload
                                </div>
                                <div id="uploadWrapper">
                                    <h3 id="uploadedHeader">
                                        <?php
                                        echo JText::_('UPLOADED_FILES');
                                        ?>
                                    </h3>
                                    <div class="files">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                <td>
                    <hr/>
                </td>
                <tr>
                    <td>
                        <h2>
                            <?php
                            echo JText::_('UPLOAD_AVAILABLE_PICTURES');
                            ?>
                        </h2>
                    </td>
                </tr>
                <tr>
                    <td class="key">
                    <?php
                    $i = 0;
                    $breite = 160;
                    $hoehe = 120;
                    echo "<table border='0' cellspacing='5' cellpadding='5' width='100%'><tr>";
					$adminimagesperrow = $this->params->get("adminimagesperrow",3);
					
                    foreach ($this->rwcardsImages as $val)
                    {
                        $size = getimagesize(JPATH_ROOT."/images/stories/cards/".$val);
                        // zugross & quer
                        if ($size[0] > $breite && $size[1] > $hoehe && $size[0] >= $size[1])
                        {
                            if ($size[0] == $size[1])
                            {
                                $sizemin[0] = $breite;
                                $sizemin[1] = $breite;
                            }
                            else
                            {
                                $sizemin[0] = $breite;
                                $sizemin[1] = $hoehe;
                            }
                        }
                        // zugross & hochkant
                        else if ($size[0] > $breite && $size[1] > $hoehe && $size[1] > $size[0])
                        {
                            $sizemin[0] = $hoehe;
                            $sizemin[1] = $breite;
                        }
                        // breite zu gross:
                        else if ($size[0] > $breite)
                        {
                            $sizemin[0] = $breite;
                            $sizemin[1] = $size[1];
                        }
                        // hoehe zu gross:
                        else if ($size[1] > $hoehe)
                        {
                            $sizemin[0] = $size[0];
                            $sizemin[1] = $hoehe;
                        }
                        // bild ok:
                        else
                        {
                            $sizemin[0] = $sizemin[0];
                            $sizemin[1] = $sizemin[1];
                        }
                    
                        if (preg_match("/.*" . $suffix . ".*/", $val)) continue ;
                    
                        if (preg_match("/jpg$|gif$|png$/", $val))
                        {
							echo "<td><img src='../images/stories/cards/".$val."' width='" . $sizemin[0]  . "' height='" . $sizemin[1] . "' align=top style='padding:5px; margin:5px; border:1px solid black; background-color:#ffffff;'><br>".JText::_('UPLOAD_IMAGE_NAME').": ".$val."</td>";
                            $i++;
                        }
                    
                        echo ($i % $adminimagesperrow == 0) ? "</tr><tr>" : "";
                    
                    }

                    ?>
                </tr>
            </table>
            </td>
        </tr>
        </table>
    </div>
    <div class="clr">
    </div>
    <input type="hidden" name="option" value="com_rwcards" /><input type="hidden" name="task" value="" /><input type="hidden" name="controller" value="manageuploadecards" />
</form>