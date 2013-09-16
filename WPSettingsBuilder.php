<?php
/**
 * TODO: description for WPSettingsBuilder.php
 *
 * @package    WPSettingsBuilder
 * @subpackage WPSettingsBuilder.php
 * @version    $Id$
 * @created    9/16/13 at 2:23 PM
 */

/**
 * TODO: description for WPSettingsBuilder:WPSettingsBuilder
 *
 * @package    WPSettingsBuilder
 * @subpackage WPSettingsBuilder
 * @version    $Id$
 * @author     Damion M Broadaway <dbroadaw@nerdery.com>
 * @created    9/16/13 at 2:23 PM
 */
class WPSettingsBuilder
{
    private $_menuData;
    private $_subMenuData;
    private $_sectionData;
    private $_fieldData;
    private $_cptData;
    private $_taxData;

    public function __construct()
    {
        include_once 'WPSettingsBuilder_Data.php';
        $this->_menuData = WPSettingsBuilder_Data::menu();
        $this->_sectionData = WPSettingsBuilder_Data::section();
        $this->_fieldData = WPSettingsBuilder_Data::fields();
        $this->_cptData = WPSettingsBuilder_Data::customPostTypes();
        $this->_taxData = WPSettingsBuilder_Data::taxonomy();
        $this->_subMenuData = WPSettingsBuilder_Data::subMenu();

        //include_once 'WPSettingsBuilder_SectionCallbacks.php';
        //include_once 'WPSettingsBuilder_FieldCallbacks.php';
    }

    public function addAdminMenu()
    {
        foreach ($this->_menuData as $value){
            add_menu_page(
                $value['pageTitle'],
                $value['menuTitle'],
                $value['cap'],
                $value['menuSlug'],
                $value['callBack'],
                $value['icon'],
                $value['position']
            );
        }
    }

    /**
     * A wrapper for WordPress's method of adding
     *  sub items to the dashboard menu
     *
     * Method expects $args to be the following well formatted array:
     *
     * array(
     *      array(
     *          'parentSlug'        => '',
     *          'pageTitle'         => '',
     *          'menuTitle'         => '',
     *          'cap'               => '',
     *          'menuSlug'          => '',
     *          'callbackClass'     => '',
     *          'callbackMethod'    => ''
     *      ),
     *      array(...
     *      ),
     *      array(...
     *      )
     *      Add as many sub arrays as needed to represent all menu sub-items
     * )
     *
     * @see   WPSettingsBuilder_Data.php for source data
     * @link  http://codex.wordpress.org/Function_Reference/add_submenu_page
     */
    public function addAdminSubMenu()
    {
        foreach ($this->_subMenuData as $value) {
            add_submenu_page(
                $value['parentSlug'],
                $value['pageTitle'],
                $value['menuTitle'],
                $value['cap'],
                $value['menuSlug'],
                array(
                     $value['callbackClass'],
                     $value['callbackMethod']
                )
            );
        }
    }

    /**
     * A wrapper for WordPress's method to add admin option sections
     *
     * Method expects $args to be the following well formatted array
     *
     * array(
     *      array(
     *          'slug'              => '',
     *          'title'             => '',
     *          'callbackClass'     => '',
     *          'callbackMethod'    => '',
     *          'optionGroup'       => ''
     *      ),
     *      array(...
     *      ),
     *      array(...
     *      )
     *      Add as many sub arrays as needed to represent all sections
     * )
     *
     * @see   WPSettingsBuilder_Data.php for source data
     * @link  http://codex.wordpress.org/Function_Reference/add_settings_section
     */
    public function addAdminSections()
    {
        foreach ($this->_sectionData as $value) {
            add_settings_section(
                $value['slug'],
                $value['title'],
                array(
                     $value['callbackClass'],
                     $value['callbackMethod']
                ),
                $value['optionGroup']
            );
        }
    }

    /**
     * A wrapper for WordPress's add_settings_field & register_settings
     *  methods
     *
     * Method expects $args to be the following well formatted array
     *
     * array(
     *      array(
     *          'slug'              => '',
     *          'title'             => '',
     *          'callbackClass'     => '',
     *          'callbackMethod'    => '',
     *          'optionGroup'       => '',
     *          'section'           => '',
     *          'class'             => '',
     *          'desc'              => '',
     *      ),
     *      array(...
     *      ),
     *      array(...
     *      )
     *      Add as many sub arrays as needed to represent all fields
     * )
     *
     * @see   WPSettingsBuilder_Data.php for source data
     * @link  http://codex.wordpress.org/Function_Reference/add_settings_section
     */
    public function addAdminFields()
    {
        foreach ($this->_fieldData as $value ) {
            add_settings_field(
                $value['slug'],
                $value['title'],
                array(
                     $value['callbackClass'],
                     $value['callbackMethod']
                ),
                $value['optionGroup'],
                $value['section'],
                $value
            );
            register_setting(
                $value['optionGroup'],
                $value['slug']
            );
        }
    }

    public function addCustomPostType()
    {
        foreach ( $this->_cptData as $cpt ) {
            $labels = array(
                'name'
                => _x($cpt['post_type_singular'], 'post type general name'),
                'singular_name'
                => _x($cpt['post_type_singular'], 'post type singular name'),
                'add_new'
                => _x('Add New', $cpt['post_type_singular']),
                'add_new_item'
                => __('Add New ' . $cpt['post_type_singular']),
                'edit_item'
                => __('Edit ' . $cpt['post_type_singular']),
                'new_items'
                => __('New ' . $cpt['post_type_singular']),
                'all_items'
                => __('All ' . $cpt['post_type_plural']),
                'view_item'
                => __('View ' . $cpt['post_type_plural']),
                'search_items'
                => __('Search ' . $cpt['post_type_plural']),
                'not_found'
                => __('No ' . $cpt['post_type_plural'] . ' found'),
                'not_found_in_trash'
                => __('No ' . $cpt['post_type_plural'] . ' found in the trash'),
                'parent_item_colon'
                => '',
                'menu_name'
                => $cpt['post_type_plural']
            );
            $cpt['cpt_args']['label'] = $cpt['post_type_plural'];
            $cpt['cpt_args']['labels'] = $labels;
            register_post_type($cpt['post_type'], $cpt['cpt_args']);
        }
    }

    public function addCustomTaxonomy()
    {
        foreach ( $this->_taxData as $tax ) {
            $labels = array(
                'name'              => _x($tax['plural_tax_name'], 'taxonomy general name'),
                'singular_name'     => _x($tax['singular_tax_name'], 'taxonomy singular name'),
                'search_items'      => __('Search ' . $tax['plural_tax_name']),
                'all_items'         => __('All ' . $tax['plural_tax_name']),
                'parent_item'       => __('Parent ' . $tax['singular_tax_name']),
                'parent_item_colon' => __('Parent ' . $tax['singular_tax_name'] . ':'),
                'edit_item'         => __('Edit ' . $tax['singular_tax_name']),
                'update_item'       => __('Update ' . $tax['singular_tax_name']),
                'add_new_item'      => __('Add New ' . $tax['singular_tax_name']),
                'new_item_name'     => __('New ' . $tax['singular_tax_name'] . ' Name'),
                'menu_name'         => __($tax['plural_tax_name']),
            );
            $tax['tax_args']['labels'] = $labels;
            register_taxonomy($tax['tax_type'], $tax['for_post_type_of'], $tax['tax_args']);
        }

    }
 
}