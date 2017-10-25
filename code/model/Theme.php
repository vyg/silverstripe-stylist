<?php

class Theme extends DataObject implements PermissionProvider, TemplateGlobalProvider
{
    private static $db = [
        'PrimaryColour' => 'Varchar(7)',
        'SecondaryColour' => 'Varchar(7)',
        'HeadingFontFamily' => 'Varchar(255)',
        'FontFamily' => 'Varchar(255)'
    ];

    public function getCMSFields()
    {
        $fields = new FieldList(
            new TabSet("Root",
                $tabMain = new Tab('Main',
                    new TextField("PrimaryColour", 'Primary Colour'),
                    new TextField("SecondaryColour", 'Secondary Colour'),
                    new TextField('HeadingFontFamily', 'Heading Font Family'),
                    new TextField("FontFamily", "Body Font Family")
                )
            ),
            new HiddenField('ID')
        );

        $tabMain->setTitle("Main");
        $this->extend('updateCMSFields', $fields);

        return $fields;
    }


    /**
     * Setup a default SiteConfig record if none exists.
     */
    public function requireDefaultRecords()
    {
        parent::requireDefaultRecords();

        $config = DataObject::get_one('Theme');

        if (!$config) {
            self::make_theme_config();

            DB::alteration_message("Added default Theme config", "created");
        }
    }

    /**
     * Get the current sites SiteConfig, and creates a new one through
     * {@link make_site_config()} if none is found.
     *
     * @return SiteConfig
     */
    public static function current_theme_config()
    {
        if ($themeConfig = DataObject::get_one('Theme')) {
            return $themeConfig;
        }

        return self::make_theme_config();
    }


    /**
     * Create SiteConfig with defaults from language file.
     *
     * @return SiteConfig
     */
    public static function make_theme_config()
    {
        $config = Theme::create();
        $config->write();

        return $config;
    }

    /**
     * @return void
     */
    public function providePermissions()
    {
        return array(
            'EDIT_THEME' => array(
                'name' => _t('Theme.EDIT_PERMISSION', 'Manage site configuration'),
                'category' => _t('Permissions.PERMISSIONS_CATEGORY', 'Roles and access permissions'),
                'help' => _t('Theme.EDIT_PERMISSION_HELP', 'Ability to edit global access settings/top-level page permissions.'),
                'sort' => 400
            )
        );
    }

    /**
     * Get the actions that are sent to the CMS.
     *
     * In your extensions: updateEditFormActions($actions)
     *
     * @return FieldList
     */
    public function getCMSActions()
    {
        // if (Permission::check('ADMIN') || Permission::check('EDIT_THEME')) {
        $actions = new FieldList(
        FormAction::create('save_themeconfig', _t('CMSMain.SAVE', 'Save'))
          ->addExtraClass('ss-ui-action-constructive')->setAttribute('data-icon', 'accept')
      );
        // } else {
        //   $actions = new FieldList();
        // }

        $this->extend('updateCMSActions', $actions);

        return $actions;
    }


    /**
     * Add $SiteConfig to all SSViewers
     */
    public static function get_template_global_variables()
    {
        return array(
      'Theme' => 'current_theme_config',
    );
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        $this->createStylesheet();
    }

    /*
     * Create a css stylesheet from our values
     * and save to the assets directory
     */
    public function createStylesheet()
    {
        $theme = DataObject::get_one('Theme');
        if ($theme) {
            $content = '
:root {
  --primary-colour: ' .$theme->PrimaryColour.';
  --secondary-colour: ' . $theme->SecondaryColour . ';
  --font-family: '.$theme->FontFamily.';
  --heading-font-family: '.$theme->HeadingFontFamily.';
}';

            $directory = ASSETS_PATH . '/css';
            $file = $directory.'/theme.css';

            if (!is_dir($directory)) {
                mkdir($directory);
            }

            file_put_contents($file, $content);
        }
        return;
    }
}
