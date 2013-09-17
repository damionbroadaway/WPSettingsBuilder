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
        if (file_exists('WPSettingsBuilder_Data.php')) {
            require_once 'WPSettingsBuilder_Data.php';
        }

        $this->_menuData    = WPSettingsBuilder_Data::menu();
        $this->_subMenuData = WPSettingsBuilder_Data::subMenu();
        $this->_sectionData = WPSettingsBuilder_Data::section();
        $this->_fieldData   = WPSettingsBuilder_Data::fields();
        $this->_cptData     = WPSettingsBuilder_Data::customPostTypes();
        $this->_taxData     = WPSettingsBuilder_Data::taxonomy();
    }

    /**
     * A wrapper for WordPress's method of adding
     *  menu items to the Dashboard menu
     *
     * Method expects the following well formatted array:
     *
     * array(
     *      array(
                'page_title'    =>  '',
                'menu_title'    =>  '',
                'capability'    =>  '',
                'function'      =>  array(
                    '',
                    ''
                ),
                'icon_url'      =>  '',
                'position'      =>  ''
            ),
     *      array(...
     *      ),
     *      array(...
     *      ),
     *      Add as many sub arrays as need for menu items.
     * );
     *
     * @see     WPSettingsBuilder_Data.php for source data
     * @link    http://codex.wordpress.org/Function_Reference/add_menu_page
     *
     */
    public function addAdminMenu()
    {
        foreach ($this->_menuData as $menu_data){
            add_menu_page(
                $menu_data['page_title'],
                $menu_data['menu_title'],
                $menu_data['capability'],
                $menu_data['menu_slug'],
                $menu_data['function'],
                $menu_data['icon_url'],
                $menu_data['position']
            );
        }
    }

    /**
     * A wrapper for WordPress's method of adding
     *  submenu items to the Dashboard menu
     *
     * Method expects $args to be the following well formatted array:
     *
     * array(
     *      array(
     *          'parent_slug'   =>  '',
     *          'page_title'    =>  '',
     *          'menu_title'    =>  '',
     *          'capability'    =>  '',
     *          'menu_slug'     =>  '',
     *          'function'      => array(
     *              '',
     *              ''
     *          )
     *      ),
     *      array(...
     *      ),
     *      array(...
     *      )
     *      Add as many sub arrays as needed to represent all submenu items
     * )
     *
     * @see   WPSettingsBuilder_Data.php for source data
     * @link  http://codex.wordpress.org/Function_Reference/add_submenu_page
     */
    public function addAdminSubMenu()
    {
        foreach ($this->_subMenuData as $sub_menu_data) {
            add_submenu_page(
                $sub_menu_data['parent_slug'],
                $sub_menu_data['page_title'],
                $sub_menu_data['menu_title'],
                $sub_menu_data['capability'],
                $sub_menu_data['menu_slug'],
                $sub_menu_data['function']
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
                'id'        =>  '',
                'title'     =>  '',
                'callback'  =>  array(
                    '',
                    ''
                ),
                'page'      =>  '',
            ),
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
        foreach ($this->_sectionData as $section_data) {
            add_settings_section(
                $section_data['id'],
                $section_data['title'],
                array(
                     $section_data['callback_class'],
                     $section_data['callback_method']
                ),
                $section_data['page']
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
        foreach ($this->_fieldData as $field_data ) {
            add_settings_field(
                $field_data['id'],
                $field_data['title'],
                $field_data['callback'],
                $field_data['page'],
                $field_data['section'],
                $field_data['args']
            );
            register_setting(
                $field_data['option_group'],
                $field_data['option_name'],
                $field_data['sanitize_callback']
            );
        }
    }

    public function addCustomPostType()
    {
        foreach ( $this->_cptData as $cpt_data ) {
            $labels = array(
                'name'
                => _x($cpt_data['post_type_singular'], 'post type general name'),
                'singular_name'
                => _x($cpt_data['post_type_singular'], 'post type singular name'),
                'add_new'
                => _x('Add New', $cpt_data['post_type_singular']),
                'add_new_item'
                => __('Add New ' . $cpt_data['post_type_singular']),
                'edit_item'
                => __('Edit ' . $cpt_data['post_type_singular']),
                'new_items'
                => __('New ' . $cpt_data['post_type_singular']),
                'all_items'
                => __('All ' . $cpt_data['post_type_plural']),
                'view_item'
                => __('View ' . $cpt_data['post_type_plural']),
                'search_items'
                => __('Search ' . $cpt_data['post_type_plural']),
                'not_found'
                => __('No ' . $cpt_data['post_type_plural'] . ' found'),
                'not_found_in_trash'
                => __('No ' . $cpt_data['post_type_plural'] . ' found in the trash'),
                'parent_item_colon'
                => '',
                'menu_name'
                => $cpt_data['post_type_plural']
            );
            $cpt_data['cpt_args']['label'] = $cpt_data['post_type_plural'];
            $cpt_data['cpt_args']['labels'] = $labels;
            register_post_type($cpt_data['post_type'], $cpt_data['cpt_args']);
        }
    }

    public function addCustomTaxonomy()
    {
        foreach ( $this->_taxData as $taxonomy_data ) {
            $labels = array(
                'name'              => _x($taxonomy_data['plural_tax_name'], 'taxonomy general name'),
                'singular_name'     => _x($taxonomy_data['singular_tax_name'], 'taxonomy singular name'),
                'search_items'      => __('Search ' . $taxonomy_data['plural_tax_name']),
                'all_items'         => __('All ' . $taxonomy_data['plural_tax_name']),
                'parent_item'       => __('Parent ' . $taxonomy_data['singular_tax_name']),
                'parent_item_colon' => __('Parent ' . $taxonomy_data['singular_tax_name'] . ':'),
                'edit_item'         => __('Edit ' . $taxonomy_data['singular_tax_name']),
                'update_item'       => __('Update ' . $taxonomy_data['singular_tax_name']),
                'add_new_item'      => __('Add New ' . $taxonomy_data['singular_tax_name']),
                'new_item_name'     => __('New ' . $taxonomy_data['singular_tax_name'] . ' Name'),
                'menu_name'         => __($taxonomy_data['plural_tax_name']),
            );
            $taxonomy_data['tax_args']['labels'] = $labels;
            register_taxonomy($taxonomy_data['tax_type'], $taxonomy_data['for_post_type_of'], $taxonomy_data['tax_args']);
        }

    }
 
}