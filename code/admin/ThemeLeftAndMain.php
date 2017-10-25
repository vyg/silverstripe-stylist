<?php

class ThemeLeftAndMain extends LeftAndMain {
  private static $menu_title = 'Theme';
  private static $url_segment = 'theme';
  private static $tree_class = 'Theme';
  private static $url_rule = '/$Action/$ID/$OtherID';

  /**
   * @var array
   */
  private static $allowed_actions = array(
      'EditForm'
  );

  public function getEditForm($id = null, $fields = null) {
    $themeConfig = Theme::current_theme_config();
    $fields = $themeConfig->getCMSFields();

    // Tell the CMS what URL the preview should show
    $home = Director::absoluteBaseURL();
    $fields->push(new HiddenField('PreviewURL', 'Preview URL', $home));

    // Added in-line to the form, but plucked into different view by LeftAndMain.Preview.js upon load
    $fields->push($navField = new LiteralField('SilverStripeNavigator', $this->getSilverStripeNavigator()));
    $navField->setAllowHTML(true);

    // Retrieve validator, if one has been setup (e.g. via data extensions).
    if ($themeConfig->hasMethod("getCMSValidator")) {
      $validator = $themeConfig->getCMSValidator();
    } else {
      $validator = null;
    }

    $actions = $themeConfig->getCMSActions();

    $form = CMSForm::create(
      $this,
      'EditForm',
      $fields,
      $actions
    )->setHTMLID('Form_EditForm');

    if( $form->Fields()->hasTabset() ) {
       $form->Fields()->findOrMakeTab('Root')->setTemplate('CMSTabSet');
    }

    $form->setResponseNegotiator($this->getResponseNegotiator());
    $form->addExtraClass('cms-content center cms-edit-form');
    $form->setHTMLID('Form_EditForm');
    $form->loadDataFrom($themeConfig);
    $form->setTemplate($this->getTemplatesWithSuffix('_EditForm'));

    // Use <button> to allow full jQuery UI styling
    // $actions = $actions->dataFields();
    // if($actions) foreach($actions as $action) $action->setUseButtonTag(true);

    $this->extend('updateEditForm', $form);

    return $form;
  }

  /**
   * Return the edit form
   *
   * @param null $request
   * @return Form
   */
  public function EditForm($request = null)
  {
      return $this->getEditForm();
  }


  /**
   * Save the current sites {@link SiteConfig} into the database.
   *
   * @param array $data
   * @param Form $form
   * @return String
   */
  public function save_themeconfig($data, $form) {
    $themeConfig = Theme::current_theme_config();
    $form->saveInto($themeConfig);

    try {
      $themeConfig->write();
    } catch(ValidationException $ex) {
      $form->sessionMessage($ex->getResult()->message(), 'bad');
      return $this->getResponseNegotiator()->respond($this->request);
    }

    $this->response->addHeader('X-Status', rawurlencode(_t('LeftAndMain.SAVEDUP', 'Saved.')));

    return $form->forTemplate();
  }

  /**
   * @return mixed
   */
  public function getResponseNegotiator()
  {
      $neg = parent::getResponseNegotiator();
      $controller = $this;
      $neg->setCallback('CurrentForm', function () use (&$controller) {
          return $controller->renderWith($controller->getTemplatesWithSuffix('_Content'));
      });
      return $neg;
  }

	/**
	 * Used for preview controls, mainly links which switch between different states of the page.
	 *
	 * @return ArrayData
	 */
	public function getSilverStripeNavigator() {
		return $this->renderWith('ThemeLeftAndMain_SilverStripeNavigator');
	}

  public function Breadcrumbs($unlinked = false) {
    $defaultTitle = self::menu_title_for_class(get_class($this));

    return new ArrayList(array(
      new ArrayData(array(
        'Title' => _t("{$this->class}.MENUTITLE", $defaultTitle),
        'Link' => $this->Link()
      ))
    ));
  }

}
