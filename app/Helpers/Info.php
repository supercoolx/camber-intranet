<?php

namespace App\Helpers;
//TODO rename file!!!
/*
 * Usage
  <script src="<?php echo Info::getNotificationsLibraryUri(); ?>?<?php echo SCRIPTS_VERSION; ?>"></script>
  <?php echo Info::getNotificationsJs(); ?>
 * //TODO make simpler
 *
 */
class Info
{
    private $system_message = [];

    public static function setMessage($str, $location = 'standart')
    {

        //self::$system_message[$location] = "<div class='alert alert-success'>$str</div>";
        $_SESSION['system_message'][$location] = "<div class='alert alert-success'>$str</div>";
        return $_SESSION['system_message'][$location];
    }

    public static function message($str, $class = 'success')
    {
        $script = '<script>
					 $(document).ready(function(){ 
						 if( $(".actionResults .alert-success").length > 1) { 
							 $(".actionResults .alert-success:first").remove();
						 } 
					 });
					</script>';
        return $script . "<div class='alert alert-$class'>$str</div>";
    }

    public static function setError($str, $location = 'standart')
    {
        //self::$system_message[$location] = "<div class='alert alert-error'>$str</div>";
        $_SESSION['system_message'][$location] = "<div class='alert alert-error'>$str</div>";
    }

    public static function setWarning($str, $location = 'standart')
    {
        //self::$system_message[$location] = "<div class='alert alert-error'>$str</div>";
        $_SESSION['system_message'][$location] = "<div class='alert alert-error'>$str</div>";
    }

    public static function getResults($location = 'standart')
    {

//		if (self::$system_message[$location] != '') {
//			$result = self::$system_message[$location];
//			self::$system_message[$location] = "";
//			return $result;
//		}

        if (isset($_SESSION['system_message'][$location]) && $_SESSION['system_message'][$location] != '') {
            $result = $_SESSION['system_message'][$location];
            $_SESSION['system_message'][$location] = "";
            return $result;
        }
    }

    public static function getNotificationsLibraryUri()
    {
        return "/functions/info.js";
    }

    public function initNotifications($selector)
    {
        return Info::getNotificationsJs($selector);
    }

    public static function getNotificationsJs($selector)
    {
        return "
            <style>
                $selector{
                   display:none;
                }
            </style>
            <script>
                $(document).ajaxComplete(function (event, xhr, settings) {
                    if (xhr.responseText.indexOf('alert-success') > -1) {
                        message = Info.getBetween(xhr.responseText, '<div class=[\"\']?alert alert-success[\"\']?>', '<\/div>');
                        if (message !== '') {
                            Info.notyMessage(message);
                        }
                    }
                });
        
                $(document).ready(function(){
                          var message = $('{$selector}').html();
                          Info.notyMessage(message);
                });
            </script>
            ";
    }

}

