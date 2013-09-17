<?php
/**
 * TODO: description for WPSettingsBuilder_Data.php
 *
 * @package    WPSettingsBuilder
 * @subpackage WPSettingsBuilder_Data.php
 * @version    $Id$
 * @created    9/16/13 at 2:26 PM
 */

/**
 * TODO: description for WPSettingsBuilder:WPSettingsBuilder_Data
 *
 * @package    WPSettingsBuilder
 * @subpackage WPSettingsBuilder_Data
 * @version    $Id$
 * @author     Damion M Broadaway <dbroadaw@nerdery.com>
 * @created    9/16/13 at 2:26 PM
 */
class WPSettingsBuilder_Data
{
    public function __construct()
    {

    }

    public static function menu()
    {
        return array(
            array(
                'page_title'    =>  '',
                'menu_title'    =>  '',
                'capability'    =>  '',
                'function'      =>  array(
                    '', //  Callback class.
                    ''  //  Callback method.
                ),
                'icon_url'      =>  '',
                'position'      =>  ''
            ),
            //  Copy above array to here add additional menu items.
            //  Repeat as needed.
        );
    }

    public static  function subMenu()
    {
        return array(
            array(
                'parent_slug'   =>  '',
                'page_title'    =>  '',
                'menu_title'    =>  '',
                'capability'    =>  '',
                'menu_slug'     =>  '',
                'function'      => array(
                    '', //  Callback class
                    ''  //  Callback method
                )
            ),
            //  Copy above array to here to add additional submenu items.
            //  Repeat as needed.
        );
    }

    public static function section()
    {
        return array(
            array(
                'id'        =>  '',
                'title'     =>  '',
                'callback'  =>  array(
                    '', //  Callback class
                    ''  //  Callback method
                ),
                'page'      =>  '',
            ),
            //  Copy above array to here to add additional sections.
            //  Repeat as needed.
        );
    }

    public static function fields()
    {
        return array();
    }

    public static function customPostTypes()
    {
        return array();
    }

    public static  function taxonomy()
    {
        return array();
    }

}