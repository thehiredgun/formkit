<?php

namespace FormKit\TemplateKit;

use FormKit\TemplateKit\TemplateType\AutocompleteType;
use FormKit\TemplateKit\TemplateType\DateType;
use FormKit\TemplateKit\TemplateType\EmailType;
use FormKit\TemplateKit\TemplateType\HiddenType;
use FormKit\TemplateKit\TemplateType\PasswordType;
use FormKit\TemplateKit\TemplateType\RadioType;
use FormKit\TemplateKit\TemplateType\SelectType;
use FormKit\TemplateKit\TemplateType\SubmitType;
use FormKit\TemplateKit\TemplateType\TextareaType;
use FormKit\TemplateKit\TemplateType\TextType;
use FormKit\TemplateKit\Utility;
use Illuminate\Support\Facades\View;

/**
 * template kit
 *
 * @author Nick Wakeman <nick@thehiredgun.tech>
 * @since  2017-06-16
 */
class TemplateKit
{
    protected static $data;
    protected static $errors;

    /**
     * create
     *
     * @param Model $data
     * @param array $errors
     */
    public static function create($data, $errors)
    {
        self::$data = $data;
        self::$errors = $errors;
        View::addNamespace('templatekit', __dir__ . '/resources/views');
    }

    public static function addErrorAlert()
    {
        return view('templatekit::error-alert', [
            'errors' => self::$errors,
        ]);
    }

    private static function addTemplate(string $class, string $name, array $options)
    {
        $options['name'] = $name;
        $element = Utility::instantiate($class, $options, true);
        return view($element->getTemplate(), [
            'element' => $element,
            'data'    => self::$data,
            'errors'  => self::$errors,
        ]);
    }

    public static function addText(string $name, array $options = [])
    {
        return self::addTemplate(TextType::class, $name, $options);
    }

    public static function addEmail(string $name, array $options = [])
    {
        return self::addTemplate(EmailType::class, $name, $options);
    }

    public static function addPassword(string $name, array $options = [])
    {
        return self::addTemplate(PasswordType::class, $name, $options);
    }

    public static function addTextarea(string $name, array $options = [])
    {
        return self::addTemplate(TextareaType::class, $name, $options);
    }

    public static function addAutocomplete(string $name, array $options = [])
    {
        return self::addTemplate(AutocompleteType::class, $name, $options);
    }

    public static function addDate(string $name, array $options = [])
    {
        return self::addTemplate(DateType::class, $name, $options);
    }

    public static function addHidden(string $name, array $options = [])
    {
        return self::addTemplate(HiddenType::class, $name, $options);
    }

    public static function addRadio(string $name, array $options = [])
    {
        return self::addTemplate(RadioType::class, $name, $options);
    }

    public static function addSelect(string $name, array $options = [])
    {
        return self::addTemplate(SelectType::class, $name, $options);
    }

    public static function addSubmit(array $options = [])
    {
        $element = Utility::instantiate(SubmitType::class, $options);
        return view($element->getTemplate(), [
            'element' => $element,
        ]);
    }

    public static function addCsrf()
    {
        return view('templatekit::csrf');
    }
}
