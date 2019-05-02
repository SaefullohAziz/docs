<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Form;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Form::component('bsText', 'layouts.components.form.text', [
            'class' => null, 
            'label', 
            'name', 
            'value' => null, 
            'placeholder', 
            'attributes' => [], 
            'helpTexts' => []
        ]);
        Form::component('bsPhoneNumber', 'layouts.components.form.phoneNumber', [
            'class' => null, 
            'label', 
            'name', 
            'value' => null, 
            'placeholder', 
            'attributes' => [], 
            'helpTexts' => []
        ]);
        Form::component('bsEmail', 'layouts.components.form.email', [
            'class' => null, 
            'label', 
            'name', 
            'value' => null, 
            'placeholder', 
            'attributes' => [], 
            'helpTexts' => []
        ]);
        Form::component('bsSelect', 'layouts.components.form.select', [
            'class' => null, 
            'label', 
            'name', 
            'option' => [], 
            'value' => null, 
            'dataPlaceholder', 
            'attributes' => [], 
            'helpTexts' => []
        ]);
        Form::component('bsPassword', 'layouts.components.form.password', [
            'class' => null, 
            'label', 
            'name', 
            'placeholder', 
            'attributes' => [], 
            'helpTexts' => []
        ]);
        Form::component('bsTextarea', 'layouts.components.form.textarea', [
            'class' => null, 
            'label', 
            'name', 
            'value' => null, 
            'placeholder', 
            'attributes' => [], 
            'helpTexts' => []
        ]);
        Form::component('bsFile', 'layouts.components.form.file', [
            'class' => null, 
            'label', 
            'name', 
            'value' => null, 
            'attributes' => [], 
            'helpTexts' => []
        ]);
        Form::component('bsCheckboxList', 'layouts.components.form.checkboxList', [
            'class' => null, 
            'label', 
            'name', 
            'options' => [], 
            'value' => [], 
            'attributes' => [], 
            'helpTexts' => []
        ]);
        Form::component('bsInlineRadio', 'layouts.components.form.inlineRadio', [
            'class' => null, 
            'label', 
            'name', 
            'options' => [], 
            'value' => null, 
            'attributes' => [], 
            'helpTexts' => []
        ]);
        Form::component('bsHidden', 'layouts.components.form.hidden', [
            'class' => null, 
            'label', 
            'name', 
            'value' => null, 
            'attributes' => [], 
            'helpTexts' => []
        ]);
    }
}
