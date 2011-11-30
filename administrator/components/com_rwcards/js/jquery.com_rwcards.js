/**
 * RWCards 3.x
 * Project page - http://www.weberr.de/
 * Copyright (c) 2009 LoadBrain, http://wwww.loadbrain.de
 * Version 3.0.16 (23.04.2009)
 */

/**
 * no conflict with other libs
 */
var $jQ = jQuery.noConflict();


$jQ(document).ready(function(){

$jQ('#uploadedHeader').hide();
	/**
	 * Click on upload
	 */
    $jQ('#uploadLink').click(function(){
        $jQ.get('./index.php?option=com_rwcards&controller=manageuploadecards', {
            task: 'uploadImagesToDir'
        }, function(data){
            $jQ('#dirUploadPathes').html(data);
        });
        
        $jQ('#uploadWrapper').hide();
        $jQ('#upload').fadeIn(1500);
    });
    
    
    /**
     * File Upload
     */
    var button = $jQ('#button1'), interval;
    
    var imageUpload = new AjaxUpload(button, {
        action: './index.php?option=com_rwcards&controller=manageuploadecards',
        name: 'userfile',
        
        onSubmit: function(file, ext){
            // change button text, when user selects file			
            button.text('Uploading');
            this.setData({
                'uploadDir': '/images/stories/cards',
                'task': 'uploadImagesToDir',
                'imgName': file
            });
            
            
            // Uploding -> Uploading. -> Uploading...
            interval = window.setInterval(function(){
                var text = button.text();
                if (text.length < 13) {
                    button.text(text + '.');
                }
                else {
                    button.text('Uploading');
                }
            }, 200);
        },
        onComplete: function(file, response){
            if (response) {
                $jQ('#uploadWrapper').show();
                $jQ('#uploadedHeader').show();
                button.text('Upload successful');
                button.fadeTo("slow", 0.33, function(){
                    button.fadeTo("slow", 1, function(){
                        button.text("Upload");
                    });
                });
                window.clearInterval(interval);
                
                // enable upload button
                this.enable();
                return false;
            }
            else {
                $jQ('#uploadWrapper').show();
				$jQ('#uploadedHeader').show();
                button.text('Upload successful');
                button.fadeTo("slow", 0.33, function(){
                    button.fadeTo("slow", 1, function(){
                        button.text("Upload");
                    });
                });
                window.clearInterval(interval);
                
                // enable upload button
                this.enable();
            }
            
            $jQ('<span style="background-image: url(images/tick.png); background-repeat:no-repeat; background-position: 0 center; 	display:block; margin: 10px;width:auto; padding: 5px 5px 5px 20px;"></span>').appendTo('#uploadLeft .files').text(file);
            
        }
    });
    
});// End ready
